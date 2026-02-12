<?php

use App\Enums\InvoiceStatusEnum;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceEmailLog;

it('has client relationship', function () {
    $client = Client::factory()->create();
    $invoice = Invoice::factory()->create(['client_id' => $client->id]);

    expect($invoice->client->id)->toBe($client->id);
});

it('has items relationship', function () {
    $invoice = Invoice::factory()->create();
    $item = $invoice->items()->create([
        'description' => 'Test Item',
        'quantity' => 1,
        'unit_price' => 1000,
        'total' => 1000,
    ]);

    expect($invoice->items->contains($item))->toBeTrue();
});

it('has parent relationship', function () {
    $parent = Invoice::create([
        'client_id' => Client::factory()->create()->id,
        'status' => InvoiceStatusEnum::Draft,
        'date' => now(),
        'due_date' => now()->addDays(30),
        'is_recurring' => false,
    ]);

    $child = Invoice::create([
        'client_id' => $parent->client_id,
        'status' => InvoiceStatusEnum::Draft,
        'date' => now(),
        'due_date' => now()->addDays(30),
        'is_recurring' => false,
        'parent_id' => $parent->id,
    ]);

    expect($child->parent->id)->toBe($parent->id);
});

it('has children relationship', function () {
    $parent = Invoice::create([
        'client_id' => Client::factory()->create()->id,
        'status' => InvoiceStatusEnum::Draft,
        'date' => now(),
        'due_date' => now()->addDays(30),
        'is_recurring' => false,
    ]);

    $child = Invoice::create([
        'client_id' => $parent->client_id,
        'status' => InvoiceStatusEnum::Draft,
        'date' => now(),
        'due_date' => now()->addDays(30),
        'is_recurring' => false,
        'parent_id' => $parent->id,
    ]);

    expect($parent->children->contains($child))->toBeTrue();
});

it('has email logs relationship', function () {
    $invoice = Invoice::create([
        'client_id' => Client::factory()->create()->id,
        'status' => InvoiceStatusEnum::Draft,
        'date' => now(),
        'due_date' => now()->addDays(30),
        'is_recurring' => false,
    ]);

    $emailLog = InvoiceEmailLog::create([
        'invoice_id' => $invoice->id,
    ]);

    expect($invoice->emailLogs->contains($emailLog))->toBeTrue();
});

it('calculates subtotal as sum of items', function () {
    $invoice = Invoice::create([
        'client_id' => Client::factory()->create()->id,
        'status' => InvoiceStatusEnum::Draft,
        'date' => now(),
        'due_date' => now()->addDays(30),
        'is_recurring' => false,
    ]);

    $invoice->items()->create([
        'description' => 'Item 1',
        'quantity' => 2,
        'unit_price' => 1000,
        'total' => 2000,
    ]);

    $invoice->items()->create([
        'description' => 'Item 2',
        'quantity' => 1,
        'unit_price' => 3000,
        'total' => 3000,
    ]);

    expect($invoice->subtotal)->toBe(5000);
});

it('returns zero for tax', function () {
    $invoice = Invoice::factory()->create();

    expect($invoice->tax)->toBe(0);
});

it('calculates total as subtotal plus tax', function () {
    $invoice = Invoice::create([
        'client_id' => Client::factory()->create()->id,
        'status' => InvoiceStatusEnum::Draft,
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

    // Total = subtotal (10000) + tax (0) = 10000
    expect($invoice->total)->toBe(10000);
});

it('creates valid invoice via factory', function () {
    $client = Client::factory()->create();
    $invoice = Invoice::factory()->create([
        'client_id' => $client->id,
        'status' => InvoiceStatusEnum::Sent,
    ]);

    $this->assertDatabaseHas('invoices', [
        'client_id' => $client->id,
        'status' => InvoiceStatusEnum::Sent->value,
    ]);
});
