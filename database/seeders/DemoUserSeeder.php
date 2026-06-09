<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@example.com', 'role' => 'super-admin'],
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'admin'],
            ['name' => 'Demo User', 'email' => 'user@example.com', 'role' => 'user'],
        ];

        foreach ($users as $demo) {
            $user = User::query()->updateOrCreate(
                ['email' => $demo['email']],
                ['name' => $demo['name'], 'password' => Hash::make('password')]
            );

            $user->syncRoles([$demo['role']]);
        }
    }
}
