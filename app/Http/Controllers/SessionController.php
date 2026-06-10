<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $sessions = DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) use ($request) {
                $session->is_current = $session->id === $request->session()->getId();
                $session->device = $this->parseUserAgent($session->user_agent);
                $session->last_active = now()->diffForHumans(\Carbon\Carbon::createFromTimestamp($session->last_activity));

                return $session;
            });

        return view('profile.sessions', compact('sessions'));
    }

    public function destroyOther(Request $request): RedirectResponse
    {
        $password = $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        activity()->causedBy($request->user())->log('Diğer oturumlar sonlandırıldı.');

        return back()->with('status', 'Diğer oturumlar sonlandırıldı.');
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        if ($id === $request->session()->getId()) {
            return back()->withErrors(['session' => 'Mevcut oturumunuzu buradan sonlandıramazsınız.']);
        }

        DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        return back()->with('status', 'Oturum sonlandırıldı.');
    }

    private function parseUserAgent(?string $ua): array
    {
        $device = 'Bilinmeyen';
        $browser = '';

        if (! $ua) {
            return ['device' => $device, 'browser' => $browser];
        }

        if (preg_match('/iPhone|iPad/', $ua)) {
            $device = 'iOS';
        } elseif (preg_match('/Android/', $ua)) {
            $device = 'Android';
        } elseif (preg_match('/Macintosh|Mac OS X/', $ua)) {
            $device = 'macOS';
        } elseif (preg_match('/Windows/', $ua)) {
            $device = 'Windows';
        } elseif (preg_match('/Linux/', $ua)) {
            $device = 'Linux';
        }

        if (preg_match('/Edg\/(\d+)/', $ua)) {
            $browser = 'Edge';
        } elseif (preg_match('/Chrome\/(\d+)/', $ua)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Firefox\/(\d+)/', $ua)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Safari\/(\d+)/', $ua)) {
            $browser = 'Safari';
        }

        return ['device' => $device, 'browser' => $browser];
    }
}
