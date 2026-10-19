<?php

namespace Database\Factories;

use App\Enums\PlaceKind;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'kind' => fake()->randomElement(PlaceKind::cases())->value,
            /* une valeur valide de PlaceKind */
        ];
    }
}