<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'status' => InvoiceStatus::Draft,
            'date' => now(),
            'due_date' => now()->addDays(30),
            'is_recurring' => false,
        ];
    }

}
