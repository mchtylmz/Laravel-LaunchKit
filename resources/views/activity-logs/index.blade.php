<x-layouts.app title="Activity logs">
    <section class="card">
        <div class="space-y-3">
            @foreach ($activities as $activity)
                <div class="rounded-lg bg-slate-50 p-4 text-sm dark:bg-slate-950">
                    <p class="font-medium">{{ $activity->description }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ $activity->causer?->name ?? 'System' }} · {{ $activity->created_at->format('Y-m-d H:i') }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $activities->links() }}</div>
    </section>
</x-layouts.app>
