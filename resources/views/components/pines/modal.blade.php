@props([
    'name' => 'modal',
    'title' => null,
    'maxWidth' => 'lg',
])

@php
$maxWidthClasses = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
];
@endphp

<div 
    x-data="{ open: false }"
    x-on:open-modal-{{ $name }}.window="open = true"
    x-on:close-modal-{{ $name }}.window="open = false"
    @keydown.escape.window="open = false"
    x-cloak
>
    <template x-teleport="body">
        <div x-show="open" class="fixed inset-0 z-[99] flex items-center justify-center w-screen h-screen">
            <div 
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="open = false"
                class="absolute inset-0 bg-black/40"
            ></div>
            
            <div 
                x-show="open"
                x-trap.inert.noscroll="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative w-full bg-white sm:rounded-lg {{ $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['lg'] }}"
            >
                @if($title)
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-neutral-900">{{ $title }}</h3>
                    <button @click="open = false" class="p-1 text-neutral-400 hover:text-neutral-600 rounded-full hover:bg-neutral-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                @endif
                
                <div class="px-6 py-4">
                    {{ $slot }}
                </div>
                
                @if(isset($footer))
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t bg-neutral-50">
                    {{ $footer }}
                </div>
                @endif
            </div>
        </div>
    </template>
</div>
