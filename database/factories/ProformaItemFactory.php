<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Proforma;
use App\Models\ProformaItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProformaItemFactory extends Factory
{
    protected $model = ProformaItem::class;
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 5);
        $unitPrice = fake()->numberBetween(1000, 100000);

        return [
            'proforma_id' => Proforma::factory(),
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

