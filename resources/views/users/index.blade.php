<x-layouts.app title="Users">
    <section class="card">
        <h2 class="text-lg font-semibold">Create user</h2>
        <form method="POST" action="{{ route('users.store') }}" class="mt-4 grid gap-3 md:grid-cols-5">
            @csrf
            <input name="name" placeholder="Name" required>
            <input name="email" type="email" placeholder="Email" required>
            <input name="password" type="password" placeholder="Password" required>
            <select name="role">@foreach ($roles as $role)<option value="{{ $role->name }}">{{ $role->name }}</option>@endforeach</select>
            <button class="btn-primary" type="submit">Create</button>
        </form>
    </section>

    <section class="card mt-6 overflow-x-auto">
        <table class="w-full min-w-[720px] text-left text-sm">
            <thead class="text-slate-500"><tr><th class="py-2">Name</th><th>Email</th><th>Role</th><th>Created</th><th>Action</th></tr></thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($users as $user)
                    <tr>
                        <td class="py-3 font-medium">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->join(', ') ?: 'none' }}</td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>
                            <form method="POST" action="{{ route('users.role', $user) }}" class="flex gap-2">
                                @csrf
                                @method('PUT')
                                <select name="role">@foreach ($roles as $role)<option value="{{ $role->name }}" @selected($user->hasRole($role->name))>{{ $role->name }}</option>@endforeach</select>
                                <button class="btn-secondary" type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $users->links() }}</div>
    </section>
</x-layouts.app>
