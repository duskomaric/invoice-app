<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteItemFactory extends Factory
{
    protected $model = QuoteItem::class;
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 5);
        $unitPrice = fake()->numberBetween(1000, 100000);

        return [
            'quote_id' => Quote::factory(),
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

