<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'zip',
        'country',
        'tax_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function getBalanceAttribute(): int
    {
        $totalInvoiced = InvoiceItem::whereHas('invoice', function ($query) {
            $query->where('client_id', $this->id)
                  ->where('status', '!=', \App\Enums\InvoiceStatus::Draft);
        })->sum('total');
            
        $totalPaid = $this->payments()->sum('amount');

        return $totalInvoiced - $totalPaid;
    }
}
