<?php

namespace Database\Factories;

use App\Enums\PaymentTypeEnum;
use App\Models\Client;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'client_id' => Client::factory(),
            'invoice_id' => null,
            'quote_id' => null,
            'proforma_id' => null,
            'type' => PaymentTypeEnum::INCOME,
            'amount' => fake()->numberBetween(1000, 100000),
            'payment_method' => fake()->randomElement(['cash', 'bank_transfer', 'card', 'check']),
            'document_number' => fake()->optional()->numerify('DOC-####'),
            'payment_date' => now()->toDateString(),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function expense(): static
    {
        return $this->state(fn () => [
            'type' => PaymentTypeEnum::EXPENSE,
        ]);
    }
}
