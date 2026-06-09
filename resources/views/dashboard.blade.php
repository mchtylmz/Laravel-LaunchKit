<x-layouts.app title="Dashboard">
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([['Users', $stats['users']], ['Roles', $stats['roles']], ['Files', $stats['files']], ['Activities', $stats['activities']]] as [$label, $value])
            <div class="card">
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $label }}</p>
                <p class="mt-3 text-3xl font-bold">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <section class="card">
            <h2 class="text-lg font-semibold">Recent activity</h2>
            <div class="mt-4 space-y-3">
                @forelse ($activities as $activity)
                    <div class="rounded-lg bg-slate-50 p-3 text-sm dark:bg-slate-950">
                        <p class="font-medium">{{ $activity->description }}</p>
                        <p class="text-xs text-slate-500">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No activity yet.</p>
                @endforelse
            </div>
        </section>
        <section class="card">
            <h2 class="text-lg font-semibold">Latest files</h2>
            <div class="mt-4 space-y-3">
                @forelse ($files as $file)
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 p-3 text-sm dark:bg-slate-950">
                        <span>{{ $file->original_name }}</span>
                        <span class="text-slate-500">{{ $file->sizeForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No files uploaded.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-layouts.app>
