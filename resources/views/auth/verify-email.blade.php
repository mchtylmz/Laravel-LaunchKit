<x-layouts.app title="Verify Email">
    <div class="card mx-auto mt-12 max-w-lg text-center">
        <div class="mx-auto mb-5 grid h-16 w-16 place-items-center rounded-full bg-indigo-100 dark:bg-indigo-900">
            <svg class="h-8 w-8 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h2 class="text-xl font-bold">Verify your email</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">We sent a verification link to <strong>{{ auth()->user()->email }}</strong>. Click the link in the email to activate your account.</p>

        <form method="POST" action="{{ route('verification.resend') }}" class="mt-6">
            @csrf
            <button class="btn-primary" type="submit">Resend verification email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button class="text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300" type="submit">Logout</button>
        </form>
    </div>
</x-layouts.app>
