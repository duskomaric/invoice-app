<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemFactory extends Factory
{
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 5);
        $unitPrice = fake()->numberBetween(1000, 100000);

        return [
            'invoice_id' => Invoice::factory(),
            'article_id' => null,
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total' => $unitPrice * $quantity,
            'tax_rate' => 0,
        ];
    }
}
