<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        $this->authorizeManageSettings();

        return view('settings.edit', [
            'settings' => Setting::query()->pluck('value', 'key'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorizeManageSettings();

        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'site_description' => ['nullable', 'string', 'max:255'],
            'default_theme' => ['required', 'in:light,dark,system'],
            'registration_enabled' => ['required', 'boolean'],
            'max_upload_size' => ['required', 'integer', 'min:1', 'max:10240'],
        ]);

        foreach ($data as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], [
                'value' => (string) $value,
                'type' => is_numeric($value) ? 'integer' : 'string',
            ]);
        }

        activity()->causedBy($request->user())->log('Ayarlar güncellendi.');

        return back()->with('status', 'Ayarlar kaydedildi.');
    }

    private function authorizeManageSettings(): void
    {
        abort_unless(auth()->user()?->can('manage settings'), 403);
    }
}
