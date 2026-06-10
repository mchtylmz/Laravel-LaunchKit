<x-layouts.app title="Forgot password">
    <div class="soft-grid grid min-h-screen place-items-center px-4 py-10 dark:bg-slate-950">
        <div class="w-full max-w-md card">
            <div class="mb-6 flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-lg bg-slate-950 text-sm font-bold text-white dark:bg-white dark:text-slate-950">LL</span>
                <div>
                    <h1 class="text-2xl font-black">Reset password</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Enter your email to receive a reset link.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <input class="w-full" name="email" type="email" placeholder="Email" value="{{ old('email') }}" required>
                <button class="btn-primary w-full" type="submit">Send reset link</button>
            </form>
            <p class="mt-5 text-sm text-slate-500"><a class="font-semibold text-indigo-600" href="{{ route('login') }}">Back to login</a></p>
        </div>
    </div>
</x-layouts.app>
