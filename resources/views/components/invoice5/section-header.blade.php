@props([
    'title' => '',
    'subtitle' => null,
    'icon' => null,
    'variant' => 'primary', // primary, success, warning, danger, info, amber
])

@php
$iconBg = [
    'primary' => 'from-violet-500 to-fuchsia-600 shadow-violet-500/30',
    'success' => 'from-emerald-500 to-teal-600 shadow-emerald-500/30',
    'warning' => 'from-amber-500 to-orange-600 shadow-amber-500/30',
    'danger' => 'from-rose-500 to-pink-600 shadow-rose-500/30',
    'info' => 'from-sky-500 to-cyan-600 shadow-sky-500/30',
    'amber' => 'from-amber-400 to-orange-500 shadow-amber-500/30',
];

$bgClass = $iconBg[$variant] ?? $iconBg['primary'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3 mb-4']) }}>
    @if($icon)
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $bgClass }} flex items-center justify-center text-white shadow-lg">
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5" />
        </div>
    @endif
    <div>
        <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $title }}</h3>
        @if($subtitle)
            <p class="text-[11px] font-bold text-slate-600 dark:text-slate-400">{{ $subtitle }}</p>
        @endif
    </div>
</div>
