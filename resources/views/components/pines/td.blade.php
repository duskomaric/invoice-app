@props([
    'align' => 'left',
])

@php
$alignClasses = [
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
];
@endphp

<td {{ $attributes->merge(['class' => 'px-5 py-4 text-sm text-neutral-700 ' . ($alignClasses[$align] ?? $alignClasses['left'])]) }}>
    {{ $slot }}
</td>
