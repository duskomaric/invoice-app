<?php

use App\Models\Client;
use App\Models\Payment;

it('has client relationship', function () {
    $client = Client::factory()->create();
    $payment = Payment::factory()->create(['client_id' => $client->id]);

    expect($payment->client->id)->toBe($client->id);
});

it('creates valid payment via factory', function () {
    $client = Client::factory()->create();
    $payment = Payment::factory()->create([
        'client_id' => $client->id,
        'amount' => 10000,
        'payment_date' => now(),
    ]);

    $this->assertDatabaseHas('payments', [
        'client_id' => $client->id,
        'amount' => 10000,
    ]);
});
