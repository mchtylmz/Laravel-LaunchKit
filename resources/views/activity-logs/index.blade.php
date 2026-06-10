<x-layouts.app title="Activity logs">
    <section class="card">
        <p class="section-title">Audit trail</p>
        <h2 class="mt-1 text-2xl font-black">Activity logs</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Track important starter kit actions and user operations.</p>
        <div class="mt-4 flex justify-end">
            <a class="btn-secondary" href="{{ route('export.activity-logs') }}">CSV Export</a>
        </div>
        <form method="GET" action="{{ route('activity-logs.index') }}" class="mt-5 grid gap-3 lg:grid-cols-5">
            <input name="q" placeholder="Search activity" value="{{ $filters['q'] ?? '' }}">
            <select name="causer_id">
                <option value="">All users</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(($filters['causer_id'] ?? '') == $user->id)>{{ $user->name }}</option>
                @endforeach
            </select>
            <select name="log_name">
                <option value="">All types</option>
                @foreach ($logNames as $logName)
                    <option value="{{ $logName }}" @selected(($filters['log_name'] ?? '') === $logName)>{{ $logName }}</option>
                @endforeach
            </select>
            <input name="from" type="date" value="{{ $filters['from'] ?? '' }}">
            <input name="to" type="date" value="{{ $filters['to'] ?? '' }}">
            <div class="flex gap-2 lg:col-span-5">
                <button class="btn-primary" type="submit">Apply filters</button>
                <a class="btn-secondary" href="{{ route('activity-logs.index') }}">Reset</a>
            </div>
        </form>
        <div class="space-y-3">
            @forelse ($activities as $activity)
                <div class="mt-4 flex gap-3 rounded-lg border border-slate-100 bg-slate-50 p-4 text-sm dark:border-white/10 dark:bg-slate-950">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    <div>
                        <p class="font-medium">{{ $activity->description }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $activity->causer?->name ?? 'System' }} · {{ $activity->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            @empty
                <p class="mt-4 rounded-lg bg-slate-50 p-4 text-sm text-slate-500 dark:bg-slate-950">No activity matched your filters.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $activities->links() }}</div>
    </section>
</x-layouts.app>
