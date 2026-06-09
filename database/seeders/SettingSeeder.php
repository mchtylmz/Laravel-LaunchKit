<?php

namespace Database\Seeders;

use App\Models\Setting;
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
    }
}
