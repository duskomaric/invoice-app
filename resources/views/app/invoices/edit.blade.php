@extends('layouts.app')

@section('title', 'Edit Invoice INV-2024-0042 - InvoicePro')
@section('page-title', 'Edit Invoice')
@section('page-subtitle', 'INV-2024-0042')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="/templates/invoice5/1" variant="secondary" size="sm" class="hidden sm:flex">
        Cancel
    </x-app.button>
    <x-app.button type="submit" form="invoice-form" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Update Invoice
    </x-app.button>
</div>
@endsection

@section('content')
<form id="invoice-form" x-data="invoiceForm()" class="space-y-4">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        {{-- Main Content --}}
        <div class="xl:col-span-2 space-y-4">
            {{-- Client Selection --}}
            <div class="stagger-1 page-enter opacity-0 relative z-50" style="animation-fill-mode: forwards;">
                <x-app.card>
                    <x-app.section-header 
                        title="Client Information" 
                        subtitle="Update client details" 
                        icon="user" 
                        variant="primary" 
                    />
                    
                    <div class="relative" x-data="{ open: false, search: '' }" @click.away="open = false">
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
            </div>

            {{-- Invoice Items --}}
            <div class="stagger-2 page-enter opacity-0 relative z-10" style="animation-fill-mode: forwards;">
                <x-app.card>
                    <x-app.section-header 
                        title="Invoice Items" 
                        subtitle="Modify products or services" 
                        icon="clipboard-document-list" 
                        variant="info" 
                    />
                    
                    {{-- Items Header --}}
                    <div class="hidden lg:grid lg:grid-cols-12 gap-3 mb-2 px-1">
                        <div class="col-span-5 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Article
                        </div>
                        <div class="col-span-2 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Qty</div>
                        <div class="col-span-2 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Price</div>
                        <div class="col-span-2 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Total</div>
                        <div class="col-span-1"></div>
                    </div>

                    {{-- Items --}}
                    <div class="space-y-2">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-700/30 border border-slate-200 dark:border-slate-600/40">
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-start">
                                    <div class="lg:col-span-5">
                                        <label class="lg:hidden text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Article</label>
                                        <div class="relative">
                                            <select x-model="item.article" @change="selectArticle(index, $event.target.value)" class="w-full h-9 px-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 appearance-none cursor-pointer transition-all">
                                                <option value="">Select article...</option>
                                                <template x-for="article in articles" :key="article.id">
                                                    <option :value="article.id" x-text="article.name" :selected="item.article == article.id"></option>
                                                </template>
                                            </select>
                                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                    <div class="lg:col-span-2">
                                        <label class="lg:hidden text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Qty</label>
                                        <input type="number" x-model="item.quantity" min="1" class="w-full h-9 px-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all">
                                    </div>
                                    <div class="lg:col-span-2">
                                        <label class="lg:hidden text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Price</label>
                                        <div class="relative">
                                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs">€</span>
                                            <input type="number" x-model="item.price" step="0.01" class="w-full h-9 pl-7 pr-2 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all">
                                        </div>
                                    </div>
                                    <div class="lg:col-span-2 flex items-center lg:justify-end h-9">
                                        <label class="lg:hidden text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mr-auto">Total</label>
                                        <span class="text-sm font-bold text-slate-900 dark:text-white" x-text="'€' + (item.quantity * item.price).toFixed(2)"></span>
                                    </div>
                                    <div class="lg:col-span-1 flex justify-end">
                                        <button type="button" @click="removeItem(index)" class="p-1.5 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-900/30 text-slate-400 dark:text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 transition-colors" :disabled="items.length === 1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Add Item Button --}}
                    <button type="button" @click="addItem()" class="w-full mt-3 h-10 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600/60 hover:border-violet-400 dark:hover:border-violet-500 hover:bg-violet-50/50 dark:hover:bg-violet-900/20 text-slate-500 dark:text-slate-400 hover:text-violet-600 dark:hover:text-violet-400 text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Item
                    </button>
                </x-app.card>
            </div>

            {{-- Danger Zone --}}
            <div class="stagger-3 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <x-app.card class="border-rose-200 dark:border-rose-900/50">
                    <x-app.section-header 
                        title="Danger Zone" 
                        subtitle="Irreversible actions" 
                        icon="exclamation-triangle" 
                        variant="danger" 
                    />
                    <div class="flex items-center justify-between p-3 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60">
                        <div>
                            <p class="text-sm font-medium text-rose-800 dark:text-rose-200">Delete this invoice</p>
                            <p class="text-xs text-rose-600/70 dark:text-rose-300/70">Once deleted, this cannot be undone</p>
                        </div>
                        <x-app.button variant="danger" size="sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete Invoice
                        </x-app.button>
                    </div>
                </x-app.card>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            {{-- Invoice Details --}}
            <div class="stagger-1 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <x-app.card>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Invoice Details
                    </h3>
                    <div class="space-y-3">
                        <x-app.input label="Invoice Number" value="INV-2024-0042" readonly class="bg-slate-50 dark:bg-slate-700" />
                        <x-app.input label="Issue Date" type="date" value="2024-01-15" />
                        <x-app.input label="Due Date" type="date" value="2024-02-14" />
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Status</label>
                            <select class="w-full h-10 px-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm">
                                <option value="draft">Draft</option>
                                <option value="pending">Pending</option>
                                <option value="paid" selected>Paid</option>
                                <option value="overdue">Overdue</option>
                            </select>
                        </div>
                    </div>
                </x-app.card>
            </div>

            {{-- Summary --}}
            <div class="stagger-2 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <div class="rounded-2xl bg-gradient-to-br from-violet-600 via-purple-600 to-fuchsia-600 p-5 text-white shadow-xl shadow-violet-500/30">
                    <h3 class="text-sm font-bold mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Summary
                    </h3>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-violet-200 text-sm">Subtotal</span>
                            <span class="font-semibold" x-text="'€' + subtotal.toFixed(2)"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-violet-200 text-sm">Tax (17%)</span>
                            <span class="font-semibold" x-text="'€' + tax.toFixed(2)"></span>
                        </div>
                        <div class="border-t border-white/20 pt-3 mt-3">
                            <div class="flex items-center justify-between">
                                <span class="text-base font-bold">Total</span>
                                <span class="text-2xl font-bold" x-text="'€' + total.toFixed(2)"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activity --}}
            <div class="stagger-3 page-enter opacity-0 pb-24 lg:pb-0" style="animation-fill-mode: forwards;">
                <x-app.card>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Activity
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-slate-800 dark:text-white">Payment received</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400">Feb 10, 2024</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center text-sky-600 dark:text-sky-400 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-slate-800 dark:text-white">Invoice viewed</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400">Jan 20, 2024</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 dark:text-violet-400 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-slate-800 dark:text-white">Invoice sent</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400">Jan 15, 2024</p>
                            </div>
                        </div>
                    </div>
                </x-app.card>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function invoiceForm() {
    return {
        clients: @json($clients),
        selectedClient: @json($invoice->client),
        
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

        items: [
            { article: 1, quantity: 1, price: 1500 },
            { article: 2, quantity: 1, price: 500 },
            { article: 3, quantity: 1, price: 340 },
        ],
        get subtotal() {
            return this.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
        },
        get tax() {
            return this.subtotal * 0.17;
        },
        get total() {
            return this.subtotal + this.tax;
        },
        addItem() {
            this.items.push({ article: '', quantity: 1, price: 0 });
        },
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },
        selectArticle(index, articleId) {
            const article = this.articles.find(a => a.id == articleId);
            if (article) {
                this.items[index].price = article.price;
            }
        }
    }
}
</script>
@endpush
@endsection
