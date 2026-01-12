<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition(): array
    {
        $currencies = [
            ['code' => 'BAM', 'name' => 'Konvertibilna Marka', 'prefix' => 'KM'],
            ['code' => 'EUR', 'name' => 'Euro', 'prefix' => '€'],
            ['code' => 'USD', 'name' => 'US Dollar', 'prefix' => '$'],
            ['code' => 'GBP', 'name' => 'British Pound', 'prefix' => '£'],
        ];

        $currency = fake()->randomElement($currencies);

        return [
            'company_id' => Company::factory(),
            'code' => $currency['code'],
            'name' => $currency['name'],
            'prefix' => $currency['prefix'],
        ];
    }

    public function bam(): static
    {
        return $this->state(fn () => [
            'code' => 'BAM',
            'name' => 'Konvertibilna Marka',
            'prefix' => 'KM',
        ]);
    }

    public function eur(): static
    {
        return $this->state(fn () => [
            'code' => 'EUR',
            'name' => 'Euro',
            'prefix' => '€',
        ]);
    }

    public function usd(): static
    {
        return $this->state(fn () => [
            'code' => 'USD',
            'name' => 'US Dollar',
            'prefix' => '$',
        ]);
    }

    public function gbp(): static
    {
        return $this->state(fn () => [
            'code' => 'GBP',
            'name' => 'British Pound',
            'prefix' => '£',
        ]);
    }
}
