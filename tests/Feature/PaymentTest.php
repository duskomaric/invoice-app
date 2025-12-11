<?php

use App\Models\User;
use App\Models\Client;
use App\Models\Payment;
use App\Enums\RoleEnum;
use App\Models\Invoice;
use App\Services\PaymentService;
use App\Enums\InvoiceStatus;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create(['role' => RoleEnum::SuperAdmin]));
});

it('can create payment', function () {
    $client = Client::factory()->create();

    livewire(\App\Filament\Resources\Payments\Pages\CreatePayment::class)
        ->fillForm([
            'client_id' => $client->id,
            'amount' => 10000,
            'payment_date' => now()->format('Y-m-d'),
            'notes' => 'Test payment',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Payment::count())->toBe(1)
        ->and(Payment::first()->amount)->toBe(1000000); // 100.00 in input, MoneyInput * 100
});

it('calculates client balance correctly with negative initial balance', function () {
    $client = Client::factory()->create();
    
    // Initial debt
    Payment::factory()->create([
        'client_id' => $client->id,
        'amount' => -50000, // -500 KM debt
    ]);
    
    // Payment received
    Payment::factory()->create([
        'client_id' => $client->id,
        'amount' => 40000, // +400 KM payment
    ]);
    
    $totalPaid = $client->payments()->sum('amount');
    $balance = $totalPaid - 0; // No invoices
    
    expect($balance)->toBe(-10000); // Still owes 100 KM
});

it('allocates payments to invoices using FIFO', function () {
    $client = Client::factory()->create();
    
    // Create two invoices
    // Create two invoices manually to avoid factory overwriting totals
    $invoice1 = Invoice::create([
        'client_id' => $client->id,
        'date' => now()->subDays(2),
        'due_date' => now()->addDays(30),
        'status' => InvoiceStatus::Sent,
    ]);
    \App\Models\InvoiceItem::create([
        'invoice_id' => $invoice1->id,
        'description' => 'Item 1',
        'quantity' => 1,
        'unit_price' => 10000,
        'total' => 10000,
    ]);
    
    $invoice2 = Invoice::create([
        'client_id' => $client->id,
        'date' => now()->subDay(),
        'due_date' => now()->addDays(30),
        'status' => InvoiceStatus::Sent,
    ]);
    \App\Models\InvoiceItem::create([
        'invoice_id' => $invoice2->id,
        'description' => 'Item 2',
        'quantity' => 1,
        'unit_price' => 5000,
        'total' => 5000,
    ]);
    
    // Make a payment that partially covers both
    Payment::factory()->create([
        'client_id' => $client->id,
        'amount' => 12000,
    ]);
    
    $service = new PaymentService();
    $service->allocatePayments($client);
    
    $invoice1->refresh();
    $invoice2->refresh();
    
    expect($invoice1->amount_paid)->toBe(10000) // Fully paid
        ->and($invoice1->status)->toBe(InvoiceStatus::Paid)
        ->and($invoice2->amount_paid)->toBe(2000) // Partially paid
        ->and($invoice2->status)->toBe(InvoiceStatus::Partial);
});

it('marks invoices as paid when payment covers all debts', function () {
    $client = Client::factory()->create();
    
    $invoice = Invoice::create([
        'client_id' => $client->id,
        'date' => now(),
        'due_date' => now()->addDays(30),
        'status' => InvoiceStatus::Sent,
    ]);
    \App\Models\InvoiceItem::create([
        'invoice_id' => $invoice->id,
        'description' => 'Item',
        'quantity' => 1,
        'unit_price' => 10000,
        'total' => 10000,
    ]);
    
    Payment::factory()->create([
        'client_id' => $client->id,
        'amount' => 15000, // Overpayment
    ]);
    
    $service = new PaymentService();
    $service->allocatePayments($client);
    
    $invoice->refresh();
    
    expect($invoice->amount_paid)->toBe(10000)
        ->and($invoice->status)->toBe(InvoiceStatus::Paid);
});
