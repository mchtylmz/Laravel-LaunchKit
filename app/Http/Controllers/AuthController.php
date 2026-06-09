<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Giriş bilgileri hatalı.'])->onlyInput('email');
        }

        $user = Auth::user();

        if ($user->two_factor_enabled) {
            Auth::logout();
            $request->session()->put('two_factor_user_id', $user->id);
            $request->session()->put('two_factor_remember', $request->boolean('remember'));
            $this->sendTwoFactorCode($user);

            return redirect()->route('two-factor.challenge')->with('status', 'Doğrulama kodu e-posta ile gönderildi.');
        }

        $request->session()->regenerate();
        activity()->causedBy($user)->log('Kullanıcı giriş yaptı.');
        $user->appNotifications()->create([
            'title' => 'Yeni giriş',
            'message' => 'Hesabınıza başarılı bir giriş yapıldı.',
            'type' => 'success',
            'url' => route('dashboard'),
        ]);

        return redirect()->intended(route('dashboard'));
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('user');
        Auth::login($user);
        activity()->causedBy($user)->performedOn($user)->log('Yeni kullanıcı kayıt oldu.');

        return redirect()->route('dashboard');
    }

    public function showTwoFactor(): View|RedirectResponse
    {
        if (! session()->has('two_factor_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor');
    }

    public function verifyTwoFactor(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = User::query()->find($request->session()->get('two_factor_user_id'));

        if (! $user || ! $user->two_factor_code_hash || $user->two_factor_expires_at?->isPast()) {
            return redirect()->route('login')->withErrors(['code' => 'Doğrulama kodu geçersiz veya süresi dolmuş.']);
        }

        if (! Hash::check($data['code'], $user->two_factor_code_hash)) {
            return back()->withErrors(['code' => 'Doğrulama kodu hatalı.']);
        }

        $remember = (bool) $request->session()->pull('two_factor_remember', false);
        $request->session()->forget('two_factor_user_id');

        $user->forceFill([
            'two_factor_code_hash' => null,
            'two_factor_expires_at' => null,
        ])->save();

        Auth::login($user, $remember);
        $request->session()->regenerate();
        activity()->causedBy($user)->log('İki aşamalı doğrulama tamamlandı.');

        return redirect()->intended(route('dashboard'));
    }

    public function resendTwoFactor(Request $request): RedirectResponse
    {
        $user = User::query()->find($request->session()->get('two_factor_user_id'));

        if (! $user) {
            return redirect()->route('login');
        }

        $this->sendTwoFactorCode($user);

        return back()->with('status', 'Yeni doğrulama kodu gönderildi.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    private function sendTwoFactorCode(User $user): void
    {
        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'two_factor_code_hash' => Hash::make($code),
            'two_factor_expires_at' => now()->addMinutes(10),
        ])->save();

        Mail::raw("Laravel LaunchKit doğrulama kodunuz: {$code}", function ($message) use ($user): void {
            $message->to($user->email)->subject('Laravel LaunchKit verification code');
        });
    }
}
