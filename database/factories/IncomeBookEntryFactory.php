<?php

namespace Database\Factories;

use App\Enums\ReviewStatusEnum;
use App\Models\Company;
use App\Models\IncomeBookEntry;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeBookEntryFactory extends Factory
{
    protected $model = IncomeBookEntry::class;
    public function definition(): array
    {
        $incomeProducts = fake()->numberBetween(0, 100000);
        $incomeGoods = fake()->numberBetween(0, 100000);
        $incomeServices = fake()->numberBetween(0, 100000);
        $incomeOther = fake()->numberBetween(0, 50000);
        $incomeFinancial = fake()->numberBetween(0, 50000);

        $totalIncome = $incomeProducts + $incomeGoods + $incomeServices + $incomeOther + $incomeFinancial;
        $vatAmount = (int) ($totalIncome * 0.17); // 17% VAT

        return [
            'company_id' => Company::factory(),
            'booking_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'description' => fake()->optional()->sentence(),
            'income_products' => $incomeProducts,
            'income_goods' => $incomeGoods,
            'income_services' => $incomeServices,
            'income_other' => $incomeOther,
            'income_financial' => $incomeFinancial,
            'total_income' => $totalIncome,
            'vat_amount' => $vatAmount,
            'review_status' => ReviewStatusEnum::AUTO,
            'payment_id' => Payment::factory(),
            'invoice_id' => null,
            'quote_id' => null,
            'proforma_id' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'review_status' => ReviewStatusEnum::PENDING,
        ]);
    }

    public function reviewed(): static
    {
        return $this->state(fn () => [
            'review_status' => ReviewStatusEnum::REVIEWED,
        ]);
    }
}

