<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class LaunchKitSettings
{
    private static ?array $settings = null;

    public static function all(): array
    {
        if (self::$settings !== null) {
            return self::$settings;
        }

        if (! Schema::hasTable('settings')) {
            return self::$settings = [];
        }

        return self::$settings = Setting::query()->pluck('value', 'key')->all();
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::all()[$key] ?? $default;
    }

    public static function boolean(string $key, bool $default = false): bool
    {
        return filter_var(self::get($key, $default), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $default;
    }

    public static function integer(string $key, int $default = 0): int
    {
        return (int) (self::get($key, $default) ?? $default);
    }
}
