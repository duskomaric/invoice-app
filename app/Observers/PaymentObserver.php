<?php

namespace App\Observers;

use App\Enums\PaymentTypeEnum;
use App\Models\Payment;
use App\Services\IncomeBookService;

class PaymentObserver
{
    public function __construct(
        private IncomeBookService $incomeBookService
    ) {}

    public function created(Payment $payment): void
    {
        \Log::info('PaymentObserver created called', [
            'payment_id' => $payment->id,
            'type' => $payment->type?->value,
            'company_id' => $payment->company_id,
            'is_small_business' => $payment->company?->is_small_business,
        ]);

        if ($payment->type !== PaymentTypeEnum::INCOME) {
            \Log::info('Payment is not INCOME type, skipping');
            return;
        }

        if (!$payment->company) {
            \Log::warning('Payment has no company, skipping');
            return;
        }

//        if (!$payment->company->is_small_business) {
//            \Log::info('Company is not small business, skipping');
//            return;
//        }

        \Log::info('Creating IncomeBookEntry', [
            'invoice_id' => $payment->invoice_id,
            'quote_id' => $payment->quote_id,
            'proforma_id' => $payment->proforma_id,
            'amount' => $payment->amount,
        ]);

        if ($payment->invoice_id) {
            $entry = $this->incomeBookService->createFromPaymentAndInvoice(
                $payment,
                $payment->invoice,
                $payment->amount
            );
            \Log::info('IncomeBookEntry created from invoice', ['entry_id' => $entry->id]);
        } elseif ($payment->quote_id) {
            $entry = $this->incomeBookService->createFromPaymentAndQuote(
                $payment,
                $payment->quote,
                $payment->amount
            );
            \Log::info('IncomeBookEntry created from quote', ['entry_id' => $entry->id]);
        } elseif ($payment->proforma_id) {
            $entry = $this->incomeBookService->createFromPaymentAndProforma(
                $payment,
                $payment->proforma,
                $payment->amount
            );
            \Log::info('IncomeBookEntry created from proforma', ['entry_id' => $entry->id]);
        } else {
            $entries = $this->incomeBookService->distributePaymentToFifoInvoices($payment);

            if (empty($entries)) {
                \Log::info('No unpaid invoices found, creating manual entry with PENDING review status');
                $entry = $this->incomeBookService->createFromPaymentWithoutInvoice($payment, [
                    'description' => 'Uplata bez dokumenta - potreban ručni pregled',
                    'income_services' => $payment->amount,
                    'vat_amount' => 0,
                ]);
                \Log::info('Manual IncomeBookEntry created with PENDING status', ['entry_id' => $entry->id]);
            } else {
                \Log::info('IncomeBookEntries created via FIFO', ['count' => count($entries)]);
            }
        }
    }
}
