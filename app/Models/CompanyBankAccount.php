<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CompanyBankAccount extends Model
{
    protected $fillable = [
        'company_id',
        'bank_name',
        'account_number',
        'swift',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $bankAccount) {
            if (! $bankAccount->is_default) {
                return;
            }

            self::where('company_id', $bankAccount->company_id)
                ->where('id', '!=', $bankAccount->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class)
            ->withTimestamps();
    }
}
