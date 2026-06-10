<x-layouts.app title="Users">
    <section class="card">
        <p class="section-title">Team access</p>
        <h2 class="mt-1 text-2xl font-black">Create user</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Invite demo users and assign their first role immediately.</p>
        <form method="POST" action="{{ route('users.store') }}" class="mt-4 grid gap-3 md:grid-cols-5">
            @csrf
            <input name="name" placeholder="Name" required>
            <input name="email" type="email" placeholder="Email" required>
            <input name="password" type="password" placeholder="Password" required>
            <select name="role">@foreach ($roles as $role)<option value="{{ $role->name }}">{{ $role->name }}</option>@endforeach</select>
            <button class="btn-primary" type="submit">Create</button>
        </form>
    </section>

    <section class="mt-6 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <form id="bulk-delete" method="POST" action="{{ route('users.bulk-delete') }}" class="inline">
                @csrf
                <input type="hidden" name="user_ids" id="bulk-delete-ids">
                <button type="submit" class="btn-secondary text-red-600 dark:text-red-400" onclick="return handleBulk('delete')" disabled id="bulk-delete-btn">Delete selected</button>
            </form>
            <form id="bulk-role" method="POST" action="{{ route('users.bulk-role') }}" class="inline-flex items-center gap-2">
                @csrf
                <input type="hidden" name="user_ids" id="bulk-role-ids">
                <select name="role" class="w-32">
                    @foreach ($roles as $role)<option value="{{ $role->name }}">{{ $role->name }}</option>@endforeach
                </select>
                <button type="submit" class="btn-secondary" onclick="return handleBulk('role')" disabled id="bulk-role-btn">Set role</button>
            </form>
        </div>
        <div class="flex gap-3">
            <a class="btn-secondary" href="{{ route('users.import') }}">CSV Import</a>
            <a class="btn-secondary" href="{{ route('export.users') }}">CSV Export</a>
        </div>
    </section>

    <section class="card mt-6 overflow-x-auto">
        <table class="w-full min-w-[720px] text-left text-sm">
            <thead class="text-xs uppercase tracking-wide text-slate-400">
                <tr>
                    <th class="py-2 w-10"><input type="checkbox" id="select-all" onchange="toggleAll(this)"></th>
                    <th class="py-2">Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($users as $user)
                    <tr class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                        <td class="py-3"><input type="checkbox" class="user-checkbox" value="{{ $user->id }}" onchange="updateBulkButtons()"></td>
                        <td class="py-3 font-medium">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="rounded-lg bg-indigo-50 px-2 py-1 text-xs font-bold text-indigo-700 dark:bg-indigo-400/10 dark:text-indigo-300">{{ $user->roles->pluck('name')->join(', ') ?: 'none' }}</span></td>
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

@push('scripts')
<script>
function toggleAll(source) {
    document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = source.checked)
    updateBulkButtons()
}
function updateBulkButtons() {
    const checked = document.querySelectorAll('.user-checkbox:checked')
    const ids = Array.from(checked).map(cb => cb.value)
    document.getElementById('bulk-delete-btn').disabled = ids.length === 0
    document.getElementById('bulk-role-btn').disabled = ids.length === 0
    document.getElementById('bulk-delete-ids').value = JSON.stringify(ids)
    document.getElementById('bulk-role-ids').value = JSON.stringify(ids)
}
function handleBulk(action) {
    const checked = document.querySelectorAll('.user-checkbox:checked')
    if (checked.length === 0) return false
    if (action === 'delete' && !confirm(checked.length + ' kullanıcıyı silmek istediğinize emin misiniz?')) return false
    return true
}
</script>
@endpush
