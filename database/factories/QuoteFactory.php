<?php

namespace Database\Factories;

use App\Enums\InvoiceStatusEnum;
use App\Enums\LanguageEnum;
use App\Models\Client;
use App\Models\Company;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    protected $model = Quote::class;
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'client_id' => Client::factory(),
            'status' => InvoiceStatusEnum::Draft,
            'language' => LanguageEnum::SerbianLatin,
            'date' => now(),
            'valid_until' => now()->addDays(30),
            'currency' => 'BAM',
            'quote_year' => now()->year,
            'quote_number' => fake()->numberBetween(1, 9999),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}

