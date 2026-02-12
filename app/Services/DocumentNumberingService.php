<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CompanySetting;
use App\Models\Invoice;
use App\Models\Proforma;
use App\Models\Quote;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use RuntimeException;

class DocumentNumberingService
{
    public function preview(?string $currency, mixed $date): string
    {
        $companyId = Filament::getTenant()?->id;
        $currency = strtoupper($currency ?: ((string) Filament::getTenant()?->currencies()->orderBy('code')->value('code') ?: 'BAM'));
        $date = $date instanceof Carbon ? $date : Carbon::parse($date ?: now());

        $prefixSetting = (string) CompanySetting::get('invoice_numbering_prefix', 'currency', $companyId);
        $prefix = match ($prefixSetting) {
            '', 'none' => null,
            'currency' => $currency,
            default => strtoupper($prefixSetting),
        };
        $year = (bool) CompanySetting::get('invoice_numbering_reset_yearly', true, $companyId) ? (int) $date->year : 0;
        $pad = max(1, (int) CompanySetting::get('invoice_numbering_pad_zeros', 3, $companyId));
        $start = max(1, (int) CompanySetting::get('invoice_numbering_starting_number', 1, $companyId));

        $last = null;
        if ($companyId) {
            $query = Invoice::where('company_id', $companyId);
            if (is_null($prefix)) {
                $query->whereNull('invoice_prefix');
            } else {
                $query->where('invoice_prefix', $prefix);
            }

            $last = $query
                ->where('invoice_year', $year)
                ->orderByRaw('CAST(invoice_number AS UNSIGNED) DESC')
                ->value('invoice_number');
        }

        $next = max(is_string($last) ? (int) ltrim($last, '0') : 0, $start - 1) + 1;
        $padded = str_pad((string) $next, $pad, '0', STR_PAD_LEFT);

        $displayYear = $year ?: (int) $date->year;

        return is_null($prefix)
            ? "{$padded}/{$displayYear}"
            : "{$prefix}-{$padded}/{$displayYear}";
    }

    public function previewForQuote(?string $currency, mixed $date): string
    {
        return $this->previewForModel(Quote::class, 'quote_prefix', 'quote_year', 'quote_number', $currency, $date);
    }

    public function previewForProforma(?string $currency, mixed $date): string
    {
        return $this->previewForModel(Proforma::class, 'proforma_prefix', 'proforma_year', 'proforma_number', $currency, $date);
    }

    public function assignToInvoice(Invoice $invoice): void
    {
        if (! $invoice->company_id) {
            throw new RuntimeException('Invoice company_id must be set before assigning an invoice number.');
        }

        $companyId = $invoice->company_id;
        $currency = strtoupper($invoice->currency ?: ((string) Filament::getTenant()?->currencies()->orderBy('code')->value('code') ?: 'BAM'));
        $date = $invoice->date instanceof Carbon ? $invoice->date : Carbon::parse($invoice->date ?: now());

        $prefixSetting = (string) CompanySetting::get('invoice_numbering_prefix', 'currency', $companyId);
        $prefix = match ($prefixSetting) {
            '', 'none' => null,
            'currency' => $currency,
            default => strtoupper($prefixSetting),
        };
        $year = (bool) CompanySetting::get('invoice_numbering_reset_yearly', true, $companyId) ? (int) $date->year : 0;
        $pad = max(1, (int) CompanySetting::get('invoice_numbering_pad_zeros', 3, $companyId));
        $start = max(1, (int) CompanySetting::get('invoice_numbering_starting_number', 1, $companyId));

        $query = Invoice::where('company_id', $companyId);
        if (is_null($prefix)) {
            $query->whereNull('invoice_prefix');
        } else {
            $query->where('invoice_prefix', $prefix);
        }

        $last = $query
            ->where('invoice_year', $year)
            ->orderByRaw('CAST(invoice_number AS UNSIGNED) DESC')
            ->value('invoice_number');

        $next = max(is_string($last) ? (int) ltrim($last, '0') : 0, $start - 1) + 1;

        $invoice->currency = $currency;
        $invoice->invoice_prefix = $prefix;
        $invoice->invoice_year = $year;
        $invoice->invoice_number = str_pad((string) $next, $pad, '0', STR_PAD_LEFT);
    }

