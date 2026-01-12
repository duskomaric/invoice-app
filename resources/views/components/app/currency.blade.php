@props([
    'amount',
    'currency' => 'EUR',
    'cents' => true,
])

@php
    $symbols = [
        'EUR' => '€',
        'USD' => '$',
        'GBP' => '£',
    ];
    $symbol = $symbols[$currency] ?? $currency;
    $value = $cents ? $amount / 100 : $amount;
@endphp

<span {{ $attributes }}>{{ $symbol }}{{ number_format($value, 2) }}</span>
