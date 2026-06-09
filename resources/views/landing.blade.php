<x-layouts.app title="Laravel LaunchKit">
    <section class="min-h-screen bg-white dark:bg-slate-950">
        <div class="mx-auto grid min-h-screen max-w-7xl items-center gap-12 px-6 py-12 lg:grid-cols-[1.05fr_.95fr]">
            <div>
                <h1 class="max-w-3xl text-5xl font-bold tracking-normal text-slate-950 dark:text-white sm:text-6xl">Laravel LaunchKit</h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600 dark:text-slate-300">
                    A clean open source Laravel starter kit with authentication, profiles, roles, settings, activity logs, file management and a polished dashboard.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a class="btn-primary" href="{{ route('register') }}">Start project</a>
                    <a class="btn-secondary" href="{{ route('login') }}">Login demo</a>
                </div>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach (['Auth system', 'User profiles', 'Role permissions', 'Dark mode', 'Activity logs', 'File manager'] as $feature)
                        <div class="rounded-lg bg-white p-4 text-sm font-semibold text-slate-700 shadow-sm dark:bg-slate-950 dark:text-slate-200">{{ $feature }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
