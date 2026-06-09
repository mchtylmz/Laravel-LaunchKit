<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value', 'type'])]
class Setting extends Model
{
    public static function value(string $key, ?string $fallback = null): ?string
    {
        return static::query()->where('key', $key)->value('value') ?? $fallback;
    }
}
