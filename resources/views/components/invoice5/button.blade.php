@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'href' => null,
])

@php
$baseClasses = 'inline-flex items-center justify-center font-semibold transition-all duration-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-900';

$variants = [
    'primary' => 'bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 text-white shadow-lg shadow-violet-500/30 hover:shadow-xl hover:shadow-violet-500/40 hover:scale-[1.02] focus:ring-violet-500',
    'secondary' => 'bg-white/80 dark:bg-slate-700/80 backdrop-blur-sm border border-slate-200/60 dark:border-slate-600/60 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600/80 hover:border-slate-300 dark:hover:border-slate-500 focus:ring-slate-400',
    'ghost' => 'text-slate-600 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-700/50 focus:ring-slate-400',
    'danger' => 'bg-gradient-to-r from-rose-500 to-pink-600 text-white shadow-lg shadow-rose-500/30 hover:shadow-xl hover:shadow-rose-500/40 hover:scale-[1.02] focus:ring-rose-500',
    'success' => 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 hover:scale-[1.02] focus:ring-emerald-500',
];

$sizes = [
    'xs' => 'h-7 px-2.5 text-[11px] gap-1',
    'sm' => 'h-8 px-3 text-xs gap-1.5',
    'md' => 'h-9 px-4 text-xs gap-2',
    'lg' => 'h-10 px-5 text-sm gap-2',
    'xl' => 'h-12 px-6 text-base gap-2.5',
];

$classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon && $iconPosition === 'left')
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-4 h-4" />
    @endif
    {{ $slot }}
    @if($icon && $iconPosition === 'right')
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-4 h-4" />
    @endif
</a>
@else
<button {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }}>
    @if($icon && $iconPosition === 'left')
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-4 h-4" />
    @endif
    {{ $slot }}
    @if($icon && $iconPosition === 'right')
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-4 h-4" />
    @endif
</button>
@endif
