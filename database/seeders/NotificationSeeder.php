<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->each(function (User $user): void {
            $user->appNotifications()->createMany([
                [
                    'title' => 'LaunchKit hazır',
                    'message' => 'Dashboard, roller ve dosya yöneticisi kullanıma hazır.',
                    'type' => 'success',
                    'url' => route('dashboard'),
                ],
                [
                    'title' => 'Güvenlik önerisi',
                    'message' => 'Profil sayfasından iki aşamalı doğrulamayı aktif edebilirsiniz.',
                    'type' => 'security',
                    'url' => route('profile.edit'),
                ],
            ]);
        });
    }
}
