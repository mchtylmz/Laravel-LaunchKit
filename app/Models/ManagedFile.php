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
        if ($this->size >= 1024 * 1024) {
            return round($this->size / 1024 / 1024, 2).' MB';
        }

        if ($this->size >= 1024) {
            return round($this->size / 1024, 2).' KB';
        }

        return $this->size.' B';
    }
}
