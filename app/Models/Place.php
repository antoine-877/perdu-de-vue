<?php

namespace App\Models;

use App\Enums\PlaceKind;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'kind',
    'photo_path',
])]
class Place extends Model
{
    use HasFactory;

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    protected function casts(): array
    {
        return [
            'kind' => PlaceKind::class,
        ];
    }
}
