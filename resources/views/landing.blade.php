<x-layouts.app title="Laravel LaunchKit">
    <section class="soft-grid min-h-screen overflow-hidden bg-white dark:bg-slate-950">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-6">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-lg bg-slate-950 text-sm font-bold text-white dark:bg-white dark:text-slate-950">LL</span>
                <span class="font-bold">Laravel LaunchKit</span>
            </a>
            <div class="flex gap-3">
                <a class="btn-secondary" href="{{ route('login') }}">Login</a>
                <a class="btn-primary" href="{{ route('register') }}">Start</a>
            </div>
        </div>

        <div class="mx-auto grid min-h-[calc(100vh-88px)] max-w-7xl items-center gap-12 px-6 py-12 lg:grid-cols-[1fr_.95fr]">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-black tracking-normal text-slate-950 dark:text-white sm:text-7xl">Laravel LaunchKit</h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600 dark:text-slate-300">
                    A clean open source Laravel starter kit with authentication, profiles, roles, settings, activity logs, file management and a polished dashboard.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a class="btn-primary" href="{{ route('register') }}">Start project</a>
                    <a class="btn-secondary" href="{{ route('login') }}">Login demo</a>
                </div>
                <div class="mt-10 grid max-w-xl grid-cols-3 gap-3">
                    @foreach ([['13', 'Laravel'], ['8.4+', 'PHP'], ['2', 'README']] as [$value, $label])
                        <div class="rounded-lg border border-slate-200/80 bg-white/80 p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                            <p class="text-2xl font-black">{{ $value }}</p>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="relative">
                <div class="absolute -inset-6 rounded-[2rem] bg-indigo-500/10 blur-3xl dark:bg-emerald-400/10"></div>
                <div class="relative rounded-lg border border-slate-200 bg-white p-4 shadow-2xl shadow-slate-950/10 dark:border-white/10 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 dark:border-white/10">
                        <div>
                            <p class="section-title">Starter dashboard</p>
                            <h2 class="mt-1 text-xl font-bold">Launch control</h2>
                        </div>
                        <span class="rounded-lg bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300">Ready</span>
                    </div>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach (['Auth system', 'User profiles', 'Role permissions', 'Dark mode', 'Activity logs', 'File manager'] as $feature)
                            <div class="rounded-lg border border-slate-100 bg-slate-50 p-4 text-sm font-semibold text-slate-700 dark:border-white/10 dark:bg-slate-950 dark:text-slate-200">{{ $feature }}</div>
                        @endforeach
                    </div>
                    <div class="mt-4 rounded-lg bg-slate-950 p-4 text-white dark:bg-white dark:text-slate-950">
                        <p class="text-sm font-semibold">Demo users seeded</p>
                        <p class="mt-1 text-xs opacity-70">superadmin@example.com / password</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
