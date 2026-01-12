@props([
    'variant' => 'default',
    'size' => 'sm',
    'dot' => false,
    'pulse' => false,
])

@php
$variants = [
    'default' => 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300',
    'primary' => 'bg-violet-100 dark:bg-violet-900/50 text-violet-700 dark:text-violet-300',
    'success' => 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300',
    'warning' => 'bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300',
    'danger' => 'bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-300',
    'info' => 'bg-sky-100 dark:bg-sky-900/50 text-sky-700 dark:text-sky-300',
];

$dotColors = [
    'default' => 'bg-slate-500',
    'primary' => 'bg-violet-500',
    'success' => 'bg-emerald-500',
    'warning' => 'bg-amber-500',
    'danger' => 'bg-rose-500',
    'info' => 'bg-sky-500',
];

$sizes = [
    'xs' => 'px-1.5 py-0.5 text-[10px]',
    'sm' => 'px-2 py-0.5 text-[11px]',
    'md' => 'px-2.5 py-1 text-xs',
    'lg' => 'px-3 py-1.5 text-sm',
];

$classes = 'inline-flex items-center gap-1.5 font-semibold rounded-full ' . 
    ($variants[$variant] ?? $variants['default']) . ' ' . 
    ($sizes[$size] ?? $sizes['sm']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($dot)
        <span class="relative flex h-2 w-2">
            @if($pulse)
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $dotColors[$variant] ?? $dotColors['default'] }}"></span>
            @endif
            <span class="relative inline-flex rounded-full h-2 w-2 {{ $dotColors[$variant] ?? $dotColors['default'] }}"></span>
        </span>
    @endif
    {{ $slot }}
</span>
