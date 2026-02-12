<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProformaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'proforma_id',
        'article_id',
        'name',
        'description',
        'quantity',
        'unit_price',
        'total',
        'tax_rate',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'integer',
        'total' => 'integer',
        'tax_rate' => 'integer',
    ];

    public function proforma(): BelongsTo
    {
        return $this->belongsTo(Proforma::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
