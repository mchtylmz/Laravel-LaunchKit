<x-layouts.app title="Two-factor verification">
    <div class="soft-grid grid min-h-screen place-items-center px-4 py-10 dark:bg-slate-950">
        <div class="w-full max-w-md card">
            <div class="mb-6 flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-lg bg-slate-950 text-sm font-bold text-white dark:bg-white dark:text-slate-950">2FA</span>
                <div>
                    <h1 class="text-2xl font-black">Two-factor verification</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Enter the 6-digit code sent to your email.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-4">
                @csrf
                <input class="w-full text-center text-xl font-black tracking-[0.4em]" name="code" inputmode="numeric" maxlength="6" placeholder="000000" required>
                <button class="btn-primary w-full" type="submit">Verify</button>
            </form>
            <form method="POST" action="{{ route('two-factor.resend') }}" class="mt-4">
                @csrf
                <button class="btn-secondary w-full" type="submit">Send a new code</button>
            </form>
        </div>
    </div>
</x-layouts.app>
