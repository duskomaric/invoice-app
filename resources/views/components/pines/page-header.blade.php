@props([
    'title' => null,
    'description' => null,
    'breadcrumbs' => [],
])

<div {{ $attributes->merge(['class' => 'mb-6']) }}>
    @if(count($breadcrumbs) > 0)
        <nav class="flex mb-2 text-sm text-neutral-500">
            @foreach($breadcrumbs as $crumb)
                @if(!$loop->last)
                    <a href="{{ $crumb['url'] }}" class="hover:text-neutral-700">{{ $crumb['label'] }}</a>
                    <span class="mx-2">/</span>
                @else
                    <span class="text-neutral-900">{{ $crumb['label'] }}</span>
                @endif
            @endforeach
        </nav>
    @endif
    
    <div class="flex items-center justify-between">
        <div>
            @if($title)
                <h1 class="text-2xl font-semibold text-neutral-900">{{ $title }}</h1>
            @endif
            @if($description)
                <p class="mt-1 text-sm text-neutral-500">{{ $description }}</p>
            @endif
        </div>
        
        @if(isset($actions))
            <div class="flex items-center gap-3">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
