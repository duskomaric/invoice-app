<?php

namespace App\Models;

use App\Enums\ArticleTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'prices_meta',
        'unit',
        'tax_category',
        'is_active',
        'type',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'prices_meta' => 'array',
        'unit' => 'string',
        'tax_category' => 'string',
        'is_active' => 'boolean',
        'type' => ArticleTypeEnum::class,
    ];

    public function getPriceInCents(string $currency): int
    {
        $price = $this->prices_meta[$currency] ?? 0;
        return (int) round($price * 100);
    }

    public function getFormattedPrice(string $currency): string
    {
        $price = $this->prices_meta[$currency] ?? 0;
        return number_format($price, 2);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
