<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Company;
use App\Models\CompanySetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DocumentNumberingService
{
    public function assign(Model $model, array $columns, bool $preview = false): string
    {
        $companyId = $model->company_id;
        $currency = strtoupper($model->currency ?? $this->getDefaultCurrency($companyId));
        $date = $model->{$columns['date'] ?? 'date'} instanceof Carbon
            ? $model->{$columns['date'] ?? 'date'}
            : Carbon::parse($model->{$columns['date'] ?? 'date'} ?? now());

        [$prefix, $year, $pad, $start] = $this->resolveNumberingParams($companyId, $currency, $date);

        $prefixColumn = $columns['prefix'];
        $yearColumn = $columns['year'];
        $numberColumn = $columns['number'];
        $modelClass = get_class($model);

        $query = $modelClass::where('company_id', $companyId);
        if (is_null($prefix)) {
            $query->whereNull($prefixColumn);
        } else {
            $query->where($prefixColumn, $prefix);
        }

        $last = $query
            ->where($yearColumn, $year)
            ->orderByRaw('CAST('.$numberColumn.' AS UNSIGNED) DESC')
            ->value($numberColumn);

        $next = max(is_numeric($last) ? (int) $last : 0, $start - 1) + 1;
        $padded = str_pad((string) $next, $pad, '0', STR_PAD_LEFT);
        $displayYear = $year ?: (int) $date->year;

        $formatted = is_null($prefix)
            ? "{$padded}/{$displayYear}"
            : "{$prefix}-{$padded}/{$displayYear}";

        if (! $preview) {
            $model->currency = $currency;
            $model->{$prefixColumn} = $prefix;
            $model->{$yearColumn} = $year;
            $model->{$numberColumn} = $padded;
        }

        return $formatted;
    }

    public function previewForCompany(int $companyId, string $modelClass, array $columns, ?string $currency = null, mixed $date = null): string
    {
        $currency = strtoupper($currency ?? $this->getDefaultCurrency($companyId));
        $date = $date instanceof Carbon ? $date : Carbon::parse($date ?? now());

        [$prefix, $year, $pad, $start] = $this->resolveNumberingParams($companyId, $currency, $date);

        $prefixColumn = $columns['prefix'];
        $yearColumn = $columns['year'];
        $numberColumn = $columns['number'];

        $query = $modelClass::where('company_id', $companyId);
        if (is_null($prefix)) {
            $query->whereNull($prefixColumn);
        } else {
            $query->where($prefixColumn, $prefix);
        }

        $last = $query
            ->where($yearColumn, $year)
            ->orderByRaw('CAST('.$numberColumn.' AS UNSIGNED) DESC')
            ->value($numberColumn);

        $next = max(is_numeric($last) ? (int) $last : 0, $start - 1) + 1;
        $padded = str_pad((string) $next, $pad, '0', STR_PAD_LEFT);
        $displayYear = $year ?: (int) $date->year;

        return is_null($prefix)
            ? "{$padded}/{$displayYear}"
            : "{$prefix}-{$padded}/{$displayYear}";
    }

    private function getDefaultCurrency(int $companyId): string
    {
        return Company::find($companyId)?->currencies()->orderBy('code')->value('code') ?? 'BAM';
    }

    private function resolveNumberingParams(int $companyId, string $currency, Carbon $date): array
    {
        $prefixSetting = (string) CompanySetting::get('invoice_numbering_prefix', 'currency', $companyId);
        $prefix = match ($prefixSetting) {
            '', 'none' => null,
            'currency' => $currency,
            default => strtoupper($prefixSetting),
        };
        $year = (bool) CompanySetting::get('invoice_numbering_reset_yearly', true, $companyId) ? (int) $date->year : 0;
        $pad = max(1, (int) CompanySetting::get('invoice_numbering_pad_zeros', 3, $companyId));
        $start = max(1, (int) CompanySetting::get('invoice_numbering_starting_number', 1, $companyId));

        return [$prefix, $year, $pad, $start];
    }
}
