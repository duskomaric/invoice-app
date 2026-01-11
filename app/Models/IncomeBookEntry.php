<?php

namespace App\Models;

use App\Enums\ReviewStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomeBookEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'booking_date',
        'description',
        'income_products',
        'income_goods',
        'income_services',
        'income_other',
        'income_financial',
        'total_income',
        'vat_amount',
        'review_status',
        'payment_id',
        'invoice_id',
        'quote_id',
        'proforma_id',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'income_products' => 'integer',
        'income_goods' => 'integer',
        'income_services' => 'integer',
        'income_other' => 'integer',
        'income_financial' => 'integer',
        'total_income' => 'integer',
        'vat_amount' => 'integer',
        'review_status' => ReviewStatusEnum::class,
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function proforma(): BelongsTo
    {
        return $this->belongsTo(Proforma::class);
    }
}
