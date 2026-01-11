@props([
    'name' => '',
    'image' => null,
    'size' => 'md',
    'variant' => 'gradient',
])

@php
$sizes = [
    'xs' => 'w-6 h-6 text-[9px]',
    'sm' => 'w-8 h-8 text-[10px]',
    'md' => 'w-10 h-10 text-xs',
    'lg' => 'w-12 h-12 text-sm',
    'xl' => 'w-14 h-14 text-base',
];

$variants = [
    'gradient' => 'bg-gradient-to-br from-violet-500 via-purple-500 to-fuchsia-500 text-white shadow-lg shadow-violet-500/30',
    'slate' => 'bg-slate-200 dark:bg-slate-600 text-slate-600 dark:text-slate-300',
    'emerald' => 'bg-gradient-to-br from-emerald-500 to-teal-500 text-white shadow-lg shadow-emerald-500/30',
    'amber' => 'bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-lg shadow-amber-500/30',
    'rose' => 'bg-gradient-to-br from-rose-500 to-pink-500 text-white shadow-lg shadow-rose-500/30',
];

$initials = collect(explode(' ', $name))->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->join('');
@endphp

<div {{ $attributes->merge([
    'class' => 'rounded-xl flex items-center justify-center font-bold flex-shrink-0 ' . 
        ($sizes[$size] ?? $sizes['md']) . ' ' . 
        ($variants[$variant] ?? $variants['gradient'])
]) }}>
    @if($image)
        <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-full object-cover rounded-xl" />
    @else
        {{ $initials }}
    @endif
</div>
