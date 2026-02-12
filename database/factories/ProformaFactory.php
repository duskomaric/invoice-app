<?php

namespace Database\Factories;

use App\Enums\InvoiceStatusEnum;
use App\Enums\LanguageEnum;
use App\Models\Client;
use App\Models\Company;
use App\Models\Proforma;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProformaFactory extends Factory
{
    protected $model = Proforma::class;
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'client_id' => Client::factory(),
            'status' => InvoiceStatusEnum::Draft,
            'language' => LanguageEnum::SerbianLatin,
            'date' => now(),
            'due_date' => now()->addDays(30),
            'currency' => 'BAM',
            'proforma_year' => now()->year,
            'proforma_number' => fake()->numberBetween(1, 9999),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}

