<x-layouts.app title="Search">
    <div class="mb-6">
        <p class="section-title">Global search</p>
        <h2 class="mt-1 text-2xl font-black">Search results</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            Results for "<strong class="text-slate-900 dark:text-white">{{ $q }}</strong>"
        </p>
    </div>

    <form method="GET" action="{{ route('search') }}" class="mb-8">
        <div class="flex gap-3">
            <input class="flex-1" name="q" value="{{ $q }}" placeholder="Search users, files, activity logs..." autofocus>
            <button class="btn-primary" type="submit">Search</button>
        </div>
    </form>

    <div class="grid gap-8 lg:grid-cols-3">
        <section>
            <h3 class="section-title mb-3">Users ({{ $users->count() }})</h3>
            <div class="space-y-3">
                @forelse ($users as $user)
                    <div class="card flex items-center gap-3">
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-slate-100 text-xs font-bold dark:bg-slate-950">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold truncate">{{ $user->name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No users found.</p>
                @endforelse
            </div>
        </section>

        <section>
            <h3 class="section-title mb-3">Files ({{ $files->count() }})</h3>
            <div class="space-y-3">
                @forelse ($files as $file)
                    <div class="card">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold truncate">{{ $file->original_name }}</p>
                                <p class="text-xs text-slate-500">{{ $file->sizeForHumans() }}</p>
                            </div>
                            <a class="btn-secondary shrink-0 text-xs" href="{{ route('file-manager.show', $file) }}">View</a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No files found.</p>
                @endforelse
            </div>
        </section>

        <section>
            <h3 class="section-title mb-3">Activity logs ({{ $activities->count() }})</h3>
            <div class="space-y-3">
                @forelse ($activities as $activity)
                    <div class="card">
                        <p class="text-sm font-semibold">{{ $activity->description }}</p>
                        <p class="mt-1 text-xs text-slate-500">
                            {{ $activity->causer?->name ?? 'System' }} ·
                            {{ $activity->created_at->diffForHumans() }}
                        </p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No activity logs found.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-layouts.app>
