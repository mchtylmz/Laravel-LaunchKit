<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\SettingAudit;
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
            'audits' => SettingAudit::query()->with('user')->latest()->limit(8)->get(),
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
            $oldValue = Setting::query()->where('key', $key)->value('value');

            Setting::query()->updateOrCreate(['key' => $key], [
                'value' => (string) $value,
                'type' => is_numeric($value) ? 'integer' : 'string',
            ]);

            if ((string) $oldValue !== (string) $value) {
                SettingAudit::query()->create([
                    'user_id' => $request->user()->id,
                    'key' => $key,
                    'old_value' => $oldValue,
                    'new_value' => (string) $value,
                ]);
            }
        }

        activity()->causedBy($request->user())->log('Ayarlar güncellendi.');
        $request->user()->appNotifications()->create([
            'title' => 'Ayarlar güncellendi',
            'message' => 'Uygulama ayarlarında değişiklik yapıldı.',
            'type' => 'info',
            'url' => route('settings.edit'),
        ]);

        return back()->with('status', 'Ayarlar kaydedildi.');
    }

    private function authorizeManageSettings(): void
    {
        abort_unless(auth()->user()?->can('manage settings'), 403);
    }
}
