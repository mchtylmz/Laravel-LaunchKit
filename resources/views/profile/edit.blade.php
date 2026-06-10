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
        <section class="card xl:col-span-2">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="section-title">Security</p>
                    <h3 class="mt-1 text-lg font-bold">Two-factor authentication</h3>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500 dark:text-slate-400">
                        {{ auth()->user()->two_factor_enabled ? '2FA is active. Login requires an email verification code.' : 'Protect this account by requiring an email verification code after password login.' }}
                    </p>
                </div>
                <span class="w-fit rounded-lg {{ auth()->user()->two_factor_enabled ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-950' }} px-3 py-1 text-xs font-bold">
                    {{ auth()->user()->two_factor_enabled ? 'Enabled' : 'Disabled' }}
                </span>
            </div>
            <form method="POST" action="{{ auth()->user()->two_factor_enabled ? route('profile.two-factor.disable') : route('profile.two-factor.enable') }}" class="mt-5 flex flex-col gap-3 sm:flex-row">
                @csrf
                @method('PUT')
                <input class="flex-1" name="current_password" type="password" placeholder="Current password" required>
                <button class="{{ auth()->user()->two_factor_enabled ? 'btn-secondary' : 'btn-primary' }}" type="submit">
                    {{ auth()->user()->two_factor_enabled ? 'Disable 2FA' : 'Enable 2FA' }}
                </button>
            </form>
        </section>
    </div>
</x-layouts.app>
