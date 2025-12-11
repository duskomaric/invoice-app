<?php

namespace Tests\Unit\Services;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_invoice_status_to_paid_when_fully_paid(): void
    {
        $invoice = Invoice::create([
            'client_id' => \App\Models\Client::factory()->create()->id,
            'amount_paid' => 0,
            'status' => InvoiceStatus::Sent,
            'date' => now(),
            'due_date' => now(),
        ]);
        \App\Models\InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => 'Test Item',
            'quantity' => 1,
            'unit_price' => 10000,
            'total' => 10000,
        ]);

        $service = new InvoiceService();
        $service->updateStatus($invoice, 10000);

        $this->assertEquals(10000, $invoice->amount_paid);
        $this->assertEquals(InvoiceStatus::Paid, $invoice->status);
    }

    public function test_it_updates_invoice_status_to_partial_when_partially_paid(): void
    {
        $invoice = Invoice::create([
            'client_id' => \App\Models\Client::factory()->create()->id,
            'amount_paid' => 0,
            'status' => InvoiceStatus::Sent,
            'date' => now(),
            'due_date' => now(),
        ]);
        \App\Models\InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => 'Test Item',
            'quantity' => 1,
            'unit_price' => 10000,
            'total' => 10000,
        ]);

        $service = new InvoiceService();
        $service->updateStatus($invoice, 5000);

        $this->assertEquals(5000, $invoice->amount_paid);
        $this->assertEquals(InvoiceStatus::Partial, $invoice->status);
    }

    public function test_it_updates_invoice_status_to_sent_when_unpaid_and_not_overdue(): void
    {
        $invoice = Invoice::create([
            'client_id' => \App\Models\Client::factory()->create()->id,
            'amount_paid' => 5000,
            'status' => InvoiceStatus::Partial,
            'date' => now(),
            'due_date' => now()->addDays(10),
        ]);
        \App\Models\InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => 'Test Item',
            'quantity' => 1,
            'unit_price' => 10000,
            'total' => 10000,
        ]);

        $service = new InvoiceService();
        $service->updateStatus($invoice, 0);

        $this->assertEquals(0, $invoice->amount_paid);
        $this->assertEquals(InvoiceStatus::Sent, $invoice->status);
    }

    public function test_it_updates_invoice_status_to_overdue_when_unpaid_and_overdue(): void
    {
        $invoice = Invoice::create([
            'client_id' => \App\Models\Client::factory()->create()->id,
            'amount_paid' => 5000,
            'status' => InvoiceStatus::Partial,
            'date' => now(),
            'due_date' => now()->subDays(1),
        ]);
        \App\Models\InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => 'Test Item',
            'quantity' => 1,
            'unit_price' => 10000,
            'total' => 10000,
        ]);

        $service = new InvoiceService();
        $service->updateStatus($invoice, 0);

        $this->assertEquals(0, $invoice->amount_paid);
        $this->assertEquals(InvoiceStatus::Overdue, $invoice->status);
    }
}
