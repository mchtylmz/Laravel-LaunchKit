<x-layouts.app title="Register">
    <div class="grid min-h-screen place-items-center bg-slate-50 px-4 dark:bg-slate-950">
        <div class="w-full max-w-md card">
            <h1 class="text-2xl font-bold">Register</h1>
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
