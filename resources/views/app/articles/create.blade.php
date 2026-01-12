@extends('layouts.app')

@section('title', 'Create Article - ' . $company->name)
@section('page-title', 'Create New Article')
@section('page-subtitle', 'Add a new service or product to your catalog')

@section('content')
<form action="{{ route('app.articles.store', $company) }}" method="POST" class="max-w-4xl mx-auto">
    @csrf
    
    <div class="space-y-6">
        <x-app.card>
            <x-app.section-header title="Basic Information" subtitle="General article details" icon="tag" variant="primary" />
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                <div class="md:col-span-2">
                    <x-app.input label="Article Name" name="name" placeholder="e.g. Website Design" required :value="old('name')" />
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-sm text-slate-800 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 transition-all p-3" placeholder="Optional description...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Type</label>
                    <select name="type" class="w-full h-10 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 transition-all px-3">
                        <option value="services" {{ old('type') === 'services' ? 'selected' : '' }}>Service</option>
                        <option value="goods" {{ old('type') === 'goods' ? 'selected' : '' }}>Goods</option>
                    </select>
                </div>

                <div>
                    <x-app.input label="Unit" name="unit" placeholder="e.g. KOM, SAT, PCS" :value="old('unit', 'service')" />
                </div>
            </div>
        </x-app.card>

        <x-app.card>
            <x-app.section-header title="Pricing & Tax" subtitle="Set standard rates per currency" icon="banknotes" variant="success" />
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                @foreach($company->currencies as $currency)
                <div>
                    <x-app.input 
                        label="Price ({{ $currency->code }})" 
                        name="prices[{{ $currency->code }}]" 
                        type="number" 
                        step="0.01" 
                        placeholder="0.00" 
                        :value="old('prices.' . $currency->code)"
                    />
                </div>
                @endforeach

                <div class="md:col-span-3">
                    <x-app.input label="Tax Category (Optional)" name="tax_category" placeholder="e.g. 17, 0, E" :value="old('tax_category')" />
                </div>
            </div>
        </x-app.card>

        <div class="flex items-center justify-end gap-3 pb-12">
            <x-app.button :href="route('app.articles.index', $company)" variant="secondary">Cancel</x-app.button>
            <x-app.button type="submit" variant="primary">Create Article</x-app.button>
        </div>
    </div>
</form>
@endsection
