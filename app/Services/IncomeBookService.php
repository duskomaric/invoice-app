<?php

namespace App\Services;

use App\Enums\ArticleTypeEnum;
use App\Enums\InvoiceStatusEnum;
use App\Enums\PaymentTypeEnum;
use App\Enums\ReviewStatusEnum;
use App\Models\Article;
use App\Models\IncomeBookEntry;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Proforma;
use App\Models\Quote;

class IncomeBookService
{
    public function distributePaymentToFifoInvoices(Payment $payment): array
    {
        if ($payment->type !== PaymentTypeEnum::INCOME) {
            return [];
        }

        $distributedEntries = [];
        $remainingAmount = $payment->amount;

        $unpaidInvoices = Invoice::where('client_id', $payment->client_id)
            ->where('company_id', $payment->company_id)
            ->whereIn('status', [InvoiceStatusEnum::Sent, InvoiceStatusEnum::Partial, InvoiceStatusEnum::Overdue])
            ->orderBy('date', 'asc')
            ->get();

        foreach ($unpaidInvoices as $invoice) {
            if ($remainingAmount <= 0) {
                break;
            }

            $invoiceBalance = $this->getInvoiceBalance($invoice);

            if ($invoiceBalance <= 0) {
                continue;
            }

            $distributionAmount = min($remainingAmount, $invoiceBalance);

            $incomeEntry = $this->createFromPaymentAndInvoice(
                $payment,
                $invoice,
                $distributionAmount
            );

            $distributedEntries[] = $incomeEntry;
            $remainingAmount -= $distributionAmount;

            $this->updateInvoiceStatus($invoice);
        }

        return $distributedEntries;
    }

    public function createFromPaymentAndInvoice(
        Payment $payment,
        Invoice $invoice,
        int $amount
    ): IncomeBookEntry {
        $percentage = $invoice->total > 0 ? $amount / $invoice->total : 0;

        $categorizedIncome = $this->categorizeInvoiceIncome($invoice, $percentage);

        return IncomeBookEntry::create([
            'company_id' => $payment->company_id,
            'booking_date' => $payment->payment_date,
            'description' => "Uplata po fakturi br. {$invoice->formatted_number}".
                            ($payment->document_number ? " - {$payment->document_number}" : ''),

            'income_products' => $categorizedIncome['products'],
            'income_goods' => $categorizedIncome['goods'],
            'income_services' => $categorizedIncome['services'],
            'income_other' => $categorizedIncome['other'],
            'income_financial' => $categorizedIncome['financial'],

            'total_income' => $amount,
            'vat_amount' => $categorizedIncome['vat'],

            'payment_id' => $payment->id,
            'invoice_id' => $invoice->id,
        ]);
    }

    public function createFromPaymentWithoutInvoice(Payment $payment, array $incomeData): IncomeBookEntry
    {
        return IncomeBookEntry::create([
            'company_id' => $payment->company_id,
            'booking_date' => $payment->payment_date,
            'description' => $incomeData['description'] ??
                            'Uplata bez fakture'.
                            ($payment->document_number ? " - {$payment->document_number}" : ''),

            'income_products' => $incomeData['income_products'] ?? 0,
            'income_goods' => $incomeData['income_goods'] ?? 0,
            'income_services' => $incomeData['income_services'] ?? 0,
            'income_other' => $incomeData['income_other'] ?? 0,
            'income_financial' => $incomeData['income_financial'] ?? 0,

            'total_income' => $payment->amount,
            'vat_amount' => $incomeData['vat_amount'] ?? 0,
            'review_status' => ReviewStatusEnum::PENDING,

            'payment_id' => $payment->id,
            'invoice_id' => null,
        ]);
    }

    public function createFromPaymentAndQuote(Payment $payment, Quote $quote, int $amount): IncomeBookEntry
    {
        $percentage = $quote->total > 0 ? $amount / $quote->total : 0;
        $categorizedIncome = $this->categorizeQuoteIncome($quote, $percentage);

        return IncomeBookEntry::create([
            'company_id' => $payment->company_id,
            'booking_date' => $payment->payment_date,
            'description' => "Uplata po ponudi br. {$quote->formatted_number}".
                            ($payment->document_number ? " - {$payment->document_number}" : ''),

            'income_products' => $categorizedIncome['products'],
            'income_goods' => $categorizedIncome['goods'],
            'income_services' => $categorizedIncome['services'],
            'income_other' => $categorizedIncome['other'],
            'income_financial' => $categorizedIncome['financial'],

            'total_income' => $amount,
            'vat_amount' => $categorizedIncome['vat'],
            'review_status' => ReviewStatusEnum::AUTO,

            'payment_id' => $payment->id,
            'quote_id' => $quote->id,
        ]);
    }

