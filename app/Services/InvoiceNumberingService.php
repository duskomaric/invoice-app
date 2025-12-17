<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Setting;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;

class InvoiceNumberingService
{
    public function usesPerCurrencySequence(): bool
    {
        $format = $this->getFormat();

        return str_contains($format, '{currency}') || str_contains($format, '{prefix}');
    }

    public function preview(?string $currency, mixed $date): string
    {
        $currency = $this->resolveCurrency($currency);
        $carbon = $this->normalizeDate($date);

        $companyId = $this->resolveCompanyId(null);
        $year = (int) $carbon->year;

        $lastNumber = $this->getLastSequenceNumberFromDatabase($companyId, $currency, $carbon);
        $startingNumber = $this->getStartingNumber();
        $nextNumber = max($lastNumber, $startingNumber - 1) + 1;

        return $this->formatInvoiceNumber($currency, $nextNumber, $year, $carbon);
    }

    public function assignToInvoice(Invoice $invoice): void
    {
        $currency = $this->resolveCurrency($invoice->currency);
        $date = $this->normalizeDate($invoice->date);

        $companyId = $this->resolveCompanyId($invoice->company_id);

        $year = (int) $date->year;

        $lastNumber = $this->getLastSequenceNumberFromDatabase($companyId, $currency, $date);
        $startingNumber = $this->getStartingNumber();
        $nextNumber = max($lastNumber, $startingNumber - 1) + 1;

        $invoice->currency = $currency;
        $invoice->sequence_number = $nextNumber;
        $invoice->sequence_year = $year;
        $invoice->invoice_number = $this->formatInvoiceNumber($currency, $nextNumber, $year, $date);
    }

    private function resolveCurrency(?string $currency): string
    {
        if ($currency) {
            return strtoupper($currency);
        }

        $firstCurrency = Filament::getTenant()?->currencies()->orderBy('code')->value('code');
        if (is_string($firstCurrency) && $firstCurrency !== '') {
            return strtoupper($firstCurrency);
        }

        return 'BAM';
    }

    private function normalizeDate(mixed $date): Carbon
    {
        if ($date instanceof Carbon) {
            return $date;
        }

        if ($date instanceof \DateTimeInterface) {
            return Carbon::instance($date);
        }

        if (is_string($date) && $date !== '') {
            return Carbon::parse($date);
        }

        return now();
    }

    private function getLastSequenceNumberFromDatabase(int $companyId, string $currency, Carbon $date): int
    {
        $query = Invoice::query();

        $query->where('company_id', $companyId);

        if ($this->usesPerCurrencySequence()) {
            $query->where('currency', $currency);
        }

        $query->whereYear('date', $date->year);

        return (int) $query->max('sequence_number');
    }

    private function resolveCompanyId(?int $companyId): int
    {
        if ($companyId) {
            return $companyId;
        }

        try {
            return Filament::getTenant()?->id ?? 1;
        } catch (\Throwable) {
            return 1;
        }
    }

    private function formatInvoiceNumber(string $currency, int $number, int $year, Carbon $date): string
    {
        $padLength = $this->getPadLength();
        $prefix = $this->getPrefix($currency);
        $format = $this->getFormat();

        $replacements = [
            '{currency}' => $currency,
            '{prefix}' => $prefix,
            '{number}' => str_pad((string) $number, $padLength, '0', STR_PAD_LEFT),
            '{year}' => (string) $year,
            '{month}' => $date->format('m'),
            '{day}' => $date->format('d'),
        ];

        return strtr($format, $replacements);
    }

    private function getFormat(): string
    {
        $format = Setting::get('invoice_numbering_format', '{prefix}-{number}/{year}');

        return is_string($format) && $format !== ''
            ? $format
            : '{prefix}-{number}/{year}';
    }

    private function getPrefix(string $currency): string
    {
        $companyId = $this->resolveCompanyId(null);
        $currencyModel = Currency::where('company_id', $companyId)
            ->where('code', $currency)
            ->first();

        if ($currencyModel?->prefix) {
            return (string) $currencyModel->prefix;
        }

        return $currency;
    }

    private function getPadLength(): int
    {
        $defaultPadLength = Setting::get('invoice_numbering_pad_length', 3);

        return max(1, (int) $defaultPadLength);
    }

    private function getStartingNumber(): int
    {
        $defaultStartingNumber = Setting::get('invoice_numbering_start_number', 1);

        return max(1, (int) $defaultStartingNumber);
    }
}
