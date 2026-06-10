<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\LaunchKitSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password as PasswordFacade;
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
        if (! LaunchKitSettings::boolean('registration_enabled', true)) {
            return redirect()->route('login')->withErrors([
                'email' => 'Yeni kayitlar su anda kapali.',
            ]);
        }

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

    public function showForgotPassword(): View
    {
        return view('auth.forgot');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email', 'exists:users,email']]);

        $token = PasswordFacade::broker()->createToken(User::query()->where('email', $data['email'])->first());

        Mail::raw("Şifre sıfırlama linkiniz: ".route('password.reset', $token), function ($message) use ($data): void {
            $message->to($data['email'])->subject('Laravel LaunchKit – Şifre Sıfırlama');
        });

        return back()->with('status', 'Şifre sıfırlama linki e-posta adresinize gönderildi.');
    }

    public function showResetForm(string $token): View|RedirectResponse
    {
        return view('auth.reset', compact('token'));
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $broker = PasswordFacade::broker();

        $user = User::query()->where('email', $data['email'])->first();

        if (! $broker->tokenExists($user, $data['token'])) {
            return back()->withErrors(['email' => 'Geçersiz veya süresi dolmuş şifre sıfırlama bağlantısı.']);
        }

        $user->update(['password' => Hash::make($data['password'])]);
        $broker->deleteToken($user);

        activity()->causedBy($user)->log('Şifre sıfırlandı.');

        return redirect()->route('login')->with('status', 'Şifreniz başarıyla sıfırlandı. Yeni şifrenizle giriş yapabilirsiniz.');
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