    public function createFromPaymentAndProforma(Payment $payment, Proforma $proforma, int $amount): IncomeBookEntry
    {
        $percentage = $proforma->total > 0 ? $amount / $proforma->total : 0;
        $categorizedIncome = $this->categorizeProformaIncome($proforma, $percentage);

        return IncomeBookEntry::create([
            'company_id' => $payment->company_id,
            'booking_date' => $payment->payment_date,
            'description' => "Uplata po predračunu br. {$proforma->formatted_number}".
                            ($payment->document_number ? " - {$payment->document_number}" : ''),

            'income_products' => $categorizedIncome['products'],
            'income_goods' => $categorizedIncome['goods'],
            'income_services' => $categorizedIncome['services'],
            'income_other' => $categorizedIncome['other'],
            'income_financial' => $categorizedIncome['financial'],

            'total_income' => $amount,
            'vat_amount' => $categorizedIncome['vat'],
            'review_status' => ReviewStatusEnum::AUTO,

            'payment_id' => $payment->id,
            'proforma_id' => $proforma->id,
        ]);
    }

    private function categorizeQuoteIncome(Quote $quote, float $percentage): array
    {
        $income = [
            'products' => 0,
            'goods' => 0,
            'services' => 0,
            'other' => 0,
            'financial' => 0,
            'vat' => 0,
        ];

        foreach ($quote->items as $item) {
            $itemTotal = $item->total;
            $taxRate = $item->tax_rate ?? 0;

            $itemNet = intval($itemTotal / (1 + ($taxRate / 100)));
            $itemVat = $itemTotal - $itemNet;

            $adjustedNet = intval($itemNet * $percentage);
            $adjustedVat = intval($itemVat * $percentage);

            $category = $this->getArticleCategory($item->article);
            $income[$category] += $adjustedNet;
            $income['vat'] += $adjustedVat;
        }

        return $income;
    }

    private function categorizeProformaIncome(Proforma $proforma, float $percentage): array
    {
        $income = [
            'products' => 0,
            'goods' => 0,
            'services' => 0,
            'other' => 0,
            'financial' => 0,
            'vat' => 0,
        ];

        foreach ($proforma->items as $item) {
            $itemTotal = $item->total;
            $taxRate = $item->tax_rate ?? 0;

            $itemNet = intval($itemTotal / (1 + ($taxRate / 100)));
            $itemVat = $itemTotal - $itemNet;

            $adjustedNet = intval($itemNet * $percentage);
            $adjustedVat = intval($itemVat * $percentage);

            $category = $this->getArticleCategory($item->article);
            $income[$category] += $adjustedNet;
            $income['vat'] += $adjustedVat;
        }

        return $income;
    }

    private function categorizeInvoiceIncome(Invoice $invoice, float $percentage): array
    {
        $income = [
            'products' => 0,
            'goods' => 0,
            'services' => 0,
            'other' => 0,
            'financial' => 0,
            'vat' => 0,
        ];

        foreach ($invoice->items as $item) {
            $itemTotal = $item->total;
            $taxRate = $item->tax_rate ?? 0;

            $itemNet = intval($itemTotal / (1 + ($taxRate / 100)));
            $itemVat = $itemTotal - $itemNet;

            $adjustedNet = intval($itemNet * $percentage);
            $adjustedVat = intval($itemVat * $percentage);

            $category = $this->getArticleCategory($item->article);
            $income[$category] += $adjustedNet;
            $income['vat'] += $adjustedVat;
        }

        return $income;
    }

    private function getArticleCategory(?Article $article): string
    {
        if (! $article || ! $article->type) {
            return 'other';
        }

        return match ($article->type) {
            ArticleTypeEnum::GOODS => 'goods',
            ArticleTypeEnum::SERVICES => 'services',
            ArticleTypeEnum::PRODUCTS => 'products',
            default => 'other'
        };
    }

    public function getInvoiceBalance(Invoice $invoice): int
    {
        $paidAmount = IncomeBookEntry::where('invoice_id', $invoice->id)
            ->sum('total_income');

        return $invoice->total - $paidAmount;
    }

    private function updateInvoiceStatus(Invoice $invoice): void
    {
        $balance = $this->getInvoiceBalance($invoice);

        if ($balance <= 0) {
            $invoice->update(['status' => InvoiceStatusEnum::Paid]);
        } elseif ($balance < $invoice->total) {
            $invoice->update(['status' => InvoiceStatusEnum::Partial]);
        }
    }

    public function getClientUnpaidInvoices(int $clientId, int $companyId): array
    {
        return Invoice::where('client_id', $clientId)
            ->where('company_id', $companyId)
            ->whereIn('status', [InvoiceStatusEnum::Sent, InvoiceStatusEnum::Partial, InvoiceStatusEnum::Overdue])
            ->with('items.article')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($invoice) {
                $balance = $this->getInvoiceBalance($invoice);

                return [
                    'id' => $invoice->id,
                    'number' => $invoice->formatted_number,
                    'date' => $invoice->date,
                    'due_date' => $invoice->due_date,
                    'total' => $invoice->total,
                    'balance' => $balance,
                    'days_overdue' => $invoice->due_date < now() ? now()->diffInDays($invoice->due_date) : 0,
                ];
            })
            ->toArray();
    }

    public function getPaymentDistributionHistory(Payment $payment): array
    {
        return $payment->incomeBookEntries()
            ->with('invoice')
            ->get()
            ->map(function ($entry) {
                return [
                    'invoice_number' => $entry->invoice?->formatted_number,
                    'amount' => $entry->total_income,
                    'booking_date' => $entry->booking_date,
                    'description' => $entry->description,
                ];
            })
            ->toArray();
    }
}
