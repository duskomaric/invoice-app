@props([
    'label' => null,
    'description' => null,
    'checked' => false,
    'id' => uniqid('toggle-'),
    'name' => null,
])

<div class="flex items-center justify-between py-3">
    @if($label || $description)
        <div class="flex flex-col">
            @if($label)
                <label for="{{ $id }}" class="text-sm font-semibold text-slate-800 dark:text-white cursor-pointer select-none">
                    {{ $label }}
                </label>
            @endif
            @if($description)
                <span class="text-xs text-slate-500 dark:text-slate-400">
                    {{ $description }}
                </span>
            @endif
        </div>
    @endif

    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" 
               id="{{ $id }}" 
               name="{{ $name }}" 
               class="sr-only peer" 
               {{ $checked ? 'checked' : '' }}
               {{ $attributes }}>
        <div class="w-10 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-violet-600"></div>
    </label>
</div>
