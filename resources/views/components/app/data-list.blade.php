@props(['collection'])

<div {{ $attributes }}>
    {{-- Desktop View --}}
    <div class="hidden lg:block relative z-10 stagger-3 page-enter opacity-0" style="animation-fill-mode: forwards;">
        {{ $desktop }}
        
        @if($collection && method_exists($collection, 'hasPages') && $collection->hasPages())
            <div class="p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 border-t-0 rounded-b-xl shadow-sm">
                {{ $collection->links() }}
            </div>
        @endif
    </div>

    {{-- Mobile View --}}
    <div class="lg:hidden space-y-3 pb-24 stagger-3 page-enter opacity-0" style="animation-fill-mode: forwards;">
        {{ $mobile }}

        @if($collection && method_exists($collection, 'hasPages') && $collection->hasPages())
            <div class="pt-4">
                {{ $collection->links() }}
            </div>
        @endif
    </div>
</div>
