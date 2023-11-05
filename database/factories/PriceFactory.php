<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Price>
 */
class PriceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'price' => fake()->randomFloat(nbMaxDecimals: 2, min: 1, max: 20),
        ];
    }
}
