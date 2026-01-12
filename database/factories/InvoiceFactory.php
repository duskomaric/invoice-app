<?php

namespace Database\Factories;

use App\Enums\InvoiceFrequencyEnum;
use App\Enums\InvoiceStatusEnum;
use App\Enums\LanguageEnum;
use App\Models\Client;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'client_id' => Client::factory(),
            'status' => InvoiceStatusEnum::Draft,
            'language' => LanguageEnum::SerbianLatin,
            'date' => now(),
            'due_date' => now()->addDays(30),
            'amount_paid' => 0,
            'currency' => 'BAM',
            'invoice_year' => now()->year,
            'invoice_number' => fake()->numberBetween(1, 9999),
            'is_recurring' => false,
            'is_fiscalized' => false,
        ];
    }

    public function recurring(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_recurring' => true,
            'frequency' => InvoiceFrequencyEnum::MONTHLY,
            'next_invoice_date' => now()->addMonth(),
        ]);
    }

    public function paid(): static
    {
        return $this->state(function (array $attributes) {
            $total = $attributes['amount_paid'] ?? 0;

            return [
                'amount_paid' => $total > 0 ? $total : fake()->numberBetween(1000, 100000),
            ];
        });
    }
}
