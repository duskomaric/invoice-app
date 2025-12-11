<?php

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;

it('has invoices relationship', function () {
    $client = Client::factory()->create();
    $invoice = Invoice::factory()->create(['client_id' => $client->id]);

    expect($client->invoices->contains($invoice))->toBeTrue();
});

it('has payments relationship', function () {
    $client = Client::factory()->create();
    $payment = Payment::factory()->create(['client_id' => $client->id]);

    expect($client->payments->contains($payment))->toBeTrue();
});

it('calculates balance correctly', function () {
    $client = Client::factory()->create();

    // Create invoices totaling 50,000 cents
    // Create directly to avoid factory's afterCreating hook
    $invoice1 = Invoice::create([
        'client_id' => $client->id,
        'status' => \App\Enums\InvoiceStatus::Sent,
        'date' => now(),
        'due_date' => now()->addDays(30),
        'is_recurring' => false,
    ]);
    $invoice1->items()->create([
        'description' => 'Item 1',
        'quantity' => 1,
        'unit_price' => 30000,
        'total' => 30000,
    ]);

    $invoice2 = Invoice::create([
        'client_id' => $client->id,
        'status' => \App\Enums\InvoiceStatus::Sent,
        'date' => now(),
        'due_date' => now()->addDays(30),
        'is_recurring' => false,
    ]);
    $invoice2->items()->create([
        'description' => 'Item 2',
        'quantity' => 1,
        'unit_price' => 20000,
        'total' => 20000,
    ]);

    // Create payments totaling 35,000 cents
    Payment::factory()->create([
        'client_id' => $client->id,
        'amount' => 20000,
    ]);

    Payment::factory()->create([
        'client_id' => $client->id,
        'amount' => 15000,
    ]);

    // Balance = Total Invoiced - Total Paid = 50000 - 35000 = 15000 (client owes)
    expect($client->balance)->toBe(15000);
});

it('returns zero balance with no invoices or payments', function () {
    $client = Client::factory()->create();

    expect($client->balance)->toBe(0);
});

it('calculates negative balance for overpayment', function () {
    $client = Client::factory()->create();

    $invoice = Invoice::create([
        'client_id' => $client->id,
        'status' => \App\Enums\InvoiceStatus::Sent,
        'date' => now(),
        'due_date' => now()->addDays(30),
        'is_recurring' => false,
    ]);
    $invoice->items()->create([
        'description' => 'Item',
        'quantity' => 1,
        'unit_price' => 10000,
        'total' => 10000,
    ]);

    Payment::factory()->create([
        'client_id' => $client->id,
        'amount' => 20000,
    ]);

    // Balance = 10000 - 20000 = -10000 (negative means client overpaid/has credit)
    expect($client->balance)->toBe(-10000);
});

it('creates valid client via factory', function () {
    $client = Client::factory()->create([
        'name' => 'Test Client',
        'email' => 'test@example.com',
    ]);

    $this->assertDatabaseHas('clients', [
        'name' => 'Test Client',
        'email' => 'test@example.com',
    ]);
});
