<x-layouts.app title="Settings">
    <section class="card max-w-3xl">
        <p class="section-title">Configuration</p>
        <h2 class="mt-1 text-2xl font-black">Application settings</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Tune product identity, registration and upload limits from one place.</p>
        <form method="POST" action="{{ route('settings.update') }}" class="mt-5 grid gap-4">
            @csrf
            @method('PUT')
            <input name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'Laravel LaunchKit') }}" required>
            <input name="site_description" value="{{ old('site_description', $settings['site_description'] ?? 'Open source Laravel starter kit') }}">
            <select name="default_theme">
                @foreach (['system', 'light', 'dark'] as $theme)
                    <option value="{{ $theme }}" @selected(($settings['default_theme'] ?? 'system') === $theme)>{{ ucfirst($theme) }}</option>
                @endforeach
            </select>
            <select name="registration_enabled">
                <option value="1" @selected(($settings['registration_enabled'] ?? '1') === '1')>Registration enabled</option>
                <option value="0" @selected(($settings['registration_enabled'] ?? '1') === '0')>Registration disabled</option>
            </select>
            <input name="max_upload_size" type="number" value="{{ old('max_upload_size', $settings['max_upload_size'] ?? 10240) }}" min="1" max="10240">
            <button class="btn-primary w-fit" type="submit">Save settings</button>
        </form>
    </section>

    <section class="card mt-6 max-w-3xl">
        <p class="section-title">Audit history</p>
        <h2 class="mt-1 text-xl font-black">Settings audit history</h2>
        <div class="mt-4 space-y-3">
            @forelse ($audits as $audit)
                <div class="rounded-lg border border-slate-100 bg-slate-50 p-3 text-sm dark:border-white/10 dark:bg-slate-950">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-semibold">{{ $audit->key }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $audit->old_value ?? 'empty' }} → {{ $audit->new_value ?? 'empty' }}</p>
                        </div>
                        <p class="text-xs text-slate-500">{{ $audit->user?->name ?? 'System' }} · {{ $audit->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            @empty
                <p class="rounded-lg bg-slate-50 p-3 text-sm text-slate-500 dark:bg-slate-950">No settings changes recorded yet.</p>
            @endforelse
        </div>
    </section>
</x-layouts.app>
