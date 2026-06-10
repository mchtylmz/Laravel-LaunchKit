<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\SettingAudit;
use App\Models\User;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => ['Laravel LaunchKit', 'string'],
            'site_description' => ['Open source Laravel starter kit for fast project launches.', 'string'],
            'default_theme' => ['system', 'string'],
            'registration_enabled' => ['1', 'boolean'],
            'max_upload_size' => ['10240', 'integer'],
        ];

        foreach ($settings as $key => [$value, $type]) {
            Setting::query()->updateOrCreate(['key' => $key], compact('value', 'type'));
        }

        $admin = User::query()->where('email', 'admin@example.com')->first();

        if ($admin) {
            SettingAudit::query()->firstOrCreate([
                'user_id' => $admin->id,
                'key' => 'site_name',
                'old_value' => null,
                'new_value' => 'Laravel LaunchKit',
            ]);
        }
    }
}
