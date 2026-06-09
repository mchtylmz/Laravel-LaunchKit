<x-layouts.app title="Settings">
    <section class="card max-w-3xl">
        <h2 class="text-lg font-semibold">Application settings</h2>
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
</x-layouts.app>
