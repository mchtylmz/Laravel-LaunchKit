<x-layouts.app title="Login">
    <div class="grid min-h-screen place-items-center bg-slate-50 px-4 dark:bg-slate-950">
        <div class="w-full max-w-md card">
            <h1 class="text-2xl font-bold">Login</h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Use a demo account or your own registered user.</p>
            <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-4">
                @csrf
                <input class="w-full" name="email" type="email" placeholder="Email" value="{{ old('email', 'admin@example.com') }}" required>
                <input class="w-full" name="password" type="password" placeholder="Password" required>
                <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                    <input class="rounded" name="remember" type="checkbox" value="1"> Remember me
                </label>
                <button class="btn-primary w-full" type="submit">Login</button>
            </form>
            <p class="mt-5 text-sm text-slate-500">No account? <a class="font-semibold text-indigo-600" href="{{ route('register') }}">Register</a></p>
        </div>
    </div>
</x-layouts.app>
