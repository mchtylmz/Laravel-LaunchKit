<x-layouts.app title="Sessions">
    <div class="mb-6">
        <p class="section-title">Security</p>
        <h2 class="mt-1 text-2xl font-black">Active sessions</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Manage active login sessions across devices.</p>
    </div>

    <div class="space-y-4">
        @foreach ($sessions as $session)
            <div class="card flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-slate-100 text-base dark:bg-slate-950">
                        @php
                            $icons = ['iOS' => '📱', 'Android' => '📱', 'macOS' => '💻', 'Windows' => '💻', 'Linux' => '🐧'];
                        @endphp
                        {{ $icons[$session->device['device']] ?? '❓' }}
                    </span>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-semibold">{{ $session->device['device'] }}</h3>
                            @if ($session->is_current)
                                <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-bold text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300">Current</span>
                            @endif
                        </div>
                        <p class="mt-0.5 text-sm text-slate-500">
                            {{ $session->device['browser'] ? "$session->device[browser] · " : '' }}{{ $session->ip_address }} · {{ $session->last_active }}
                        </p>
                    </div>
                </div>
                @unless ($session->is_current)
                    <form method="POST" action="{{ route('sessions.destroy', $session->id) }}" onsubmit="return confirm('Oturumu sonlandırmak istediğinize emin misiniz?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn-secondary text-sm" type="submit">Terminate</button>
                    </form>
                @endunless
            </div>
        @endforeach
    </div>

    @if ($sessions->count() > 1)
        <form method="POST" action="{{ route('sessions.destroy-other') }}" class="mt-6">
            @csrf
            @method('DELETE')
            <div class="card">
                <p class="text-sm font-semibold">Log out other devices</p>
                <p class="mt-1 text-sm text-slate-500">Requires current password. This will end all sessions except this one.</p>
                <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                    <input class="flex-1" name="current_password" type="password" placeholder="Current password" required>
                    <button class="btn-primary" type="submit">Log out other devices</button>
                </div>
            </div>
        </form>
    @endif
</x-layouts.app>
