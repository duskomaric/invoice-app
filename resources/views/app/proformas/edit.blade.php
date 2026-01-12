@extends('layouts.app')

@section('title', 'Edit Proforma - App')
@section('page-title', 'Edit Proforma')
@section('page-subtitle', 'Update proforma details and items')
@section('page-badge', 'Edit')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.proformas.show', [$company, $proforma]) }}" variant="secondary" size="sm" class="hidden sm:flex">
        Cancel
    </x-app.button>
    <x-app.button type="submit" form="proforma-form" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Save Changes
    </x-app.button>
</div>
@endsection

@section('content')
<form id="proforma-form" action="{{ route('app.proformas.update', [$company, $proforma]) }}" method="POST"
    x-data="{
        items: @js($proforma->items->map(fn($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description,
            'quantity' => (float) $item->quantity,
            'unit_price' => (float) ($item->unit_price / 100),
            'tax_rate' => (float) $item->tax_rate,
        ])),
        clients: @json($clients),
        selectedClient: @json($proforma->client),
        search: '',
        open: false,

        get filteredClients() {
            if (this.search === '') return this.clients;
            return this.clients.filter(client => {
                return client.name.toLowerCase().includes(this.search.toLowerCase()) || 
                       client.email.toLowerCase().includes(this.search.toLowerCase());
            });
        },

        selectClient(client) {
            this.selectedClient = client;
            this.open = false;
            this.search = '';
        },

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
                
                <div class="mt-4 relative" x-data="{ open: false, search: '{{ $proforma->client->name }}', clients: @js($clients) }" @click.away="open = false">
                    <div class="relative" x-show="!selectedClient">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" x-model="search" @focus="open = true" @click="open = true" placeholder="Search or select a client..." class="w-full h-14 pl-11 pr-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-sm font-medium text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm">
                    </div>

                    <div x-show="open && !selectedClient" x-transition class="absolute left-0 right-0 mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 py-2 z-[100] max-h-72 overflow-y-auto" x-cloak>
                        <a href="{{ route('app.clients.create', $company) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-violet-50 dark:hover:bg-violet-900/20 border-b border-slate-100 dark:border-slate-700 text-violet-600 dark:text-violet-400 group transition-colors">
                            <div class="w-8 h-8 rounded-lg border-2 border-dashed border-violet-300 dark:border-violet-600 group-hover:border-violet-400 flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <span class="font-bold text-sm">Create New Client</span>
                        </a>
                        <template x-for="client in filteredClients" :key="client.id">
                            <button type="button" @click="selectClient(client)" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-left border-b border-slate-50 dark:border-slate-700/50 last:border-0 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300 flex-shrink-0" x-text="client.name.substring(0, 2).toUpperCase()"></div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 dark:text-white text-sm truncate" x-text="client.name"></p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate" x-text="client.email"></p>
                                </div>
                            </button>
                        </template>
                    </div>

                    {{-- Selected Client Card --}}
                    <div x-show="selectedClient" class="h-14 px-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between" x-cloak>
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-xs font-bold text-violet-600 dark:text-violet-400 flex-shrink-0" x-text="selectedClient?.name.substring(0, 2).toUpperCase()"></div>
                            <div class="min-w-0">
                                <input type="hidden" name="client_id" :value="selectedClient?.id">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate" x-text="selectedClient?.name"></h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate" x-text="selectedClient?.email"></p>
                            </div>
                        </div>
                        <button type="button" @click="selectedClient = null" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-50 hover:bg-slate-100 dark:bg-slate-700 dark:hover:bg-slate-600 text-xs font-bold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 transition-all ml-3 flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Change
                        </button>
                    </div>
                </div>
            </x-app.card>

            <x-app.card>
                <x-app.section-header title="Proforma Settings" subtitle="Document details" icon="cog" variant="secondary" />
                <div class="space-y-3 mt-4">
                    <x-app.input label="Date" type="date" name="date" value="{{ $proforma->date->format('Y-m-d') }}" />
                    <x-app.input label="Due Date" type="date" name="due_date" value="{{ $proforma->due_date->format('Y-m-d') }}" />
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Currency</label>
                        <select name="currency" class="w-full h-10 px-3 rounded-xl bg-white dark:bg-slate-800 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm">
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->code }}" {{ $proforma->currency === $currency->code ? 'selected' : '' }}>{{ $currency->code }} - {{ $currency->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Language</label>
                        <select name="language" class="w-full h-10 px-3 rounded-xl bg-white dark:bg-slate-800 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm">
                            <option value="en" {{ $proforma->language === 'en' ? 'selected' : '' }}>English</option>
                            <option value="hr" {{ $proforma->language === 'hr' ? 'selected' : '' }}>Croatian</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Status</label>
                        <select name="status" class="w-full h-10 px-3 rounded-xl bg-white dark:bg-slate-800 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm">
                            @foreach(['draft', 'sent', 'expired', 'converted'] as $status)
                                <option value="{{ $status }}" {{ $proforma->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </x-app.card>
        </div>

        {{-- Items Section --}}
        <x-app.card class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <x-app.section-header title="Proforma Items" subtitle="Add products or services to this document" icon="list-bullet" variant="primary" />
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
                    <textarea name="notes" rows="4" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-xs text-slate-600 placeholder-slate-400" placeholder="Add payment instructions, terms, or other details...">{{ $proforma->notes }}</textarea>
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
