<?php

namespace App\Models;

use App\Enums\Color;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'place_id',
    'name',
    'description',
    'color',
    'found_on',
    'returned_at',
])]
class Item extends Model
{
    use HasFactory;

    public function isReturned(): bool
    {
        return $this->returned_at !== null;
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    protected function casts(): array
    {
        return [
            'color' => Color::class,
            'found_on' => 'date',
            'returned_at' => 'date',
        ];
    }
}