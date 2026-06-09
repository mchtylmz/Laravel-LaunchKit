<x-layouts.app title="Roles">
    <section class="card">
        <h2 class="text-lg font-semibold">Create role</h2>
        <form method="POST" action="{{ route('roles.store') }}" class="mt-4 space-y-4">
            @csrf
            <input class="w-full max-w-md" name="name" placeholder="Role name" required>
            <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($permissions as $permission)
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="permissions[]" value="{{ $permission->name }}"> {{ $permission->name }}</label>
                @endforeach
            </div>
            <button class="btn-primary" type="submit">Create role</button>
        </form>
    </section>

    <section class="mt-6 grid gap-4 lg:grid-cols-2">
        @foreach ($roles as $role)
            <form method="POST" action="{{ route('roles.update', $role) }}" class="card">
                @csrf
                @method('PUT')
                <h3 class="font-semibold">{{ $role->name }}</h3>
                <div class="mt-4 grid gap-2">
                    @foreach ($permissions as $permission)
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="permissions[]" value="{{ $permission->name }}" @checked($role->hasPermissionTo($permission->name))> {{ $permission->name }}</label>
                    @endforeach
                </div>
                <button class="btn-secondary mt-4" type="submit">Update permissions</button>
            </form>
        @endforeach
    </section>
</x-layouts.app>
