<?php

namespace App\Models;

use App\Enums\PaymentTypeEnum;
use App\Observers\PaymentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([PaymentObserver::class])]
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'client_id',
        'invoice_id',
        'quote_id',
        'proforma_id',
        'amount',
        'payment_date',
        'notes',
        'type',
        'payment_method',
        'document_number',
    ];

    protected $casts = [
        'amount' => 'integer',
        'payment_date' => 'date',
        'type' => PaymentTypeEnum::class,
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
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

    public function incomeBookEntries(): HasMany
    {
        return $this->hasMany(IncomeBookEntry::class);
    }
}
