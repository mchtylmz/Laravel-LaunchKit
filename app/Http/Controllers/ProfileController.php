<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Spatie\Activitylog\Models\Activity;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $activities = Activity::query()
            ->where(function ($q) {
                $q->where('causer_type', auth()->user()?->getMorphClass())
                  ->where('causer_id', auth()->id());
            })
            ->orWhere(function ($q) {
                $q->where('subject_type', auth()->user()?->getMorphClass())
                  ->where('subject_id', auth()->id());
            })
            ->latest()
            ->take(20)
            ->get();

        return view('profile.edit', ['activities' => $activities]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        unset($data['avatar']);
        $user->update($data);
        activity()->causedBy($user)->performedOn($user)->log('Profil güncellendi.');
        $user->appNotifications()->create([
            'title' => 'Profil güncellendi',
            'message' => 'Hesap profil bilgileriniz başarıyla kaydedildi.',
            'type' => 'success',
            'url' => route('profile.edit'),
        ]);

        return back()->with('status', 'Profil bilgileri güncellendi.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($data['password']),
        ]);

        activity()->causedBy($request->user())->log('Şifre değiştirildi.');
        $request->user()->appNotifications()->create([
            'title' => 'Şifre değiştirildi',
            'message' => 'Hesap şifreniz başarıyla güncellendi.',
            'type' => 'security',
            'url' => route('profile.edit'),
        ]);

        return back()->with('status', 'Şifre güncellendi.');
    }

    public function enableTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        $request->user()->update(['two_factor_enabled' => true]);
        activity()->causedBy($request->user())->log('İki aşamalı doğrulama açıldı.');
        $request->user()->appNotifications()->create([
            'title' => '2FA açıldı',
            'message' => 'İki aşamalı doğrulama hesabınız için aktif edildi.',
            'type' => 'security',
            'url' => route('profile.edit'),
        ]);

        return back()->with('status', 'İki aşamalı doğrulama açıldı.');
    }

    public function disableTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        $request->user()->forceFill([
            'two_factor_enabled' => false,
            'two_factor_code_hash' => null,
            'two_factor_expires_at' => null,
        ])->save();

        activity()->causedBy($request->user())->log('İki aşamalı doğrulama kapatıldı.');
        $request->user()->appNotifications()->create([
            'title' => '2FA kapatıldı',
            'message' => 'İki aşamalı doğrulama hesabınız için devre dışı bırakıldı.',
            'type' => 'security',
            'url' => route('profile.edit'),
        ]);

        return back()->with('status', 'İki aşamalı doğrulama kapatıldı.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        activity()->causedBy($user)->log('Hesap silindi.');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->appNotifications()->delete();
        $user->delete();

        return redirect()->route('landing')->with('status', 'Hesabınız başarıyla silindi.');
    }
}
