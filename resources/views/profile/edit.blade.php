<x-layouts.app title="Profile">
    <div class="mb-6">
        <p class="section-title">Account center</p>
        <h2 class="mt-1 text-2xl font-black">Profile information</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Keep account details, avatar and password up to date.</p>
    </div>
    <div class="grid gap-6 xl:grid-cols-2">
        <section class="card">
            <h3 class="text-lg font-bold">Profile information</h3>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                @csrf
                @method('PUT')
                <input class="w-full" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                <input class="w-full" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required>
                <input class="w-full" name="avatar" type="file" accept="image/*">
                @if (auth()->user()->avatar_path)
                    <img class="h-20 w-20 rounded-full object-cover" src="{{ asset('storage/'.auth()->user()->avatar_path) }}" alt="Avatar">
                @endif
                <button class="btn-primary" type="submit">Save profile</button>
            </form>
        </section>
        <section class="card">
            <h3 class="text-lg font-bold">Change password</h3>
            <form method="POST" action="{{ route('profile.password') }}" class="mt-5 space-y-4">
                @csrf
                @method('PUT')
                <input class="w-full" name="current_password" type="password" placeholder="Current password" required>
                <input class="w-full" name="password" type="password" placeholder="New password" required>
                <input class="w-full" name="password_confirmation" type="password" placeholder="Confirm new password" required>
                <button class="btn-primary" type="submit">Update password</button>
            </form>
        </section>
    </div>
</x-layouts.app>
