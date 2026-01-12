@extends('layouts.app')

@section('title', 'Edit Contract - App')
@section('page-title', 'Edit Contract')
@section('page-subtitle', 'Update contract terms and covered items')
@section('page-badge', 'Edit')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.contracts.show', [$company, $contract]) }}" variant="secondary" size="sm" class="hidden sm:flex">
        Cancel
    </x-app.button>
    <x-app.button type="submit" form="contract-form" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Save Changes
    </x-app.button>
</div>
@endsection

@section('content')
<form id="contract-form" action="{{ route('app.contracts.update', [$company, $contract]) }}" method="POST"
    x-data="{
        items: @js($contract->items->map(fn($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description,
            'quantity' => (float) $item->quantity,
            'unit_price' => (float) ($item->unit_price / 100),
            'tax_rate' => (float) $item->tax_rate,
        ])),
        addItem() {
            this.items.push({ id: Date.now(), name: '', description: '', quantity: 1, unit_price: 0, tax_rate: 0 });
        },
        removeItem(id) {
            this.items = this.items.filter(item => item.id !== id);
        },
        get subtotal() {
            return this.items.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
        },
        get totalTax() {
            return this.items.reduce((sum, item) => sum + (item.quantity * item.unit_price * (item.tax_rate / 100)), 0);
        },
        get total() {
            return this.subtotal + this.totalTax;
        }
    }">
    @csrf
    @method('PUT')
    
    <div class="space-y-4">
        {{-- Client Selection and Basic Info --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <x-app.card class="lg:col-span-2 relative z-20">
                <x-app.section-header title="Client Selection" subtitle="Choose an existing client or add a new one" icon="user" variant="primary" />
                <div class="mt-4" x-data="{ open: false, search: '{{ $contract->client->name }}', clients: @js($clients) }">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Select Client</label>
                    <div class="relative">
                        <input type="hidden" name="client_id" :value="search ? clients.find(c => c.name === search)?.id : ''">
                        <input type="text" x-model="search" @focus="open = true" @click.away="open = false" placeholder="Search clients..." class="w-full h-10 px-3 rounded-xl bg-white dark:bg-slate-800 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm">
                        
                        <div x-show="open" class="absolute left-0 right-0 top-full mt-1 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 overflow-hidden z-50 max-h-48 overflow-y-auto">
                            <template x-for="client in clients.filter(c => c.name.toLowerCase().includes(search.toLowerCase()))" :key="client.id">
                                <button type="button" @click="search = client.name; open = false" class="w-full text-left px-4 py-2 text-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300">
                                    <div class="font-bold flex items-center justify-between">
                                        <span x-text="client.name"></span>
                                        <span class="text-[10px] text-slate-400" x-text="'ID: #' + client.id"></span>
                                    </div>
                                    <div class="text-[10px] text-slate-500" x-text="client.email"></div>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </x-app.card>

            <x-app.card>
                <x-app.section-header title="Contract Settings" subtitle="Document details" icon="cog" variant="secondary" />
                <div class="space-y-3 mt-4">
                    <x-app.input label="Start Date" type="date" name="date" value="{{ $contract->date->format('Y-m-d') }}" />
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Currency</label>
                        <select name="currency" class="w-full h-10 px-3 rounded-xl bg-white dark:bg-slate-800 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm">
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->code }}" {{ $contract->currency === $currency->code ? 'selected' : '' }}>{{ $currency->code }} - {{ $currency->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Language</label>
                        <select name="language" class="w-full h-10 px-3 rounded-xl bg-white dark:bg-slate-800 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm">
                            <option value="en" {{ $contract->language === 'en' ? 'selected' : '' }}>English</option>
                            <option value="hr" {{ $contract->language === 'hr' ? 'selected' : '' }}>Croatian</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Status</label>
                        <select name="status" class="w-full h-10 px-3 rounded-xl bg-white dark:bg-slate-800 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm">
                            @foreach(['draft', 'active', 'cancelled', 'expired'] as $status)
                                <option value="{{ $status }}" {{ $contract->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </x-app.card>
        </div>

        {{-- Items Section --}}
        <x-app.card class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <x-app.section-header title="Contract Items" subtitle="Services or products covered by this contract" icon="list-bullet" variant="primary" />
                <x-app.button type="button" @click="addItem" variant="secondary" size="sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Item
                </x-app.button>
            </div>

            <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800">
                            <th class="py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Item Name & Description</th>
                            <th class="py-3 px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-24 text-center">Qty</th>
                            <th class="py-3 px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-32 text-right">Price</th>
                            <th class="py-3 px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-24 text-right">Tax (%)</th>
                            <th class="py-3 px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-32 text-right">Total</th>
                            <th class="py-3 pl-2 w-10 text-right"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="item.id">
                            <tr class="border-b border-slate-50 dark:border-slate-800/50 group">
                                <td class="py-3">
                                    <input type="text" :name="'items['+index+'][name]'" x-model="item.name" placeholder="Item name" class="w-full bg-transparent border-none p-0 text-sm font-bold text-slate-900 dark:text-white focus:ring-0 mb-1" required>
                                    <input type="text" :name="'items['+index+'][description]'" x-model="item.description" placeholder="Description (optional)" class="w-full bg-transparent border-none p-0 text-xs text-slate-500 focus:ring-0">
                                </td>
                                <td class="py-3 px-2">
                                    <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" step="0.01" class="w-full h-8 px-2 rounded-lg bg-slate-50 dark:bg-slate-900 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 text-sm font-bold text-center">
                                </td>
                                <td class="py-3 px-2">
                                    <input type="number" :name="'items['+index+'][unit_price]'" x-model.number="item.unit_price" step="0.01" class="w-full h-8 px-2 rounded-lg bg-slate-50 dark:bg-slate-900 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 text-sm font-bold text-right">
                                </td>
                                <td class="py-3 px-2">
                                    <input type="number" :name="'items['+index+'][tax_rate]'" x-model.number="item.tax_rate" step="1" class="w-full h-8 px-2 rounded-lg bg-slate-50 dark:bg-slate-900 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 text-sm font-bold text-right">
                                </td>
                                <td class="py-3 px-2 text-right">
                                    <span class="text-sm font-bold text-slate-900 dark:text-white" x-text="(item.quantity * item.unit_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                                </td>
                                <td class="py-3 pl-2 text-right">
                                    <button type="button" @click="removeItem(item.id)" class="text-slate-300 hover:text-rose-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Notes</label>
                    <textarea name="notes" rows="4" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-xs text-slate-600 placeholder-slate-400" placeholder="Add terms, special instructions, or other details...">{{ $contract->notes }}</textarea>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-slate-500 text-sm">
                        <span>Subtotal</span>
                        <span class="font-bold text-slate-900 dark:text-white" x-text="subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                    </div>
                    <div class="flex items-center justify-between text-slate-500 text-sm">
                        <span>Tax Amount</span>
                        <span class="font-bold text-slate-900 dark:text-white" x-text="totalTax.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                    </div>
                    <div class="border-t border-slate-100 dark:border-slate-800 pt-2 flex items-center justify-between">
                        <span class="text-slate-900 dark:text-white font-bold">Total Amount</span>
                        <span class="text-xl font-bold text-violet-600 dark:text-violet-400" x-text="total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                    </div>
                </div>
            </div>
        </x-app.card>
    </div>
</form>
@endsection
