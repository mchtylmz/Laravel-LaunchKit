<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'manage users',
            'manage roles',
            'view activity logs',
            'manage settings',
            'manage files',
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission]);
        }

        Role::query()->firstOrCreate(['name' => 'super-admin'])->syncPermissions($permissions);
        Role::query()->firstOrCreate(['name' => 'admin'])->syncPermissions([
            'view dashboard',
            'manage users',
            'view activity logs',
            'manage settings',
            'manage files',
        ]);
        Role::query()->firstOrCreate(['name' => 'user'])->syncPermissions([
            'view dashboard',
            'manage files',
        ]);
    }
}
