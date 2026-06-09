<x-layouts.app title="Login">
    <div class="soft-grid grid min-h-screen place-items-center px-4 py-10 dark:bg-slate-950">
        <div class="w-full max-w-md card">
            <div class="mb-6 flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-lg bg-slate-950 text-sm font-bold text-white dark:bg-white dark:text-slate-950">LL</span>
                <div>
                    <h1 class="text-2xl font-black">Login</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Use a demo account or your own registered user.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-4">
                @csrf
                <input class="w-full" name="email" type="email" placeholder="Email" value="{{ old('email', 'admin@example.com') }}" required>
                <input class="w-full" name="password" type="password" placeholder="Password" required>
                <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                    <input class="rounded" name="remember" type="checkbox" value="1"> Remember me
                </label>
                <button class="btn-primary w-full" type="submit">Login</button>
            </form>
            <div class="mt-5 rounded-lg bg-slate-50 p-3 text-xs text-slate-500 dark:bg-slate-950">
                Demo: <span class="font-semibold text-slate-700 dark:text-slate-200">admin@example.com</span> / password
            </div>
            <p class="mt-5 text-sm text-slate-500">No account? <a class="font-semibold text-indigo-600" href="{{ route('register') }}">Register</a></p>
        </div>
    </div>
</x-layouts.app>
