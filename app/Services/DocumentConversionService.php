<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Proforma;
use App\Models\Quote;

class DocumentConversionService
{
    public function convertQuoteToProforma(Quote $quote): Proforma
    {
        $proforma = Proforma::create([
            'company_id' => $quote->company_id,
            'client_id' => $quote->client_id,
            'status' => 'draft',
            'language' => $quote->language,
            'date' => now(),
            'due_date' => now()->addDays(30),
            'notes' => $quote->notes,
            'currency' => $quote->currency,
            'sourceable_type' => Quote::class,
            'sourceable_id' => $quote->id,
        ]);

        foreach ($quote->items as $quoteItem) {
            $proforma->items()->create([
                'article_id' => $quoteItem->article_id,
                'name' => $quoteItem->name,
                'description' => $quoteItem->description,
                'quantity' => $quoteItem->quantity,
                'unit_price' => $quoteItem->unit_price,
                'total' => $quoteItem->total,
                'tax_rate' => $quoteItem->tax_rate,
            ]);
        }

        return $proforma;
    }

    public function convertProformaToInvoice(Proforma $proforma): Invoice
    {
        $invoice = Invoice::create([
            'company_id' => $proforma->company_id,
            'client_id' => $proforma->client_id,
            'status' => 'draft',
            'language' => $proforma->language,
            'date' => now(),
            'due_date' => $proforma->due_date ?? now()->addDays(30),
            'notes' => $proforma->notes,
            'currency' => $proforma->currency,
            'sourceable_type' => Proforma::class,
            'sourceable_id' => $proforma->id,
        ]);

        foreach ($proforma->items as $proformaItem) {
            $invoice->items()->create([
                'article_id' => $proformaItem->article_id,
                'name' => $proformaItem->name,
                'description' => $proformaItem->description,
                'quantity' => $proformaItem->quantity,
                'unit_price' => $proformaItem->unit_price,
                'total' => $proformaItem->total,
                'tax_rate' => $proformaItem->tax_rate,
            ]);
        }

        return $invoice;
    }

    public function convertQuoteToInvoice(Quote $quote): Invoice
    {
        $invoice = Invoice::create([
            'company_id' => $quote->company_id,
            'client_id' => $quote->client_id,
            'status' => 'draft',
            'language' => $quote->language,
            'date' => now(),
            'due_date' => now()->addDays(30),
            'notes' => $quote->notes,
            'currency' => $quote->currency,
            'sourceable_type' => Quote::class,
            'sourceable_id' => $quote->id,
        ]);

        foreach ($quote->items as $quoteItem) {
            $invoice->items()->create([
                'article_id' => $quoteItem->article_id,
                'name' => $quoteItem->name,
                'description' => $quoteItem->description,
                'quantity' => $quoteItem->quantity,
                'unit_price' => $quoteItem->unit_price,
                'total' => $quoteItem->total,
                'tax_rate' => $quoteItem->tax_rate,
            ]);
        }

        return $invoice;
    }

    public function convertContractToInvoice(Contract $contract): Invoice
    {
        $invoice = Invoice::create([
            'company_id' => $contract->company_id,
            'client_id' => $contract->client_id,
            'status' => 'draft',
            'language' => $contract->language,
            'date' => now(),
            'due_date' => now()->addDays(30),
            'notes' => $contract->notes,
            'currency' => $contract->currency,
            'sourceable_type' => Contract::class,
            'sourceable_id' => $contract->id,
        ]);

        foreach ($contract->items as $contractItem) {
            $invoice->items()->create([
                'article_id' => $contractItem->article_id,
                'name' => $contractItem->name,
                'description' => $contractItem->description,
                'quantity' => $contractItem->quantity,
                'unit_price' => $contractItem->unit_price,
                'total' => $contractItem->total,
                'tax_rate' => $contractItem->tax_rate,
            ]);
        }

        return $invoice;
    }
}
