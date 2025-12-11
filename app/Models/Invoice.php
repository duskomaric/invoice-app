<?php

namespace App\Models;

use App\Enums\InvoiceFrequency;
use App\Enums\InvoiceStatus;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'status',
        'language',
        'date',
        'due_date',
        'amount_paid',
        'notes',
        'is_recurring',
        'frequency',
        'next_invoice_date',
        'parent_id',
        // Currency & Invoice Numbering
        'currency',
        'invoice_number',
        'sequence_number',
        'sequence_year',
        // Fiscal data
        'is_fiscalized',
        'fiscal_invoice_number',
        'fiscal_counter',
        'fiscal_verification_url',
        'fiscalized_at',
        'fiscal_meta',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
        'frequency' => InvoiceFrequency::class,
        'date' => 'date',
        'due_date' => 'date',
        'next_invoice_date' => 'date',
        'amount_paid' => 'integer',
        'is_recurring' => 'boolean',
        // Currency & numbering
        'sequence_number' => 'integer',
        'sequence_year' => 'integer',
        // Fiscal data casts
        'is_fiscalized' => 'boolean',
        'fiscalized_at' => 'datetime',
        'fiscal_meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (!$invoice->invoice_number) {
                $invoice->generateInvoiceNumber();
            }
        });
    }

    /**
     * Auto-generate invoice number based on currency and year
     */
    public function generateInvoiceNumber(): void
    {
        $year = now()->year;
        $currency = $this->currency ?? Setting::get('invoice_default_currency', 'BAM');

        // Get current sequences
        $sequences = Setting::get('invoice_sequences', []);
        $currentNumber = $sequences[$currency][$year] ?? 0;
        $nextNumber = $currentNumber + 1;

        // Update sequence in settings
        $sequences[$currency][$year] = $nextNumber;
        Setting::set('invoice_sequences', $sequences);

        // Get prefix for currency
        $prefixes = Setting::get('invoice_prefixes', ['BAM' => 'BAM', 'EUR' => 'EUR']);
        $prefix = $prefixes[$currency] ?? $currency;

        // Set values
        $this->currency = $currency;
        $this->sequence_number = $nextNumber;
        $this->sequence_year = $year;
        $this->invoice_number = sprintf('%s-%03d/%d', $prefix, $nextNumber, $year);
    }

    /**
     * Get formatted invoice number for display
     */
    public function getFormattedNumberAttribute(): string
    {
        return $this->invoice_number ?? "ID-{$this->id}";
    }

    public function getSubtotalAttribute(): int
    {
        return $this->items->sum('total');
    }

    public function getTaxAttribute(): int
    {
        return 0; // Tax is currently not handled or assumed 0
    }

    public function getTotalAttribute(): int
    {
        return $this->subtotal + $this->tax;
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Invoice::class, 'parent_id');
    }

    public function emailLogs(): HasMany
    {
        return $this->hasMany(InvoiceEmailLog::class);
    }
}
