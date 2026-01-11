@props([
    'name' => 'modal',
    'maxWidth' => '2xl',
    'title' => '',
])

@php
$maxWidthClasses = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
    'full' => 'max-w-full mx-4',
];
@endphp

<div 
    x-show="{{ $name }}" 
    x-transition:enter="transition ease-out duration-300" 
    x-transition:enter-start="opacity-0" 
    x-transition:enter-end="opacity-100" 
    x-transition:leave="transition ease-in duration-200" 
    x-transition:leave-start="opacity-100" 
    x-transition:leave-end="opacity-0" 
    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
    @click.self="{{ $name }} = false"
    x-cloak
>
    <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-sm"></div>
    
    <div 
        x-show="{{ $name }}"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative w-full {{ $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['2xl'] }} bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-white/20 dark:border-slate-700/50 overflow-hidden"
        @click.stop
    >
        @if($title)
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700/50">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $title }}</h3>
            <button @click="{{ $name }} = false" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif
        
        <div class="p-6 max-h-[70vh] overflow-y-auto">
            {{ $slot }}
        </div>
        
        @isset($footer)
        <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/50">
            {{ $footer }}
        </div>
        @endisset
    </div>
</div>
