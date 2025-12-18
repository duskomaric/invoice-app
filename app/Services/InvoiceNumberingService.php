<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Currency;
use App\Models\CompanySetting;
use App\Models\Invoice;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use RuntimeException;

class InvoiceNumberingService
{
    public function usesPerCurrencySequence(): bool
    {
        return $this->usesPerCurrencySequenceForFormat($this->getFormat());
    }

    public function preview(?string $currency, mixed $date): string
    {
        $currency = $this->resolveCurrency($currency);
        $carbon = $this->normalizeDate($date);

        $companyId = $this->resolveCompanyId(null);
        $format = $this->getFormat();
        return $this->previewForConfig(
            format: $format,
            currency: $currency,
            date: $carbon,
            companyId: $companyId,
        );
    }

    public function previewForConfig(
        string $format,
        ?int $padLength = null,
        ?int $startingNumber = null,
        ?string $currency = null,
        mixed $date = null,
        ?int $companyId = null,
    ): string {
        $currency = $this->resolveCurrency($currency);
        $carbon = $this->normalizeDate($date);

        $resolvedCompanyId = $this->resolveCompanyId($companyId);
        $year = (int) $carbon->year;

        $usesPerCurrencySequence = $this->usesPerCurrencySequenceForFormat($format);

        $lastNumber = $this->getLastSequenceNumber(
            companyId: $resolvedCompanyId,
            currency: $currency,
            date: $carbon,
            usesPerCurrencySequence: $usesPerCurrencySequence,
        );

        $startingNumber = max(1, (int) ($startingNumber ?? $this->getStartingNumber()));
        $nextNumber = max($lastNumber, $startingNumber - 1) + 1;

        $padLength = max(1, (int) ($padLength ?? $this->getPadLength()));
        $prefix = str_contains($format, '{prefix}')
            ? $this->getPrefix($resolvedCompanyId, $currency)
            : '';

        return $this->formatInvoiceNumber(
            format: $format,
            currency: $currency,
            prefix: $prefix,
            number: $nextNumber,
            year: $year,
            date: $carbon,
            padLength: $padLength,
        );
    }

    public function assignToInvoice(Invoice $invoice): void
    {
        $currency = $this->resolveCurrency($invoice->currency);
        $date = $this->normalizeDate($invoice->date);

        if (! $invoice->company_id) {
            throw new RuntimeException('Invoice company_id must be set before assigning an invoice number.');
        }

        $companyId = $invoice->company_id;

        $year = (int) $date->year;

        $format = $this->getFormat();
        $nextNumber = $this->getNextSequenceNumber(
            companyId: $companyId,
            currency: $currency,
            date: $date,
            format: $format,
            startingNumber: $this->getStartingNumber(),
        );

        $padLength = $this->getPadLength();
        $prefix = str_contains($format, '{prefix}')
            ? $this->getPrefix($companyId, $currency)
            : '';

        $invoice->currency = $currency;
        $invoice->sequence_number = $nextNumber;
        $invoice->sequence_year = $year;
        $invoice->invoice_number = $this->formatInvoiceNumber(
            format: $format,
            currency: $currency,
            prefix: $prefix,
            number: $nextNumber,
            year: $year,
            date: $date,
            padLength: $padLength,
        );
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

    private function getLastSequenceNumber(
        int $companyId,
        string $currency,
        Carbon $date,
        bool $usesPerCurrencySequence,
    ): int {
        $query = Invoice::query();

        $query->where('company_id', $companyId);

        if ($usesPerCurrencySequence) {
            $query->where('currency', $currency);
        }

        $query->whereYear('date', $date->year);

        return (int) $query->max('sequence_number');
    }

    private function getNextSequenceNumber(
        int $companyId,
        string $currency,
        Carbon $date,
        string $format,
        int $startingNumber,
    ): int {
        $lastNumber = $this->getLastSequenceNumber(
            companyId: $companyId,
            currency: $currency,
            date: $date,
            usesPerCurrencySequence: $this->usesPerCurrencySequenceForFormat($format),
        );

        $startingNumber = max(1, $startingNumber);

        return max($lastNumber, $startingNumber - 1) + 1;
    }

    private function usesPerCurrencySequenceForFormat(string $format): bool
    {
        return str_contains($format, '{currency}') || str_contains($format, '{prefix}');
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

    private function formatInvoiceNumber(
        string $format,
        string $currency,
        string $prefix,
        int $number,
        int $year,
        Carbon $date,
        int $padLength,
    ): string {
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
        $format = CompanySetting::get('invoice_numbering_format', '{prefix}-{number}/{year}');

        return is_string($format) && $format !== ''
            ? $format
            : '{prefix}-{number}/{year}';
    }

    private function getPrefix(int $companyId, string $currency): string
    {
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
        $defaultPadLength = CompanySetting::get('invoice_numbering_pad_length', 3);

        return max(1, (int) $defaultPadLength);
    }

    private function getStartingNumber(): int
    {
        $defaultStartingNumber = CompanySetting::get('invoice_numbering_start_number', 1);

        return max(1, (int) $defaultStartingNumber);
    }
}
