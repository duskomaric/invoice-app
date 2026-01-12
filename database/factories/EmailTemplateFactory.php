<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\EmailTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailTemplateFactory extends Factory
{
    protected $model = EmailTemplate::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->words(2, true),
            'subject' => fake()->sentence(),
            'body' => fake()->paragraphs(3, true),
            'type' => fake()->randomElement(['invoice', 'quote', 'proforma']),
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
