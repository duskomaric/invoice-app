<?php

use App\Models\Article;
use App\Models\Invoice;
use App\Models\InvoiceItem;

it('has invoice relationship', function () {
    $invoice = Invoice::factory()->create();
    $item = $invoice->items()->create([
        'description' => 'Test Item',
        'quantity' => 1,
        'unit_price' => 1000,
        'total' => 1000,
    ]);

    expect($item->invoice->id)->toBe($invoice->id);
});

it('has article relationship', function () {
    $article = Article::factory()->create();
    $invoice = Invoice::factory()->create();
    
    $item = $invoice->items()->create([
        'article_id' => $article->id,
        'description' => 'Test Item',
        'quantity' => 1,
        'unit_price' => 1000,
        'total' => 1000,
    ]);

    expect($item->article->id)->toBe($article->id);
});

it('creates valid invoice item via factory', function () {
    $invoice = Invoice::factory()->create();
    $item = InvoiceItem::factory()->create([
        'invoice_id' => $invoice->id,
        'description' => 'Test Item',
        'quantity' => 2,
        'unit_price' => 1500,
        'total' => 3000,
    ]);

    $this->assertDatabaseHas('invoice_items', [
        'invoice_id' => $invoice->id,
        'description' => 'Test Item',
        'quantity' => 2,
        'unit_price' => 1500,
        'total' => 3000,
    ]);
});
