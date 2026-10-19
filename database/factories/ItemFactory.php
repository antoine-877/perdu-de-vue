<?php

namespace Database\Factories;


use App\Models\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'place_id' => Place::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'color' => fake()->safeColorName(),
            'found_on' => fake()->dateTimeBetween(
                '-1 month',
                'now'
            )->format('Y-m-d'),
            'returned_at' => null,
        ];
    }
}
