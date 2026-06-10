<x-layouts.app title="Reset password">
    <div class="soft-grid grid min-h-screen place-items-center px-4 py-10 dark:bg-slate-950">
        <div class="w-full max-w-md card">
            <div class="mb-6 flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-lg bg-slate-950 text-sm font-bold text-white dark:bg-white dark:text-slate-950">LL</span>
                <div>
                    <h1 class="text-2xl font-black">Set new password</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Choose a strong new password for your account.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input class="w-full" name="email" type="email" placeholder="Email" value="{{ old('email') }}" required>
                <input class="w-full" name="password" type="password" placeholder="New password" required>
                <input class="w-full" name="password_confirmation" type="password" placeholder="Confirm password" required>
                <button class="btn-primary w-full" type="submit">Reset password</button>
            </form>
            <p class="mt-5 text-sm text-slate-500"><a class="font-semibold text-indigo-600" href="{{ route('login') }}">Back to login</a></p>
        </div>
    </div>
</x-layouts.app>
