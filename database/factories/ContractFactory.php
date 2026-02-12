<?php

namespace Database\Factories;

use App\Enums\InvoiceStatusEnum;
use App\Enums\LanguageEnum;
use App\Models\Client;
use App\Models\Company;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory
{
    protected $model = Contract::class;
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'client_id' => Client::factory(),
            'status' => InvoiceStatusEnum::Draft,
            'language' => LanguageEnum::SerbianLatin,
            'date' => now(),
            'currency' => 'BAM',
            'contract_year' => now()->year,
            'contract_number' => fake()->numberBetween(1, 9999),
            'notes' => fake()->optional()->sentence(),
            'file_path' => null,
            'file_original_name' => null,
            'file_mime' => null,
            'file_size' => null,
        ];
    }
}

