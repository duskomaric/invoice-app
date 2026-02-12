<?php

namespace App\Models;

use App\Enums\InvoiceStatusEnum;
use App\Enums\LanguageEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'client_id',
        'status',
        'language',
        'date',
        'notes',
        'currency',
        'contract_prefix',
        'contract_year',
        'contract_number',
        'file_path',
        'file_original_name',
        'file_mime',
        'file_size',
    ];

    protected $casts = [
        'status' => InvoiceStatusEnum::class,
        'date' => 'date',
        'language' => LanguageEnum::class,
        'file_size' => 'integer',
    ];

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
        return $this->hasMany(ContractItem::class);
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

    public function getFormattedNumberAttribute(): string
    {
        if (!$this->contract_number) {
            return "ID-{$this->id}";
        }

        $year = $this->contract_year ?: (int) ($this->date?->year ?? now()->year);

        if (!$this->contract_prefix) {
            return "{$this->contract_number}/{$year}";
        }

        return "{$this->contract_prefix}-{$this->contract_number}/{$year}";
    }
}

