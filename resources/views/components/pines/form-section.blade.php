@props([
    'title' => null,
    'description' => null,
    'columns' => 1,
])

@php
$gridCols = [
    1 => 'grid-cols-1',
    2 => 'grid-cols-1 sm:grid-cols-2',
    3 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
    6 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6',
    12 => 'grid-cols-12',
];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white border border-neutral-200/60 rounded-lg shadow-sm']) }}>
    @if($title)
        <div class="px-6 py-4 border-b border-neutral-200/60">
            <h3 class="text-base font-semibold text-neutral-900">{{ $title }}</h3>
            @if($description)
                <p class="mt-1 text-sm text-neutral-500">{{ $description }}</p>
            @endif
        </div>
    @endif
    
    <div class="px-6 py-4">
        <div class="grid gap-4 {{ $gridCols[$columns] ?? $gridCols[1] }}">
            {{ $slot }}
        </div>
    </div>
</div>
