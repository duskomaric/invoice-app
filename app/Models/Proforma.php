<?php

namespace App\Models;

use App\Enums\InvoiceStatusEnum;
use App\Enums\LanguageEnum;
use App\Services\DocumentNumberingService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Proforma extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'client_id',
        'status',
        'language',
        'date',
        'due_date',
        'notes',
        'currency',
        'proforma_prefix',
        'proforma_year',
        'proforma_number',
        'sourceable_type',
        'sourceable_id',
    ];

    protected $casts = [
        'status' => InvoiceStatusEnum::class,
        'date' => 'date',
        'due_date' => 'date',
        'language' => LanguageEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($proforma) {
            if (! $proforma->proforma_number) {
                $proforma->generateProformaNumber();
            }
        });
    }

    public function generateProformaNumber(): void
    {
        app(DocumentNumberingService::class)->assign($this, [
            'prefix' => 'proforma_prefix',
            'year' => 'proforma_year',
            'number' => 'proforma_number',
        ]);
    }

    public function getFormattedNumberAttribute(): string
    {
        if (! $this->proforma_number) {
            return "ID-{$this->id}";
        }

        $year = $this->proforma_year ?: (int) ($this->date?->year ?? now()->year);
        $pad = max(1, (int) CompanySetting::get('invoice_numbering_pad_zeros', 3, $this->company_id));
        $number = str_pad((string) $this->proforma_number, $pad, '0', STR_PAD_LEFT);

        if (! $this->proforma_prefix) {
            return "{$number}/{$year}";
        }

        return "{$this->proforma_prefix}-{$number}/{$year}";
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
        return $this->hasMany(ProformaItem::class);
    }

    public function sourceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function invoices(): MorphMany
    {
        return $this->morphMany(Invoice::class, 'sourceable');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'proforma_id');
    }
}
