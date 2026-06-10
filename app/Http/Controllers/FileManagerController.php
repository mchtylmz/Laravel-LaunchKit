<?php

namespace App\Http\Controllers;

use App\Models\ManagedFile;
use App\Models\User;
use App\Support\LaunchKitSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileManagerController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()?->can('manage files'), 403);

        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'uploader_id' => ['nullable', 'integer', 'exists:users,id'],
            'type' => ['nullable', 'in:image,document,other'],
        ]);

        $query = ManagedFile::query()
            ->with('user')
            ->when($filters['q'] ?? null, fn ($query, $search) => $query->where('original_name', 'like', "%{$search}%"))
            ->when($filters['uploader_id'] ?? null, fn ($query, $uploaderId) => $query->where('user_id', $uploaderId))
            ->when($filters['type'] ?? null, function ($query, $type): void {
                if ($type === 'image') {
                    $query->where('mime_type', 'like', 'image/%');

                    return;
                }

                if ($type === 'document') {
                    $query->where(function ($documentQuery): void {
                        $documentQuery
                            ->where('mime_type', 'like', 'application/%')
                            ->orWhere('mime_type', 'like', 'text/%');
                    });

                    return;
                }

                $query->where(function ($otherQuery): void {
                    $otherQuery
                        ->whereNull('mime_type')
                        ->orWhere(function ($knownTypeQuery): void {
                            $knownTypeQuery
                                ->where('mime_type', 'not like', 'image/%')
                                ->where('mime_type', 'not like', 'application/%')
                                ->where('mime_type', 'not like', 'text/%');
                        });
                });
            });

        $allowedMimes = config('file-manager.allowed_mimes', []);

        return view('file-manager.index', [
            'files' => $query->latest()->paginate(12)->withQueryString(),
            'filters' => $filters,
            'uploaders' => User::query()
                ->whereIn('id', ManagedFile::query()->select('user_id')->distinct())
                ->orderBy('name')
                ->get(['id', 'name']),
            'stats' => [
                'total_files' => ManagedFile::query()->count(),
                'total_size' => ManagedFile::query()->sum('size'),
            ],
            'maxUploadSize' => max(1, LaunchKitSettings::integer('max_upload_size', 10240)),
            'allowedExtensions' => config('file-manager.allowed_extensions', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('manage files'), 403);

        $maxUploadSize = max(1, LaunchKitSettings::integer('max_upload_size', 10240));

        $allowedMimes = config('file-manager.allowed_mimes', []);

        $data = $request->validate([
            'file' => ['required', 'file', 'max:'.$maxUploadSize, 'mimes:'.config('file-manager.allowed_extensions', '')],
        ]);

        $uploaded = $data['file'];
        $path = $uploaded->store('managed-files', 'public');

        $file = ManagedFile::query()->create([
            'user_id' => $request->user()->id,
            'original_name' => $uploaded->getClientOriginalName(),
            'stored_name' => basename($path),
            'path' => $path,
            'disk' => 'public',
            'mime_type' => $uploaded->getMimeType(),
            'size' => $uploaded->getSize() ?: 0,
        ]);

        activity()->causedBy($request->user())->performedOn($file)->log('Dosya yüklendi.');
        $request->user()->appNotifications()->create([
            'title' => 'Dosya yüklendi',
            'message' => "{$file->original_name} dosyası yüklendi.",
            'type' => 'success',
            'url' => route('file-manager.index'),
        ]);

        return back()->with('status', 'Dosya yüklendi.');
    }

    public function show(ManagedFile $managedFile): View|Response
    {
        abort_unless(auth()->user()?->can('manage files'), 403);

        $fileUrl = Storage::disk($managedFile->disk)->url($managedFile->path);
        $isImage = str_starts_with($managedFile->mime_type ?? '', 'image/');
        $isPdf = $managedFile->mime_type === 'application/pdf';

        return view('file-manager.show', compact('managedFile', 'fileUrl', 'isImage', 'isPdf'));
    }

    public function download(ManagedFile $managedFile): StreamedResponse
    {
        abort_unless(auth()->user()?->can('manage files'), 403);

        return Storage::disk($managedFile->disk)->download($managedFile->path, $managedFile->original_name);
    }

    public function destroy(Request $request, ManagedFile $managedFile): RedirectResponse
    {
        abort_unless(auth()->user()?->can('manage files'), 403);

        Storage::disk($managedFile->disk)->delete($managedFile->path);
        $fileName = $managedFile->original_name;
        $managedFile->delete();
        activity()->causedBy($request->user())->log('Dosya silindi.');
        $request->user()->appNotifications()->create([
            'title' => 'Dosya silindi',
            'message' => "{$fileName} dosyası silindi.",
            'type' => 'warning',
            'url' => route('file-manager.index'),
        ]);

        return back()->with('status', 'Dosya silindi.');
    }
}
