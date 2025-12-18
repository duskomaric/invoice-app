<?php

namespace App\Models;

use App\Enums\InvoiceFrequency;
use App\Enums\InvoiceStatus;
use App\Enums\LanguageEnum;
use App\Services\InvoiceNumberingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
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
        'invoice_prefix',
        'invoice_year',
        'invoice_number',
        'invoice_template',
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
        // Fiscal data casts
        'is_fiscalized' => 'boolean',
        'fiscalized_at' => 'datetime',
        'fiscal_meta' => 'array',
        'language' => LanguageEnum::class,
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
        app(InvoiceNumberingService::class)->assignToInvoice($this);
    }

    /**
     * Get formatted invoice number for display
     */
    public function getFormattedNumberAttribute(): string
    {
        if (! $this->invoice_number) {
            return "ID-{$this->id}";
        }

        $year = $this->invoice_year ?: (int) ($this->date?->year ?? now()->year);

        if (! $this->invoice_prefix) {
            return "{$this->invoice_number}/{$year}";
        }

        return "{$this->invoice_prefix}-{$this->invoice_number}/{$year}";
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

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function bankAccounts(): BelongsToMany
    {
        return $this->belongsToMany(CompanyBankAccount::class)
            ->withTimestamps();
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
