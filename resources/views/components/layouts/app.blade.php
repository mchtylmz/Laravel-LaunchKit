<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' · ' : '' }}{{ $appSettings['site_name'] ?? 'Laravel LaunchKit' }}</title>
    <script>
        const theme = localStorage.getItem('theme') || 'system';
        const dark = theme === 'dark' || (theme === 'system' && matchMedia('(prefers-color-scheme: dark)').matches);
        document.documentElement.classList.toggle('dark', dark);
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: false }" x-init="$store.theme.init()">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,rgba(79,70,229,0.12),transparent_32rem),linear-gradient(180deg,#f8fafc,white)] dark:bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,0.12),transparent_32rem),linear-gradient(180deg,#08111f,#020617)] lg:flex">
        @auth
            <div x-show="sidebarOpen" class="fixed inset-0 z-30 bg-slate-950/40 lg:hidden" x-on:click="sidebarOpen = false"></div>
            <aside class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-slate-200/70 bg-white/90 p-4 shadow-2xl shadow-slate-950/10 backdrop-blur-xl transition dark:border-white/10 dark:bg-slate-950/80 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 lg:shadow-none" :class="{ 'translate-x-0': sidebarOpen }">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-lg border border-slate-200/80 bg-white p-3 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <span class="grid h-11 w-11 place-items-center rounded-lg bg-slate-950 text-sm font-bold text-white dark:bg-white dark:text-slate-950">LL</span>
                    <span>
                        <span class="block font-bold">{{ $appSettings['site_name'] ?? 'Laravel LaunchKit' }}</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ $appSettings['site_description'] ?? 'Starter kit control room' }}</span>
                    </span>
                </a>

                <div class="mt-6 px-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Workspace</div>
                <nav class="mt-3 space-y-1">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}" href="{{ route('dashboard') }}"><span>Dashboard</span><span class="text-xs opacity-60">01</span></a>
                    <a class="nav-link {{ request()->routeIs('notifications.*') ? 'nav-link-active' : '' }}" href="{{ route('notifications.index') }}"><span>Notifications</span><span class="text-xs opacity-60">08</span></a>
                    <a class="nav-link {{ request()->routeIs('profile.*') ? 'nav-link-active' : '' }}" href="{{ route('profile.edit') }}"><span>Profile</span><span class="text-xs opacity-60">02</span></a>
                    @can('manage settings')
                        <a class="nav-link {{ request()->routeIs('settings.*') ? 'nav-link-active' : '' }}" href="{{ route('settings.edit') }}"><span>Settings</span><span class="text-xs opacity-60">03</span></a>
                    @endcan
                    @can('manage users')
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'nav-link-active' : '' }}" href="{{ route('users.index') }}"><span>Users</span><span class="text-xs opacity-60">04</span></a>
                    @endcan
                    @can('manage roles')
                        <a class="nav-link {{ request()->routeIs('roles.*') ? 'nav-link-active' : '' }}" href="{{ route('roles.index') }}"><span>Roles</span><span class="text-xs opacity-60">05</span></a>
                    @endcan
                    @can('view activity logs')
                        <a class="nav-link {{ request()->routeIs('activity-logs.*') ? 'nav-link-active' : '' }}" href="{{ route('activity-logs.index') }}"><span>Activity logs</span><span class="text-xs opacity-60">06</span></a>
                    @endcan
                    @can('manage files')
                        <a class="nav-link {{ request()->routeIs('file-manager.*') ? 'nav-link-active' : '' }}" href="{{ route('file-manager.index') }}"><span>File manager</span><span class="text-xs opacity-60">07</span></a>
                    @endcan
                </nav>

                <div class="mt-8 rounded-lg border border-indigo-200/80 bg-indigo-50 p-4 text-sm text-indigo-950 dark:border-indigo-400/20 dark:bg-indigo-400/10 dark:text-indigo-100">
                    <p class="font-semibold">Launch-ready base</p>
                    <p class="mt-1 text-xs leading-5 opacity-80">Auth, roles, settings and file tools are ready for the next product idea.</p>
                </div>
            </aside>
        @endauth

        <div class="min-w-0 flex-1">
            @auth
                @php
                    $headerNotifications = auth()->user()->appNotifications()->latest()->limit(5)->get();
                    $unreadNotifications = auth()->user()->appNotifications()->unread()->count();
                @endphp
                <header class="sticky top-0 z-20 border-b border-slate-200/70 bg-white/75 backdrop-blur-xl dark:border-white/10 dark:bg-slate-950/60">
                    <div class="flex min-h-16 flex-col gap-3 px-4 py-3 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                        <div class="flex items-center gap-3">
                            <button class="btn-secondary px-3 lg:hidden" x-on:click="sidebarOpen = true">Menu</button>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $appSettings['site_name'] ?? 'Laravel LaunchKit' }}</p>
                                <h1 class="text-lg font-bold">{{ $title ?? 'Dashboard' }}</h1>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <div x-data="{ open: false }" class="relative">
                                <button class="btn-secondary relative px-3" x-on:click="open = ! open">
                                    Notifications
                                    @if ($unreadNotifications > 0)
                                        <span class="ml-2 rounded-full bg-emerald-500 px-2 py-0.5 text-xs text-white">{{ $unreadNotifications }}</span>
                                    @endif
                                </button>
                                <div x-show="open" x-on:click.outside="open = false" class="absolute right-0 mt-2 w-96 rounded-lg border border-slate-200 bg-white p-3 shadow-xl shadow-slate-950/10 dark:border-slate-800 dark:bg-slate-900">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-semibold">Notifications</p>
                                        @if ($unreadNotifications > 0)
                                            <form method="POST" action="{{ route('notifications.read-all') }}">
                                                @csrf
                                                <button class="text-xs font-semibold text-indigo-600 dark:text-indigo-300" type="submit">Mark all as read</button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                        @forelse ($headerNotifications as $notification)
                                            <form method="POST" action="{{ route('notifications.read', $notification) }}" class="rounded-lg border border-slate-100 bg-slate-50 p-3 text-left dark:border-white/10 dark:bg-slate-950">
                                                @csrf
                                                <button class="block w-full text-left" type="submit">
                                                    <span class="flex items-start justify-between gap-3">
                                                        <span>
                                                            <span class="font-semibold text-slate-900 dark:text-white">{{ $notification->title }}</span>
                                                            <span class="mt-1 block text-xs leading-5 text-slate-500">{{ $notification->message }}</span>
                                                        </span>
                                                        @if (! $notification->read_at)
                                                            <span class="mt-1 h-2 w-2 rounded-full bg-emerald-500"></span>
                                                        @endif
                                                    </span>
                                                </button>
                                            </form>
                                        @empty
                                            <p class="rounded-lg bg-slate-50 p-3 text-sm dark:bg-slate-950">No notifications yet.</p>
                                        @endforelse
                                    </div>
                                    <a class="mt-3 block text-right text-xs font-semibold text-indigo-600 dark:text-indigo-300" href="{{ route('notifications.index') }}">Open inbox</a>
                                </div>
                            </div>
                            <select class="w-28" x-bind:value="$store.theme.value" x-on:change="$store.theme.set($event.target.value)">
                                <option value="system">System</option>
                                <option value="light">Light</option>
                                <option value="dark">Dark</option>
                            </select>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn-secondary" type="submit">Logout</button>
                            </form>
                        </div>
                    </div>
                </header>
            @endauth

            <main class="@auth p-4 sm:p-6 lg:p-8 @endauth">
                @if (session('status'))
                    <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-200">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
