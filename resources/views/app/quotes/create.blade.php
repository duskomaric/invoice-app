@extends('layouts.app')

@section('title', 'New Quote - ' . $company->name)
@section('page-title', 'Create Quote')
@section('page-subtitle', 'Send a new professional quote to your prospect')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.quotes.index', $company) }}" variant="secondary" size="sm" class="hidden sm:flex">
        Cancel
    </x-app.button>
    <x-app.button type="submit" form="quote-form" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Save Quote
    </x-app.button>
</div>
@endsection

@section('content')
@php
    $articles = \App\Models\Article::where('company_id', $company->id)->where('is_active', true)->get();
@endphp

<form id="quote-form" action="{{ route('app.quotes.store', $company) }}" method="POST"
    x-data="quoteForm({ 
        articles: @js($articles),
        currencies: @js($currencies),
        initialItems: @js(old('items', [['article_id' => '', 'name' => '', 'description' => '', 'quantity' => 1, 'unit_price' => 0, 'tax_rate' => 17]]))
    })">
    @csrf
    
    <div class="space-y-4 pb-24">
        {{-- Client Selection and Basic Info --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <x-app.card class="lg:col-span-2">
                <x-app.section-header title="Prospect Selection" subtitle="Choose an existing client or lead" icon="user" variant="primary" />
                <div class="mt-4">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Select Client</label>
                    <div class="relative">
                        <select name="client_id" required class="w-full h-11 px-4 rounded-xl bg-white dark:bg-slate-800 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm font-bold text-slate-700 dark:text-slate-200">
                            <option value="">Search clients...</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }} ({{ $client->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </x-app.card>

            <x-app.card>
                <x-app.section-header title="Quote Settings" subtitle="Document details" icon="cog" variant="secondary" />
                <div class="space-y-3 mt-4">
                    <x-app.input label="Date" type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required />
                    <x-app.input label="Valid Until" type="date" name="valid_until" value="{{ old('valid_until', date('Y-m-d', strtotime('+30 days'))) }}" required />
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Currency</label>
                        <select name="currency" x-model="currency" class="w-full h-10 px-3 rounded-xl bg-white dark:bg-slate-800 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm font-bold">
                            @foreach($currencies as $c)
                                <option value="{{ $c->code }}">{{ $c->code }} - {{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Language</label>
                        <select name="language" class="w-full h-10 px-3 rounded-xl bg-white dark:bg-slate-800 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm font-bold">
                            <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                            <option value="hr" {{ old('language') == 'hr' ? 'selected' : '' }}>Croatian</option>
                        </select>
                    </div>
                </div>
            </x-app.card>
        </div>

        {{-- Items Section --}}
        <x-app.card>
            <div class="flex items-center justify-between mb-6">
                <x-app.section-header title="Proposed Services" subtitle="Add items to your quote" icon="list-bullet" variant="primary" />
                <x-app.button type="button" @click="addItem" variant="secondary" size="sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Item
                </x-app.button>
            </div>

            <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800">
                            <th class="py-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Item / Description</th>
                            <th class="py-3 px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-24 text-center">Qty</th>
                            <th class="py-3 px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-32 text-right">Rate</th>
                            <th class="py-3 px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-24 text-right">Tax (%)</th>
                            <th class="py-3 px-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider w-32 text-right">Total</th>
                            <th class="py-3 pl-2 w-10 text-right"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="item.id">
                            <tr class="border-b border-slate-50 dark:border-slate-800/50 group">
                                <td class="py-4">
                                    <div class="relative mb-1">
                                        <select 
                                            :name="'items['+index+'][article_id]'" 
                                            x-model="item.article_id"
                                            @change="selectArticle(item, $event.target.value)"
                                            class="w-full bg-slate-50 dark:bg-slate-900/50 border-none rounded-lg text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-violet-500/20"
                                            required
                                        >
                                            <option value="">Select an article...</option>
                                            <template x-for="article in articles" :key="article.id">
                                                <option :value="article.id" x-text="article.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <input type="text" :name="'items['+index+'][name]'" x-model="item.name" class="hidden">
                                    <textarea :name="'items['+index+'][description]'" x-model="item.description" placeholder="Optional description..." class="w-full bg-transparent border-none p-0 text-[11px] text-slate-500 focus:ring-0 resize-none h-6"></textarea>
                                </td>
                                <td class="py-4 px-2">
                                    <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" step="1" class="w-full h-9 px-2 rounded-lg bg-slate-50 dark:bg-slate-900/50 border-none ring-1 ring-slate-200 dark:ring-slate-700/50 focus:ring-2 focus:ring-violet-500/30 text-sm font-bold text-center">
                                </td>
                                <td class="py-4 px-2">
                                    <div class="relative">
                                        <input type="number" :name="'items['+index+'][unit_price]'" x-model.number="item.unit_price" step="0.01" class="w-full h-9 px-2 rounded-lg bg-slate-50 dark:bg-slate-900/50 border-none ring-1 ring-slate-200 dark:ring-slate-700/50 focus:ring-2 focus:ring-violet-500/30 text-sm font-bold text-right">
                                    </div>
                                </td>
                                <td class="py-4 px-2">
                                    <input type="number" :name="'items['+index+'][tax_rate]'" x-model.number="item.tax_rate" step="1" class="w-full h-9 px-2 rounded-lg bg-slate-50 dark:bg-slate-900/50 border-none ring-1 ring-slate-200 dark:ring-slate-700/50 focus:ring-2 focus:ring-violet-500/30 text-sm font-bold text-right">
                                </td>
                                <td class="py-4 px-2 text-right">
                                    <span class="text-sm font-black text-slate-900 dark:text-white" x-text="formatMoney(item.quantity * item.unit_price)"></span>
                                </td>
                                <td class="py-4 pl-2 text-right">
                                    <button type="button" @click="removeItem(item.id)" class="text-slate-300 hover:text-rose-500 transition-colors p-1" title="Remove Item">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mt-10">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">Client Notes</label>
                    <textarea name="notes" rows="4" class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-xs text-slate-600 dark:text-slate-400 placeholder-slate-400" placeholder="Scope of work, timeline, or other details..."></textarea>
                </div>
                <div class="space-y-3 bg-slate-50 dark:bg-slate-900/30 p-6 rounded-3xl border border-slate-100 dark:border-slate-800">
                    <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wide">
                        <span>Subtotal</span>
                        <span class="text-slate-900 dark:text-white" x-text="formatMoney(subtotal)"></span>
                    </div>
                    <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wide">
                        <span>Tax Amount</span>
                        <span class="text-slate-900 dark:text-white" x-text="formatMoney(totalTax)"></span>
                    </div>
                    <div class="border-t border-slate-200 dark:border-slate-700 pt-3 flex items-center justify-between">
                        <span class="text-slate-900 dark:text-white font-black text-xs uppercase tracking-widest">Estimated Total</span>
                        <span class="text-2xl font-black text-violet-600 dark:text-violet-400" x-text="formatMoney(total)"></span>
                    </div>
                </div>
            </div>
        </x-app.card>
    </div>
</form>

<script>
function quoteForm(config) {
    return {
        articles: config.articles,
        currencies: config.currencies,
        currency: '{{ old('currency', $company->currency) }}',
        items: config.initialItems.map(item => ({
            ...item,
            id: item.id || Date.now() + Math.random()
        })),
        
        addItem() {
            this.items.push({ 
                id: Date.now() + Math.random(), 
                article_id: '',
                name: '', 
                description: '', 
                quantity: 1, 
                unit_price: 0, 
                tax_rate: 17 
            });
        },
        
        removeItem(id) {
            if (this.items.length > 1) {
                this.items = this.items.filter(item => item.id !== id);
            }
        },

        selectArticle(item, articleId) {
            const article = this.articles.find(a => a.id == articleId);
            if (article) {
                item.name = article.name;
                item.description = article.description || '';
                item.tax_rate = article.tax_category || 17;
                
                if (article.prices_meta && article.prices_meta[this.currency]) {
                    item.unit_price = article.prices_meta[this.currency] / 100;
                } else {
                    const prices = Object.values(article.prices_meta || {});
                    item.unit_price = prices.length > 0 ? prices[0] / 100 : 0;
                }
            }
        },

        get subtotal() {
            return this.items.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
        },

        get totalTax() {
            return this.items.reduce((sum, item) => sum + (item.quantity * item.unit_price * (item.tax_rate / 100)), 0);
        },

        get total() {
            return this.subtotal + this.totalTax;
        },

        formatMoney(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: this.currency,
                minimumFractionDigits: 2
            }).format(amount);
        }
    }
}
</script>
@endsection
