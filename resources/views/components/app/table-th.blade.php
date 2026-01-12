@props([
    'align' => 'left',
    'icon' => null,
])

@php
    $alignClasses = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
    ];
@endphp

<th {{ $attributes->merge(['class' => ($alignClasses[$align] ?? 'text-left') . ' text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-4 py-3']) }}>
    <div class="flex items-center gap-1.5 {{ $align === 'right' ? 'justify-end' : '' }}">
        @if($icon)
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-3.5 h-3.5" />
        @endif
        {{ $slot }}
    </div>
</th>
