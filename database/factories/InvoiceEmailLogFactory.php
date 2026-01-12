<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceEmailLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceEmailLogFactory extends Factory
{
    protected $model = InvoiceEmailLog::class;

    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'opened_at' => fake()->optional()->dateTimeBetween('-30 days', 'now'),
            'clicked_at' => fake()->optional()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
