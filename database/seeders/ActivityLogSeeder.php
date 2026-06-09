<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@example.com')->first();

        if (! $admin) {
            return;
        }

        activity()->causedBy($admin)->log('Laravel LaunchKit demo verileri oluşturuldu.');
        activity()->causedBy($admin)->log('Rol ve izin altyapısı hazırlandı.');
        activity()->causedBy($admin)->log('Dashboard ilk kullanıma hazır.');
    }
}
