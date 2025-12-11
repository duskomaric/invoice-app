<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'amount' => $this->faker->numberBetween(1000, 100000), // 10.00 - 1000.00
            'payment_date' => $this->faker->date(),
            'notes' => $this->faker->sentence(),
        ];
    }
}
