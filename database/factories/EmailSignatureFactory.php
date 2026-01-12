<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\EmailSignature;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailSignatureFactory extends Factory
{
    protected $model = EmailSignature::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->words(2, true),
            'content' => fake()->paragraph(),
            'is_default' => false,
        ];
    }

    public function default(): static
    {
        return $this->state(fn () => [
            'is_default' => true,
        ]);
    }
}
