<?php

namespace App\Http\Controllers;

use App\Models\ManagedFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileManagerController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('manage files'), 403);

        return view('file-manager.index', [
            'files' => ManagedFile::query()->with('user')->latest()->paginate(12),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('manage files'), 403);

        $data = $request->validate([
            'file' => ['required', 'file', 'max:10240'],
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

        return back()->with('status', 'Dosya yüklendi.');
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
        $managedFile->delete();
        activity()->causedBy($request->user())->log('Dosya silindi.');

        return back()->with('status', 'Dosya silindi.');
    }
}
