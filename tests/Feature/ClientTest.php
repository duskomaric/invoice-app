<?php

use App\Enums\RoleEnum;
use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create(['role' => RoleEnum::SuperAdmin]));
});

it('can render client list page', function () {
    livewire(\App\Filament\Resources\Clients\Pages\ListClients::class)
        ->assertSuccessful();
});

it('can create client', function () {
    livewire(\App\Filament\Resources\Clients\Pages\CreateClient::class)
        ->fillForm([
            'name' => 'Test Client',
            'email' => 'test@example.com',
            'phone' => '123456789',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Client::count())->toBe(1)
        ->and(Client::first()->name)->toBe('Test Client');
});

it('displays client balance in table', function () {
    $client = Client::factory()->create();

    // Invoiced: 10000 (Sent) + 5000 (Draft) = 15000 Total, but Draft should be ignored for balance
    $invoice1 = Invoice::create([
        'client_id' => $client->id,
        'status' => \App\Enums\InvoiceStatusEnum::Sent,
        'date' => now(),
        'due_date' => now(),
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
        'status' => \App\Enums\InvoiceStatusEnum::Draft,
        'date' => now(),
        'due_date' => now(),
    ]);
    \App\Models\InvoiceItem::create([
        'invoice_id' => $invoice2->id,
        'description' => 'Item 2',
        'quantity' => 1,
        'unit_price' => 5000,
        'total' => 5000,
    ]);

    // Paid: 6000
    Payment::factory()->create([
        'client_id' => $client->id,
        'amount' => 6000,
    ]);

    // Expected Balance: 10000 (Sent) - 6000 (Paid) = 4000
    expect($client->balance)->toBe(4000);

    livewire(\App\Filament\Resources\Clients\Pages\ListClients::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$client]);
});

it('can view client with financial overview', function () {
    $client = Client::factory()->create();

    $invoice = Invoice::factory()->create([
        'client_id' => $client->id,
    ]);
    \App\Models\InvoiceItem::factory()->create([
        'invoice_id' => $invoice->id,
        'total' => 15000,
    ]);

    Payment::factory()->create([
        'client_id' => $client->id,
        'amount' => 10000,
    ]);

    livewire(\App\Filament\Resources\Clients\Pages\ViewClient::class, [
        'record' => $client->id,
    ])
        ->assertSuccessful();
});
