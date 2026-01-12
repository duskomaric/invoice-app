<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanySetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanySettingFactory extends Factory
{
    protected $model = CompanySetting::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'key' => fake()->unique()->word(),
            'value' => json_encode(fake()->word()),
        ];
    }
}
