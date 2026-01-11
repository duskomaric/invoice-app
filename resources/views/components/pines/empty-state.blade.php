@props([
    'title' => 'No data',
    'description' => null,
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-12 text-center']) }}>
    @if($icon)
        <div class="flex items-center justify-center w-12 h-12 mb-4 rounded-full bg-neutral-100">
            {!! $icon !!}
        </div>
    @else
        <div class="flex items-center justify-center w-12 h-12 mb-4 rounded-full bg-neutral-100">
            <svg class="w-6 h-6 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </div>
    @endif
    
    <h3 class="text-sm font-medium text-neutral-900">{{ $title }}</h3>
    
    @if($description)
        <p class="mt-1 text-sm text-neutral-500">{{ $description }}</p>
    @endif
    
    @if(isset($action))
        <div class="mt-4">
            {{ $action }}
        </div>
    @endif
</div>
