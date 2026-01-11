@props([
    'label' => null,
    'name' => null,
    'value' => null,
    'options' => [],
    'placeholder' => 'Select...',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'searchable' => false,
])

<div {{ $attributes->only('class')->merge(['class' => 'space-y-1']) }}>
    @if($label)
        <label @if($name) for="{{ $name }}" @endif class="block text-sm font-medium text-neutral-700">
            {{ $label }}
            @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    @if($searchable)
    <div x-data="{
        open: false,
        search: '',
        selected: '{{ $value }}',
        selectedLabel: '',
        options: {{ json_encode(collect($options)->map(fn($label, $val) => ['value' => $val, 'label' => $label])->values()) }},
        get filteredOptions() {
            if (!this.search) return this.options;
            return this.options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase()));
        },
        selectOption(option) {
            this.selected = option.value;
            this.selectedLabel = option.label;
            this.open = false;
            this.search = '';
        },
        init() {
            const found = this.options.find(o => o.value == this.selected);
            if (found) this.selectedLabel = found.label;
        }
    }" class="relative">
        <input type="hidden" name="{{ $name }}" x-model="selected">
        <button type="button" @click="open = !open" class="relative w-full h-10 pl-3 pr-10 text-left bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 text-sm">
            <span x-text="selectedLabel || '{{ $placeholder }}'" :class="{ 'text-neutral-400': !selectedLabel }"></span>
            <span class="absolute inset-y-0 right-0 flex items-center pr-2">
                <svg class="w-5 h-5 text-neutral-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd"/></svg>
            </span>
        </button>
        <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 w-full mt-1 bg-white border rounded-md shadow-lg border-neutral-200 max-h-60 overflow-auto" x-cloak>
            <div class="p-2 border-b">
                <input type="text" x-model="search" placeholder="Search..." class="w-full px-3 py-2 text-sm border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400">
            </div>
            <ul class="py-1">
                <template x-for="option in filteredOptions" :key="option.value">
                    <li @click="selectOption(option)" class="px-3 py-2 text-sm cursor-pointer hover:bg-neutral-100" :class="{ 'bg-neutral-100': selected == option.value }">
                        <span x-text="option.label"></span>
                    </li>
                </template>
                <li x-show="filteredOptions.length === 0" class="px-3 py-2 text-sm text-neutral-500">No results</li>
            </ul>
        </div>
    </div>
    @else
    <select 
        @if($name) name="{{ $name }}" id="{{ $name }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 {{ $error ? 'border-red-500' : '' }}"
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected($value == $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    @endif

    @if($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
