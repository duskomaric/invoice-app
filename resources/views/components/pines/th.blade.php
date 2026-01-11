@props([
    'align' => 'left',
    'sortable' => false,
    'sortKey' => null,
    'sortDirection' => null,
])

@php
$alignClasses = [
    'left' => 'text-left',
    'center' => 'text-center',
    'right' => 'text-right',
];
@endphp

<th {{ $attributes->merge(['class' => 'px-5 py-3 text-xs font-medium uppercase text-neutral-500 ' . ($alignClasses[$align] ?? $alignClasses['left'])]) }}>
    @if($sortable && $sortKey)
        <a href="{{ request()->fullUrlWithQuery(['sort' => $sortKey, 'direction' => $sortDirection === 'asc' ? 'desc' : 'asc']) }}" class="inline-flex items-center gap-1 hover:text-neutral-700">
            {{ $slot }}
            @if(request('sort') === $sortKey)
                <svg class="w-4 h-4 {{ request('direction') === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
            @endif
        </a>
    @else
        {{ $slot }}
    @endif
</th>
