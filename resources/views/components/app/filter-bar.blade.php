@props([
    'placeholder' => 'Search...',
    'searchModel' => null,
])

<div class="relative z-50">
    <x-app.card class="p-3">
        <div class="flex flex-wrap items-center gap-2">
            {{-- Search --}}
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" 
                       placeholder="{{ $placeholder }}" 
                       @if($searchModel) x-model="{{ $searchModel }}" @endif
                       class="w-full h-9 pl-9 pr-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 text-xs focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm">
            </div>

            {{-- Extra Filters/Actions --}}
            <div class="flex items-center gap-2">
                {{ $slot }}
            </div>
        </div>
    </x-app.card>
</div>
