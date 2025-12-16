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
                'USD' => [
                    'price' => $this->faker->randomFloat(2, 1, 100),
                    'currency_code' => 'USD',
                ],
                'EUR' => [
                    'price' => $this->faker->randomFloat(2, 1, 100),
                    'currency_code' => 'EUR',
                ],
            ],
            'unit' => $this->faker->randomElement(['piece', 'kg', 'liter']),
//            'tax_category' => $this->faker->randomElement(['standard', 'reduced', 'zero']),
            'is_active' => true,
        ];
    }
}
