<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function notice(): \Illuminate\View\View
    {
        return view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        activity()->causedBy($request->user())->log('E-posta doğrulandı.');
        $request->user()->appNotifications()->create([
            'title' => 'E-posta doğrulandı',
            'message' => 'E-posta adresiniz başarıyla doğrulandı.',
            'type' => 'success',
            'url' => route('dashboard'),
        ]);

        return redirect()->intended(route('dashboard'))->with('status', 'E-posta adresiniz doğrulandı.');
    }

    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Doğrulama bağlantısı e-posta adresinize gönderildi.');
    }
}
