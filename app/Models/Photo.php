<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['item_id', 'path', 'is_cover'])]
class Photo extends Model
{
    protected function casts(): array
    {
        return ['is_cover' => 'boolean'];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
