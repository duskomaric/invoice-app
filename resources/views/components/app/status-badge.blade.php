@props([
    'status',
    'variant' => 'slate',
    'size' => 'sm',
    'dot' => false,
    'pulse' => false,
])

@php
    $variants = [
        'slate' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400',
        'emerald' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
        'rose' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300',
        'amber' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
        'blue' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
        'violet' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/50 dark:text-violet-300',
        // Support invoice5 aliases
        'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
        'danger' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300',
        'warning' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
        'primary' => 'bg-violet-100 text-violet-700 dark:bg-violet-900/50 dark:text-violet-300',
        'info' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
        'default' => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400',
    ];

    $dotColors = [
        'slate' => 'bg-slate-500',
        'emerald' => 'bg-emerald-500',
        'rose' => 'bg-rose-500',
        'amber' => 'bg-amber-500',
        'blue' => 'bg-blue-500',
        'violet' => 'bg-violet-500',
        'success' => 'bg-emerald-500',
        'danger' => 'bg-rose-500',
        'warning' => 'bg-amber-500',
        'primary' => 'bg-violet-500',
        'default' => 'bg-slate-500',
    ];

    $sizes = [
        'xs' => 'px-1.5 py-0.5 text-[10px]',
        'sm' => 'px-2 py-0.5 text-[11px]',
        'md' => 'px-2.5 py-1 text-xs',
    ];

    $classes = "inline-flex items-center gap-1.5 font-semibold rounded-full " . ($variants[$variant] ?? $variants['slate']) . " " . ($sizes[$size] ?? $sizes['sm']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($dot)
        <span class="relative flex h-2 w-2">
            @if($pulse)
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $dotColors[$variant] ?? $dotColors['slate'] }}"></span>
            @endif
            <span class="relative inline-flex rounded-full h-2 w-2 {{ $dotColors[$variant] ?? $dotColors['slate'] }}"></span>
        </span>
    @endif
    {{ $status }}
</span>
