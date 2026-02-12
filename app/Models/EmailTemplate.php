<?php

namespace App\Models;

use App\Observers\EmailTemplateObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([EmailTemplateObserver::class])]
class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'subject',
        'body',
        'type',
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
