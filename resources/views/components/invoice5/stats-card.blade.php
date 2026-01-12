@props([
    'title' => '',
    'value' => '',
    'icon' => null,
    'trend' => null,
    'trendUp' => true,
    'variant' => 'default',
])

@php
$iconBg = [
    'default' => 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300',
    'primary' => 'bg-gradient-to-br from-violet-500 to-purple-600 text-white shadow-md shadow-violet-500/25',
    'success' => 'bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/25',
    'warning' => 'bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-md shadow-amber-500/25',
    'danger' => 'bg-gradient-to-br from-rose-500 to-pink-600 text-white shadow-md shadow-rose-500/25',
    'info' => 'bg-gradient-to-br from-sky-500 to-cyan-600 text-white shadow-md shadow-sky-500/25',
];
@endphp

<x-invoice5.card hover class="group">
    <div class="flex items-start justify-between gap-3">
        <div class="flex-1 min-w-0">
            <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5">{{ $title }}</p>
            <p class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $value }}</p>
            @if($trend)
                <div class="flex items-center gap-1 mt-1.5">
                    @if($trendUp)
                        <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    @else
                        <svg class="w-3.5 h-3.5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                    @endif
                    <span class="text-[10px] font-semibold {{ $trendUp ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">{{ $trend }}</span>
                </div>
            @endif
        </div>
        @if($icon)
            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110 {{ $iconBg[$variant] ?? $iconBg['default'] }}">
                <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5" />
            </div>
        @endif
    </div>
    @if($slot->isNotEmpty())
        <div class="mt-2 pt-2 border-t border-slate-200 dark:border-slate-700">
            {{ $slot }}
        </div>
    @endif
</x-invoice5.card>
