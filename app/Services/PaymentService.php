<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\InvoiceStatusEnum;
use App\Models\Client;

class PaymentService
{
    public function __construct(
        protected InvoiceService $invoiceService = new InvoiceService,
    ) {}

    public function allocatePayments(Client $client): void
    {
        // 1. Calculate Total Available Funds (Sum of all payments, including negative "initial balances")
        $remainingPayment = $client->payments()->sum('amount');

        // If net balance is negative or zero, all invoices are unpaid
        if ($remainingPayment <= 0) {
            $this->markAllInvoicesAsUnpaid($client);

            return;
        }

        // 3. Distribute Remaining Payment to Invoices (FIFO)
        $invoices = $client->invoices()
            ->where('status', '!=', InvoiceStatusEnum::Draft) // Only consider finalized invoices
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        foreach ($invoices as $invoice) {
            if ($remainingPayment <= 0) {
                // No more money, mark as unpaid (or reset to 0 paid)
                $this->invoiceService->updateStatus($invoice, 0);

                continue;
            }

            $invoiceTotal = $invoice->total;

            if ($remainingPayment >= $invoiceTotal) {
                // Fully paid
                $this->invoiceService->updateStatus($invoice, $invoiceTotal);
                $remainingPayment -= $invoiceTotal;
            } else {
                // Partially paid
                $this->invoiceService->updateStatus($invoice, (int) $remainingPayment);
                $remainingPayment = 0;
            }
        }
    }

    private function markAllInvoicesAsUnpaid(Client $client): void
    {
        $invoices = $client->invoices()
            ->where('status', '!=', InvoiceStatusEnum::Draft)
            ->get();

        foreach ($invoices as $invoice) {
            $this->invoiceService->updateStatus($invoice, 0);
        }
    }
}
