<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'status' => ['nullable', 'in:all,read,unread'],
            'type' => ['nullable', 'string', 'max:50'],
        ]);

        $query = $request->user()->appNotifications()
            ->when($filters['q'] ?? null, fn ($query, $search) => $query
                ->where(function ($notificationQuery) use ($search): void {
                    $notificationQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                }))
            ->when(($filters['status'] ?? 'all') === 'read', fn ($query) => $query->whereNotNull('read_at'))
            ->when(($filters['status'] ?? 'all') === 'unread', fn ($query) => $query->whereNull('read_at'))
            ->when($filters['type'] ?? null, fn ($query, $type) => $query->where('type', $type))
            ->latest();

        return view('notifications.index', [
            'notifications' => $query->paginate(12)->withQueryString(),
            'filters' => [
                'q' => $filters['q'] ?? null,
                'status' => $filters['status'] ?? 'all',
                'type' => $filters['type'] ?? null,
            ],
            'types' => $request->user()->appNotifications()
                ->select('type')
                ->distinct()
                ->orderBy('type')
                ->pluck('type'),
            'stats' => [
                'total' => $request->user()->appNotifications()->count(),
                'unread' => $request->user()->appNotifications()->unread()->count(),
                'read' => $request->user()->appNotifications()->whereNotNull('read_at')->count(),
            ],
        ]);
    }

    public function markAsRead(Request $request, AppNotification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 403);

        $notification->update(['read_at' => now()]);

        if ($request->boolean('redirect', true) && $notification->url) {
            return redirect($notification->url);
        }

        return back()->with('status', 'Bildirim okundu.');
    }

    public function markAsUnread(Request $request, AppNotification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 403);

        $notification->update(['read_at' => null]);

        return back()->with('status', 'Bildirim okunmamis olarak isaretlendi.');
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:read,unread,delete'],
            'notification_ids' => ['required', 'array', 'min:1'],
            'notification_ids.*' => ['required', 'integer'],
        ]);

        $notificationIds = collect($data['notification_ids'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $notifications = $request->user()->appNotifications()
            ->whereKey($notificationIds)
            ->get();

        abort_if($notifications->count() !== $notificationIds->count(), 403);

        match ($data['action']) {
            'read' => $notifications->each->update(['read_at' => now()]),
            'unread' => $notifications->each->update(['read_at' => null]),
            'delete' => $notifications->each->delete(),
        };

        $messages = [
            'read' => 'Secili bildirimler okundu olarak isaretlendi.',
            'unread' => 'Secili bildirimler okunmamis olarak isaretlendi.',
            'delete' => 'Secili bildirimler silindi.',
        ];

        return back()->with('status', $messages[$data['action']]);
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->appNotifications()->unread()->update(['read_at' => now()]);

        return back()->with('status', 'Tüm bildirimler okundu.');
    }
}
