<?php

namespace App\Models;

use App\Observers\EmailSignatureObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([EmailSignatureObserver::class])]
class EmailSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'content',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
