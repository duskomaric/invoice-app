<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Contract;
use App\Models\ContractItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractItemFactory extends Factory
{
    protected $model = ContractItem::class;
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 5);
        $unitPrice = fake()->numberBetween(1000, 100000);

        return [
            'contract_id' => Contract::factory(),
            'article_id' => null,
            'name' => fake()->words(3, true),
            'description' => fake()->optional()->sentence(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total' => $unitPrice * $quantity,
            'tax_rate' => fake()->randomElement([0, 17, 21]),
        ];
    }
}

