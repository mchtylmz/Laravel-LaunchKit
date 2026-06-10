<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function users(): StreamedResponse
    {
        abort_unless(auth()->user()?->can('manage users'), 403);

        $headers = [['Name', 'Email', 'Role', 'Created At']];

        $rows = User::query()->with('roles')->latest()->get()->map(fn ($user) => [
            $user->name,
            $user->email,
            $user->roles->pluck('name')->join(', '),
            $user->created_at->format('Y-m-d H:i'),
        ]);

        return $this->csvResponse('users-export-'.now()->format('Ymd').'.csv', collect($headers)->merge($rows));
    }

    public function activityLogs(): StreamedResponse
    {
        abort_unless(auth()->user()?->can('view activity logs'), 403);

        $headers = [['User', 'Description', 'Date']];

        $rows = Activity::query()->with('causer')->latest()->limit(5000)->get()->map(fn ($activity) => [
            $activity->causer?->name ?? 'System',
            $activity->description,
            $activity->created_at->format('Y-m-d H:i'),
        ]);

        return $this->csvResponse('activity-logs-export-'.now()->format('Ymd').'.csv', collect($headers)->merge($rows));
    }

    private function csvResponse(string $filename, iterable $rows): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($rows): void {
            $stream = fopen('php://output', 'w');
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp1252');

            foreach ($rows as $row) {
                fputcsv($stream, $row, ',', '"', '');
            }

            fclose($stream);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=cp1252');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$filename}\"");

        return $response;
    }
}
