@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'disabled' => false,
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium transition-colors rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none';

$variants = [
    'primary' => 'text-white bg-neutral-950 hover:bg-neutral-800 focus:ring-neutral-900',
    'secondary' => 'text-neutral-700 bg-white border border-neutral-200 hover:bg-neutral-50 focus:ring-neutral-400',
    'danger' => 'text-white bg-red-600 hover:bg-red-700 focus:ring-red-500',
    'ghost' => 'text-neutral-600 hover:bg-neutral-100 focus:ring-neutral-400',
    'link' => 'text-blue-600 hover:text-blue-700 hover:underline focus:ring-blue-400',
];

$sizes = [
    'xs' => 'px-2 py-1 text-xs',
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-5 py-2.5 text-base',
];

$classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes, 'disabled' => $disabled]) }}>{{ $slot }}</button>
@endif
