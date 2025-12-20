<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->catchPhrase(),
            'description' => fake()->sentence(),
            'prices_meta' => [
                'USD' => 100.50,
                'EUR' => 122.30,
                'BAM' => 196.60,
            ],
            'unit' => $this->faker->randomElement(['piece', 'kg', 'liter']),
            'tax_category' => $this->faker->randomElement(['F', 'N', 'P']),
            'is_active' => true,
        ];
    }
}
