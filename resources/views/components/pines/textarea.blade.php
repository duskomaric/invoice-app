@props([
    'label' => null,
    'name' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'rows' => 3,
    'error' => null,
])

<div {{ $attributes->only('class')->merge(['class' => 'space-y-1']) }}>
    @if($label)
        <label @if($name) for="{{ $name }}" @endif class="block text-sm font-medium text-neutral-700">
            {{ $label }}
            @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    
    <textarea 
        @if($name) name="{{ $name }}" id="{{ $name }}" @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        rows="{{ $rows }}"
        {{ $attributes->except('class')->merge([
            'class' => 'flex w-full px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50' . ($error ? ' border-red-500' : '')
        ]) }}
    >{{ $value ?? $slot }}</textarea>
    
    @if($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
