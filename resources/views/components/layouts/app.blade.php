<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Laravel LaunchKit' }}</title>
    <script>
        const theme = localStorage.getItem('theme') || 'system';
        const dark = theme === 'dark' || (theme === 'system' && matchMedia('(prefers-color-scheme: dark)').matches);
        document.documentElement.classList.toggle('dark', dark);
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: false }" x-init="$store.theme.init()">
    <div class="min-h-screen lg:flex">
        @auth
            <div x-show="sidebarOpen" class="fixed inset-0 z-30 bg-slate-950/40 lg:hidden" x-on:click="sidebarOpen = false"></div>
            <aside class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-slate-200 bg-white p-4 transition dark:border-slate-800 dark:bg-slate-900 lg:static lg:translate-x-0" :class="{ 'translate-x-0': sidebarOpen }">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-2 py-3">
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-indigo-600 font-bold text-white">LL</span>
                    <span>
                        <span class="block font-bold">Laravel LaunchKit</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">Open source starter kit</span>
                    </span>
                </a>

                <nav class="mt-6 space-y-1">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="nav-link {{ request()->routeIs('profile.*') ? 'nav-link-active' : '' }}" href="{{ route('profile.edit') }}">Profile</a>
                    @can('manage settings')
                        <a class="nav-link {{ request()->routeIs('settings.*') ? 'nav-link-active' : '' }}" href="{{ route('settings.edit') }}">Settings</a>
                    @endcan
                    @can('manage users')
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'nav-link-active' : '' }}" href="{{ route('users.index') }}">Users</a>
                    @endcan
                    @can('manage roles')
                        <a class="nav-link {{ request()->routeIs('roles.*') ? 'nav-link-active' : '' }}" href="{{ route('roles.index') }}">Roles</a>
                    @endcan
                    @can('view activity logs')
                        <a class="nav-link {{ request()->routeIs('activity-logs.*') ? 'nav-link-active' : '' }}" href="{{ route('activity-logs.index') }}">Activity logs</a>
                    @endcan
                    @can('manage files')
                        <a class="nav-link {{ request()->routeIs('file-manager.*') ? 'nav-link-active' : '' }}" href="{{ route('file-manager.index') }}">File manager</a>
                    @endcan
                </nav>
            </aside>
        @endauth

        <div class="min-w-0 flex-1">
            @auth
                <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/90 backdrop-blur dark:border-slate-800 dark:bg-slate-950/80">
                    <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            <button class="btn-secondary px-3 lg:hidden" x-on:click="sidebarOpen = true">Menu</button>
                            <h1 class="text-base font-semibold">{{ $title ?? 'Dashboard' }}</h1>
                        </div>
                        <div class="flex items-center gap-3">
                            <div x-data="{ open: false }" class="relative">
                                <button class="btn-secondary px-3" x-on:click="open = ! open">Notifications</button>
                                <div x-show="open" x-on:click.outside="open = false" class="absolute right-0 mt-2 w-80 rounded-lg border border-slate-200 bg-white p-3 shadow-xl dark:border-slate-800 dark:bg-slate-900">
                                    <p class="text-sm font-semibold">Recent updates</p>
                                    <div class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                        <p>Starter kit hazırlandı.</p>
                                        <p>Demo kullanıcılar seed ile eklenir.</p>
                                        <p>Activity log son işlemleri takip eder.</p>
                                    </div>
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
