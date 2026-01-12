@props([
    'label' => null,
    'icon' => null,
    'prefix' => null,
    'suffix' => null,
    'error' => null,
])

<div class="space-y-1.5">
    @if($label)
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">{{ $label }}</label>
    @endif
    
    <div class="relative">
        @if($icon)
            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500">
                <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-4 h-4" />
            </div>
        @endif
        
        @if($prefix)
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-sm">{{ $prefix }}</span>
        @endif
        
        <input {{ $attributes->merge([
            'class' => 'w-full h-10 rounded-lg bg-white dark:bg-slate-700 border text-sm text-slate-800 dark:text-white placeholder-slate-500 dark:placeholder-slate-500 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-violet-500/20 shadow-sm ' .
                ($error ? 'border-rose-300 dark:border-rose-500/50 focus:border-rose-500' : 'border-slate-300 dark:border-slate-600 focus:border-violet-400 dark:focus:border-violet-500') .
                ($icon ? ' pl-10' : ($prefix ? ' pl-8' : ' pl-4')) .
                ($suffix ? ' pr-10' : ' pr-4')
        ]) }} />
        
        @if($suffix)
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-sm">{{ $suffix }}</span>
        @endif
    </div>
    
    @if($error)
        <p class="text-xs text-rose-500 dark:text-rose-400 flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $error }}
        </p>
    @endif
</div>
