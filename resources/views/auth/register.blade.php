<x-layouts.app title="Register">
    <div class="soft-grid grid min-h-screen place-items-center px-4 py-10 dark:bg-slate-950">
        <div class="w-full max-w-md card">
            <div class="mb-6 flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-lg bg-slate-950 text-sm font-bold text-white dark:bg-white dark:text-slate-950">LL</span>
                <div>
                    <h1 class="text-2xl font-black">Register</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Create a clean starter workspace.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('register.store') }}" class="mt-6 space-y-4">
                @csrf
                <input class="w-full" name="name" placeholder="Name" value="{{ old('name') }}" required>
                <input class="w-full" name="email" type="email" placeholder="Email" value="{{ old('email') }}" required>
                <input class="w-full" name="password" type="password" placeholder="Password" required>
                <input class="w-full" name="password_confirmation" type="password" placeholder="Confirm password" required>
                <button class="btn-primary w-full" type="submit">Create account</button>
            </form>
            <p class="mt-5 text-sm text-slate-500">Already registered? <a class="font-semibold text-indigo-600" href="{{ route('login') }}">Login</a></p>
        </div>
    </div>
</x-layouts.app>
