<x-layouts.app title="Activity logs">
    <section class="card">
        <p class="section-title">Audit trail</p>
        <h2 class="mt-1 text-2xl font-black">Activity logs</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Track important starter kit actions and user operations.</p>
        <div class="space-y-3">
            @foreach ($activities as $activity)
                <div class="mt-4 flex gap-3 rounded-lg border border-slate-100 bg-slate-50 p-4 text-sm dark:border-white/10 dark:bg-slate-950">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    <div>
                        <p class="font-medium">{{ $activity->description }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $activity->causer?->name ?? 'System' }} · {{ $activity->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $activities->links() }}</div>
    </section>
</x-layouts.app>
