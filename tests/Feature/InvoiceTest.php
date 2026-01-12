<?php

use App\Enums\InvoiceStatusEnum;
use App\Enums\RoleEnum;
use App\Models\Article;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create(['role' => RoleEnum::SuperAdmin]));
});

it('can render invoice list page', function () {
    livewire(\App\Filament\Resources\Invoices\Pages\ListInvoices::class)
        ->assertSuccessful();
});

it('can create invoice with calculated totals', function () {
    $client = Client::factory()->create();
    $article = Article::factory()->create(['price' => 10000]); // 100.00

    livewire(\App\Filament\Resources\Invoices\Pages\CreateInvoice::class)
        ->fillForm([
            'client_id' => $client->id,
            'status' => InvoiceStatusEnum::Draft->value,
            'date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
        ])
        ->set('data.items', [
            [
                'article_id' => $article->id,
                'description' => 'Test item',
                'quantity' => 2,
                'unit_price' => 10000,
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $invoice = Invoice::latest()->first();

    expect($invoice)->not->toBeNull()
        ->and($invoice->client_id)->toBe($client->id)
        ->and($invoice->subtotal)->toBe(2000000) // 2 * 100.00, MoneyInput stores as cents * 100
        ->and($invoice->total)->toBe(2000000);
});

it('calculates invoice item totals correctly', function () {
    $client = Client::factory()->create();
    $article = Article::factory()->create(['price' => 5000]);

    livewire(\App\Filament\Resources\Invoices\Pages\CreateInvoice::class)
        ->fillForm([
            'client_id' => $client->id,
            'status' => InvoiceStatusEnum::Draft->value,
            'date' => now()->format('Y-m-d'),
        ])
        ->set('data.items', [
            [
                'article_id' => $article->id,
                'description' => 'Item 1',
                'quantity' => 3,
                'unit_price' => 5000,
            ],
            [
                'article_id' => $article->id,
                'description' => 'Item 2',
                'quantity' => 1,
                'unit_price' => 10000,
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $invoice = Invoice::latest()->first();
    $items = $invoice->items;

    expect($items)->toHaveCount(2)
        ->and($items[0]->total)->toBe(1500000) // 3 * 50.00, MoneyInput * 100
        ->and($items[1]->total)->toBe(1000000) // 1 * 100.00, MoneyInput * 100
        ->and($invoice->subtotal)->toBe(2500000)
        ->and($invoice->total)->toBe(2500000);
});

it('can edit invoice and recalculate totals', function () {
    $client = Client::factory()->create();
    $article = Article::factory()->create(['price' => 5000]);

    // Create invoice manually to avoid factory overwriting totals
    $invoice = Invoice::create([
        'client_id' => $client->id,
        'date' => now(),
        'due_date' => now()->addDays(30),
        'status' => InvoiceStatusEnum::Draft,
    ]);

    // Add items to invoice
    \App\Models\InvoiceItem::create([
        'invoice_id' => $invoice->id,
        'article_id' => $article->id, // Just to have a valid article
        'description' => 'Initial item',
        'quantity' => 2,
        'unit_price' => 5000,
        'total' => 10000,
    ]);

    livewire(\App\Filament\Resources\Invoices\Pages\EditInvoice::class, [
        'record' => $invoice->id,
    ])
        ->set('data.items', [
            [
                'article_id' => $article->id,
                'description' => 'Updated item',
                'quantity' => 5,
                'unit_price' => 3000,
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $invoice->refresh();

    expect($invoice->subtotal)->toBe(1500000) // 5 * 30.00, MoneyInput * 100
        ->and($invoice->total)->toBe(1500000);
});
