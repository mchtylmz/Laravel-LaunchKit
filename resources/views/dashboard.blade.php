<x-layouts.app title="Dashboard">
    <section class="mb-6 rounded-lg border border-slate-200/80 bg-slate-950 p-6 text-white shadow-xl shadow-slate-950/10 dark:border-white/10">
        <div class="grid gap-6 lg:grid-cols-[1fr_auto] lg:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-emerald-300">Project foundation</p>
                <h2 class="mt-2 text-3xl font-black">Everything ready for your next Laravel build.</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">Auth, roles, settings, files and activity tracking are wired into a clean starter kit structure.</p>
            </div>
            <a class="btn-primary bg-white text-slate-950 hover:bg-emerald-100" href="{{ route('file-manager.index') }}">Manage files</a>
        </div>
    </section>

    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([['Users', $stats['users'], 'People with access'], ['Roles', $stats['roles'], 'Permission groups'], ['Files', $stats['files'], 'Managed uploads'], ['Activities', $stats['activities'], 'Tracked events']] as [$label, $value, $description])
            <div class="metric-card">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $label }}</p>
                        <p class="mt-3 text-4xl font-black">{{ $value }}</p>
                    </div>
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-indigo-50 text-xs font-bold text-indigo-700 dark:bg-indigo-400/10 dark:text-indigo-300">{{ substr($label, 0, 1) }}</span>
                </div>
                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ $description }}</p>
            </div>
        @endforeach
    </div>

    <section class="mt-6 grid gap-5 xl:grid-cols-3">
        @foreach ([['Activity trend', $charts['activities'], 'Tracked events over the last 7 days'], ['User growth', $charts['users'], 'New users over the last 7 days'], ['File uploads', $charts['files'], 'Uploaded files over the last 7 days']] as [$title, $chart, $description])
            @php $max = max(1, $chart->max('count')); @endphp
            <div class="card">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="section-title">{{ $description }}</p>
                        <h3 class="mt-1 text-lg font-black">{{ $title }}</h3>
                    </div>
                    <span class="rounded-lg bg-slate-100 px-2 py-1 text-xs font-bold text-slate-500 dark:bg-slate-950">{{ $chart->sum('count') }}</span>
                </div>
                <div class="mt-5 flex h-32 items-end gap-2">
                    @foreach ($chart as $point)
                        <div class="flex flex-1 flex-col items-center gap-2">
                            <div class="flex h-24 w-full items-end rounded-lg bg-slate-100 p-1 dark:bg-slate-950">
                                <div class="w-full rounded-md bg-indigo-600 dark:bg-emerald-400" style="height: {{ max(8, ($point['count'] / $max) * 100) }}%"></div>
                            </div>
                            <span class="text-[11px] font-semibold text-slate-400">{{ $point['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </section>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.1fr_.9fr]">
        <section class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="section-title">Audit trail</p>
                    <h2 class="mt-1 text-lg font-bold">Recent activity</h2>
                </div>
                <a class="text-sm font-semibold text-indigo-600 dark:text-indigo-300" href="{{ route('activity-logs.index') }}">View all</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($activities as $activity)
                    <div class="flex gap-3 rounded-lg border border-slate-100 bg-slate-50 p-3 text-sm dark:border-white/10 dark:bg-slate-950">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                        <div>
                            <p class="font-medium">{{ $activity->description }}</p>
                            <p class="text-xs text-slate-500">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No activity yet.</p>
                @endforelse
            </div>
        </section>
        <section class="card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="section-title">Storage</p>
                    <h2 class="mt-1 text-lg font-bold">Latest files</h2>
                </div>
                <a class="text-sm font-semibold text-indigo-600 dark:text-indigo-300" href="{{ route('file-manager.index') }}">Open manager</a>
            </div>
            <div class="mt-4 space-y-3">
                @forelse ($files as $file)
                    <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50 p-3 text-sm dark:border-white/10 dark:bg-slate-950">
                        <span class="font-medium">{{ $file->original_name }}</span>
                        <span class="text-slate-500">{{ $file->sizeForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No files uploaded.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-layouts.app>
