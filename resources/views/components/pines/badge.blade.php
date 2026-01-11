@props([
    'variant' => 'default',
    'size' => 'md',
])

@php
$variants = [
    'default' => 'bg-neutral-100 text-neutral-700',
    'primary' => 'bg-blue-100 text-blue-700',
    'success' => 'bg-green-100 text-green-700',
    'warning' => 'bg-yellow-100 text-yellow-700',
    'danger' => 'bg-red-100 text-red-700',
    'info' => 'bg-cyan-100 text-cyan-700',
];

$sizes = [
    'sm' => 'px-1.5 py-0.5 text-xs',
    'md' => 'px-2 py-1 text-xs',
    'lg' => 'px-2.5 py-1 text-sm',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center font-medium rounded-full ' . ($variants[$variant] ?? $variants['default']) . ' ' . ($sizes[$size] ?? $sizes['md'])]) }}>
    {{ $slot }}
</span>
