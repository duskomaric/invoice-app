@props([
    'label' => 'Select',
    'icon' => null,
    'align' => 'left',
    'width' => '48',
])

@php
$alignClasses = [
    'left' => 'left-0',
    'right' => 'right-0',
];
$widthClasses = [
    '48' => 'w-48',
    '56' => 'w-56',
    '64' => 'w-64',
    'full' => 'w-full',
];
@endphp

<div x-data="{ open: false }" class="relative" @click.away="open = false">
    <button 
        @click="open = !open" 
        type="button"
        {{ $attributes->merge([
            'class' => 'h-9 px-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-xs text-slate-700 dark:text-slate-200 flex items-center gap-2 hover:border-violet-400 dark:hover:border-violet-500/50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-sm'
        ]) }}
    >
        @if($icon)
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" />
        @endif
        <span>{{ $label }}</span>
        @isset($count)
            <span class="w-4 h-4 rounded-full bg-violet-500 text-white text-[9px] flex items-center justify-center font-bold">{{ $count }}</span>
        @endisset
        <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
        class="absolute z-[100] mt-1.5 {{ $alignClasses[$align] ?? $alignClasses['left'] }} {{ $widthClasses[$width] ?? $widthClasses['48'] }} bg-white dark:bg-slate-800 rounded-lg shadow-xl shadow-slate-900/15 dark:shadow-black/40 border border-slate-200 dark:border-slate-700 py-1 overflow-hidden"
        x-cloak
    >
        {{ $slot }}
    </div>
</div>
