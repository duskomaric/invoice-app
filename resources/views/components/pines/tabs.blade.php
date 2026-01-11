@props([
    'tabs' => [],
    'active' => null,
    'paramName' => 'tab',
])

@php
$activeTab = request()->get($paramName, $active ?? array_key_first($tabs));
@endphp

<div {{ $attributes }}>
    <div class="relative inline-flex items-center p-1 bg-neutral-100 rounded-lg">
        @foreach($tabs as $key => $label)
            <a 
                href="{{ request()->fullUrlWithQuery([$paramName => $key]) }}"
                @class([
                    'relative z-10 inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md transition-all',
                    'bg-white shadow-sm text-neutral-900' => $activeTab === $key,
                    'text-neutral-600 hover:text-neutral-900' => $activeTab !== $key,
                ])
            >
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>
