@props([
    'href' => '#',
    'icon' => null,
    'active' => false,
    'badge' => null,
    'collapsed' => false,
])

<a 
    href="{{ $href }}" 
    {{ $attributes->merge([
        'class' => 'group flex items-center gap-2.5 px-3 py-2 rounded-xl transition-all duration-200 ' .
            ($active 
                ? 'bg-gradient-to-r from-violet-500/10 to-fuchsia-500/10 dark:from-violet-500/20 dark:to-fuchsia-500/20 text-violet-700 dark:text-violet-300 font-medium' 
                : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white')
    ]) }}
>
    @if($icon)
        <div @class([
            'flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200',
            'bg-gradient-to-br from-violet-500 to-fuchsia-600 text-white shadow-lg shadow-violet-500/30 group-hover:scale-105' => $active,
            'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:bg-slate-200 dark:group-hover:bg-slate-600 group-hover:text-slate-700 dark:group-hover:text-slate-200' => !$active,
        ])>
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-4 h-4" />
        </div>
    @endif
    
    @if(!$collapsed)
        <span class="flex-1 text-xs font-medium truncate">{{ $slot }}</span>
        
        @if($badge)
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-violet-100 dark:bg-violet-900/50 text-violet-600 dark:text-violet-300">{{ $badge }}</span>
        @endif
    @endif
</a>
