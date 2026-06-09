<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, AppNotification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === $request->user()->id, 403);

        $notification->update(['read_at' => now()]);

        return $notification->url
            ? redirect($notification->url)
            : back()->with('status', 'Bildirim okundu.');
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->appNotifications()->unread()->update(['read_at' => now()]);

        return back()->with('status', 'Tüm bildirimler okundu.');
    }
}
