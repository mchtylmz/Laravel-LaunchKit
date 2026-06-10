<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'original_name', 'stored_name', 'path', 'disk', 'mime_type', 'size'])]
class ManagedFile extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sizeForHumans(): string
    {
        return self::formatSize($this->size);
    }

    public function category(): string
    {
        $mimeType = $this->mime_type ?? '';

        return match (true) {
            str_starts_with($mimeType, 'image/') => 'image',
            str_starts_with($mimeType, 'application/'), str_starts_with($mimeType, 'text/') => 'document',
            default => 'other',
        };
    }

    public function categoryLabel(): string
    {
        return ucfirst($this->category());
    }

    public static function formatSize(int $size): string
    {
        if ($size >= 1024 * 1024) {
            return round($size / 1024 / 1024, 2).' MB';
        }

        if ($size >= 1024) {
            return round($size / 1024, 2).' KB';
        }

        return $size.' B';
    }
}