    public function assignToQuote(Quote $quote): void
    {
        if (! $quote->company_id) {
            throw new RuntimeException('Quote company_id must be set before assigning a quote number.');
        }

        [$currency, $prefix, $year, $pad, $start] = $this->resolveNumberingParams($quote->company_id, $quote->currency, $quote->date);

        $last = Quote::where('company_id', $quote->company_id)
            ->when(is_null($prefix), fn ($q) => $q->whereNull('quote_prefix'), fn ($q) => $q->where('quote_prefix', $prefix))
            ->where('quote_year', $year)
            ->orderByRaw('CAST(quote_number AS UNSIGNED) DESC')
            ->value('quote_number');

        $next = max(is_numeric($last) ? (int) $last : 0, $start - 1) + 1;

        $quote->currency = $currency;
        $quote->quote_prefix = $prefix;
        $quote->quote_year = $year;
        $quote->quote_number = $next;
    }

    public function assignToProforma(Proforma $proforma): void
    {
        if (! $proforma->company_id) {
            throw new RuntimeException('Proforma company_id must be set before assigning a proforma number.');
        }

        [$currency, $prefix, $year, $pad, $start] = $this->resolveNumberingParams($proforma->company_id, $proforma->currency, $proforma->date);

        $last = Proforma::where('company_id', $proforma->company_id)
            ->when(is_null($prefix), fn ($q) => $q->whereNull('proforma_prefix'), fn ($q) => $q->where('proforma_prefix', $prefix))
            ->where('proforma_year', $year)
            ->orderByRaw('CAST(proforma_number AS UNSIGNED) DESC')
            ->value('proforma_number');

        $next = max(is_numeric($last) ? (int) $last : 0, $start - 1) + 1;

        $proforma->currency = $currency;
        $proforma->proforma_prefix = $prefix;
        $proforma->proforma_year = $year;
        $proforma->proforma_number = $next;
    }

    private function previewForModel(string $modelClass, string $prefixColumn, string $yearColumn, string $numberColumn, ?string $currency, mixed $date): string
    {
        $companyId = Filament::getTenant()?->id;
        $currency = strtoupper($currency ?: ((string) Filament::getTenant()?->currencies()->orderBy('code')->value('code') ?: 'BAM'));
        $date = $date instanceof Carbon ? $date : Carbon::parse($date ?: now());

        [$currency, $prefix, $year, $pad, $start] = $this->resolveNumberingParams($companyId, $currency, $date);

        $last = null;
        if ($companyId) {
            $query = $modelClass::where('company_id', $companyId);
            if (is_null($prefix)) {
                $query->whereNull($prefixColumn);
            } else {
                $query->where($prefixColumn, $prefix);
            }

            $last = $query
                ->where($yearColumn, $year)
                ->orderByRaw('CAST(' . $numberColumn . ' AS UNSIGNED) DESC')
                ->value($numberColumn);
        }

        $next = max(is_numeric($last) ? (int) $last : 0, $start - 1) + 1;
        $padded = str_pad((string) $next, $pad, '0', STR_PAD_LEFT);
        $displayYear = $year ?: (int) $date->year;

        return is_null($prefix)
            ? "{$padded}/{$displayYear}"
            : "{$prefix}-{$padded}/{$displayYear}";
    }

    private function resolveNumberingParams(?int $companyId, ?string $currency, mixed $date): array
    {
        $currency = strtoupper($currency ?: ((string) Filament::getTenant()?->currencies()->orderBy('code')->value('code') ?: 'BAM'));
        $date = $date instanceof Carbon ? $date : Carbon::parse($date ?: now());

        $prefixSetting = (string) CompanySetting::get('invoice_numbering_prefix', 'currency', $companyId);
        $prefix = match ($prefixSetting) {
            '', 'none' => null,
            'currency' => $currency,
            default => strtoupper($prefixSetting),
        };
        $year = (bool) CompanySetting::get('invoice_numbering_reset_yearly', true, $companyId) ? (int) $date->year : 0;
        $pad = max(1, (int) CompanySetting::get('invoice_numbering_pad_zeros', 3, $companyId));
        $start = max(1, (int) CompanySetting::get('invoice_numbering_starting_number', 1, $companyId));

        return [$currency, $prefix, $year, $pad, $start];
    }
}
