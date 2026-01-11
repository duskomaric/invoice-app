@props([
    'title' => null,
    'description' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white border border-neutral-200/60 rounded-lg shadow-sm']) }}>
    @if($title || isset($header))
        <div class="px-6 py-4 border-b border-neutral-200/60">
            @if(isset($header))
                {{ $header }}
            @else
                <h3 class="text-lg font-semibold text-neutral-900">{{ $title }}</h3>
                @if($description)
                    <p class="mt-1 text-sm text-neutral-500">{{ $description }}</p>
                @endif
            @endif
        </div>
    @endif
    
    <div @class(['px-6 py-4' => $padding])>
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="px-6 py-4 border-t border-neutral-200/60 bg-neutral-50/50">
            {{ $footer }}
        </div>
    @endif
</div>
