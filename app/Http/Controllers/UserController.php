<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('manage users'), 403);

        return view('users.index', [
            'users' => User::query()->with('roles')->latest()->paginate(10),
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('manage users'), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->assignRole($data['role']);
        activity()->causedBy($request->user())->performedOn($user)->log('Kullanıcı oluşturuldu.');

        return back()->with('status', 'Kullanıcı oluşturuldu.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        abort_unless(auth()->user()?->can('manage users'), 403);

        $data = $request->validate([
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user->syncRoles([$data['role']]);
        activity()->causedBy($request->user())->performedOn($user)->log('Kullanıcı rolü güncellendi.');

        return back()->with('status', 'Kullanıcı rolü güncellendi.');
    }
}
