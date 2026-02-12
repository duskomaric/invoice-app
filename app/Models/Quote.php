<?php

namespace App\Models;

use App\Enums\InvoiceStatusEnum;
use App\Enums\LanguageEnum;
use App\Models\CompanySetting;
use App\Services\DocumentNumberingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'client_id',
        'status',
        'language',
        'date',
        'valid_until',
        'notes',
        'currency',
        'quote_prefix',
        'quote_year',
        'quote_number',
    ];

    protected $casts = [
        'status' => InvoiceStatusEnum::class,
        'date' => 'date',
        'valid_until' => 'date',
        'language' => LanguageEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quote) {
            if (!$quote->quote_number) {
                $quote->generateQuoteNumber();
            }
        });
    }

    public function generateQuoteNumber(): void
    {
        app(DocumentNumberingService::class)->assign($this, [
            'prefix' => 'quote_prefix',
            'year' => 'quote_year',
            'number' => 'quote_number',
        ]);
    }

    public function getFormattedNumberAttribute(): string
    {
        if (!$this->quote_number) {
            return "ID-{$this->id}";
        }

        $year = $this->quote_year ?: (int) ($this->date?->year ?? now()->year);
        $pad = max(1, (int) CompanySetting::get('invoice_numbering_pad_zeros', 3, $this->company_id));
        $number = str_pad((string) $this->quote_number, $pad, '0', STR_PAD_LEFT);

        if (! $this->quote_prefix) {
            return "{$number}/{$year}";
        }

        return "{$this->quote_prefix}-{$number}/{$year}";
    }

    public function getSubtotalAttribute(): int
    {
        return $this->items->sum('total');
    }

    public function getTaxAttribute(): int
    {
        return 0;
    }

    public function getTotalAttribute(): int
    {
        return $this->subtotal + $this->tax;
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function proformas(): MorphMany
    {
        return $this->morphMany(Proforma::class, 'sourceable');
    }

    public function invoices(): MorphMany
    {
        return $this->morphMany(Invoice::class, 'sourceable');
    }
}
