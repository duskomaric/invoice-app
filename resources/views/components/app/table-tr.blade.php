@props([
    'hover' => false,
])

<tr {{ $attributes->merge(['class' => 'group ' . ($hover ? 'hover:bg-violet-50/30 dark:hover:bg-violet-900/10 transition-colors' : '')]) }}>
    {{ $slot }}
</tr>
