<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemFactory extends Factory
{
    public function definition(): array
    {
        $article = Article::inRandomOrder()->first() ?? Article::factory()->create();
        $quantity = fake()->numberBetween(1, 5);

        return [
            'invoice_id' => Invoice::factory(),
            'article_id' => $article->id,
            'description' => $article->name,
            'quantity' => $quantity,
            'unit_price' => $article->price,
            'total' => $article->price * $quantity,
        ];
    }
}
