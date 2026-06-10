<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('manage roles'), 403);

        return view('roles.index', [
            'roles' => Role::query()->with('permissions')->orderBy('name')->get(),
            'permissions' => Permission::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('manage roles'), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:80', 'unique:roles,name'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::query()->create(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);
        activity()->causedBy($request->user())->performedOn($role)->log('Rol oluşturuldu.');
        $request->user()->appNotifications()->create([
            'title' => 'Rol oluşturuldu',
            'message' => "{$role->name} rolü oluşturuldu.",
            'type' => 'success',
            'url' => route('roles.index'),
        ]);

        return back()->with('status', 'Rol oluşturuldu.');
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        abort_unless(auth()->user()?->can('manage roles'), 403);

        $data = $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role->syncPermissions($data['permissions'] ?? []);
        activity()->causedBy($request->user())->performedOn($role)->log('Rol izinleri güncellendi.');
        $request->user()->appNotifications()->create([
            'title' => 'Rol izinleri güncellendi',
            'message' => "{$role->name} rolünün izinleri güncellendi.",
            'type' => 'info',
            'url' => route('roles.index'),
        ]);

        return back()->with('status', 'Rol izinleri güncellendi.');
    }
}
