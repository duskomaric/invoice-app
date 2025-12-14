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
        'bank_account',
        // OFS Configuration
        'ofs_base_url',
        'ofs_api_key',
        'ofs_serial_number',
        'ofs_pac',
        'is_active',
        'subscription_ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscription_ends_at' => 'datetime',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }
}
