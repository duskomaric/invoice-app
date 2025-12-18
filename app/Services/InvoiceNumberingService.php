<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CompanySetting;
use App\Models\Invoice;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use RuntimeException;

class InvoiceNumberingService
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
}
