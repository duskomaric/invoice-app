<?php

namespace App\Console\Commands;

use App\Enums\InvoiceFrequencyEnum;
use App\Models\Invoice;
use Illuminate\Console\Command;

class GenerateRecurringInvoicesCommand extends Command
{
    protected $signature = 'invoices:generate-recurring';

    protected $description = 'Generate invoices from recurring templates';

    public function handle(): void
    {
        $recurringInvoices = Invoice::where('is_recurring', true)
            ->where('next_invoice_date', '<=', now())
            ->get();

        foreach ($recurringInvoices as $invoice) {
            // Logic to clone invoice and create new one
            // This is a simplified example
            $newInvoice = $invoice->replicate(['is_recurring', 'frequency', 'next_invoice_date', 'parent_id', 'status', 'date', 'due_date']);
            $newInvoice->parent_id = $invoice->id;
            $newInvoice->status = \App\Enums\InvoiceStatusEnum::Draft;
            $newInvoice->date = now();
            $newInvoice->due_date = now()->addDays(30);
            $newInvoice->save();

            // Replicate items
            foreach ($invoice->items as $item) {
                $newInvoice->items()->create($item->toArray());
            }

            // Update next date on parent
            $invoice->next_invoice_date = match ($invoice->frequency) {
                InvoiceFrequencyEnum::Weekly => $invoice->next_invoice_date->addWeek(),
                InvoiceFrequencyEnum::Monthly => $invoice->next_invoice_date->addMonth(),
                InvoiceFrequencyEnum::Quarterly => $invoice->next_invoice_date->addQuarter(),
                InvoiceFrequencyEnum::Yearly => $invoice->next_invoice_date->addYear(),
                default => $invoice->next_invoice_date->addMonth(),
            };
            $invoice->save();

            $this->info("Generated invoice for client: {$invoice->client->name}");
        }
    }
}
