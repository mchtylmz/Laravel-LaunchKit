<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
        $request->user()->appNotifications()->create([
            'title' => 'Kullanıcı oluşturuldu',
            'message' => "{$user->name} için yeni kullanıcı hesabı oluşturuldu.",
            'type' => 'success',
            'url' => route('users.index'),
        ]);

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
        $request->user()->appNotifications()->create([
            'title' => 'Rol güncellendi',
            'message' => "{$user->name} kullanıcısının rolü {$data['role']} olarak değiştirildi.",
            'type' => 'info',
            'url' => route('users.index'),
        ]);

        return back()->with('status', 'Kullanıcı rolü güncellendi.');
    }

    public function showImport(): View
    {
        abort_unless(auth()->user()?->can('manage users'), 403);

        return view('users.import', [
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function import(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('manage users'), 403);

        $data = $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
            'default_role' => ['required', 'exists:roles,name'],
        ]);

        $file = $request->file('csv');
        $stream = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($stream);

        $expected = ['name', 'email', 'password'];
        $headerMap = array_map('strtolower', $header ?? []);

        if (array_intersect($expected, $headerMap) !== $expected) {
            fclose($stream);

            return back()->withErrors(['csv' => 'CSV dosyası "name, email, password" sütunlarını içermelidir.']);
        }

        $imported = 0;
        $errors = [];

        while (($row = fgetcsv($stream)) !== false) {
            $record = array_combine($headerMap, $row);

            $validator = Validator::make($record, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'string', 'min:3'],
            ]);

            if ($validator->fails()) {
                $errors[] = 'Satır '.($imported + 2).': '.implode(' ', $validator->errors()->all());

                continue;
            }

            $user = User::query()->create([
                'name' => $record['name'],
                'email' => $record['email'],
                'password' => Hash::make($record['password']),
            ]);
            $user->assignRole($data['default_role']);
            $imported++;
        }

        fclose($stream);

        activity()->causedBy($request->user())->log("{$imported} kullanıcı CSV'den içe aktarıldı.");

        $message = "{$imported} kullanıcı içe aktarıldı.";

        if (count($errors) > 0) {
            $message .= ' '.count($errors).' satır atlandı: '.implode('; ', array_slice($errors, 0, 5));
        }

        return redirect()->route('users.index')->with('status', $message);
    }
}
