<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'city',
        'postal_code',
        'country',
        'phone',
        'email',
        'website',
        'identification_number',
        'vat_number',
        'town',
        // OFS Configuration
        'ofs_base_url',
        'ofs_api_key',
        'ofs_serial_number',
        'ofs_pac',
        'is_active',
        'subscription_ends_at',
        // SMTP Configuration
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_from_address',
        'smtp_from_name',
        'is_small_business',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscription_ends_at' => 'datetime',
        'is_small_business' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function proformas(): HasMany
    {
        return $this->hasMany(Proforma::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function currencies(): HasMany
    {
        return $this->hasMany(Currency::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(CompanyBankAccount::class);
    }

    public function incomeBookEntries(): HasMany
    {
        return $this->hasMany(IncomeBookEntry::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
