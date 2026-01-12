@extends('layouts.app')

@section('title', 'Create Invoice - App')
@section('page-title', 'Create Invoice')
@section('page-subtitle', 'Create a new invoice for your client')
@section('page-badge', 'New')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.invoices.index', $company) }}" variant="secondary" size="sm" class="hidden sm:flex">
        Cancel
    </x-app.button>
    <x-app.button type="submit" form="invoice-form" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Save Invoice
    </x-app.button>
</div>
@endsection

@section('content')
<form id="invoice-form" action="{{ route('app.invoices.store', $company) }}" method="POST" x-data="invoiceForm()" class="space-y-4">
    @csrf
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        {{-- Main Content --}}
        <div class="xl:col-span-2 space-y-4">
            {{-- Client Selection --}}
            <div class="stagger-1 page-enter opacity-0 relative z-50" style="animation-fill-mode: forwards;">
                <x-app.card>
                    <x-app.section-header 
                        title="Client Information" 
                        subtitle="Select or add a client" 
                        icon="user" 
                        variant="primary" 
                    />

                    <div class="relative" x-data="{ open: false, search: '' }" @click.away="open = false">
                        <div class="relative" x-show="!selectedClient">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" x-model="search" @focus="open = true" @click="open = true" placeholder="Search or select a client..." class="w-full h-9 pl-9 pr-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all">
                        </div>
                            <div x-show="open && !selectedClient" x-transition class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-slate-200 dark:border-slate-700 py-1 z-[100] max-h-64 overflow-y-auto" x-cloak>
                                <a href="{{ route('app.clients.create', $company) }}" class="flex items-center gap-2.5 px-3 py-2 hover:bg-violet-50 dark:hover:bg-violet-900/20 border-b border-slate-100 dark:border-slate-700 text-violet-600 dark:text-violet-400">
                                    <div class="w-8 h-8 rounded-lg border-2 border-dashed border-violet-300 dark:border-violet-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </div>
                                    <span class="font-medium text-xs">Add new client</span>
                                </a>
                                <template x-for="client in filteredClients" :key="client.id">
                                    <button type="button" @click="selectClient(client)" class="w-full flex items-center gap-2.5 px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700/50 text-left">
                                        <div class="w-6 h-6 rounded bg-slate-100 dark:bg-slate-600 flex items-center justify-center text-[10px] font-bold text-slate-600 dark:text-slate-300" x-text="client.name.substring(0, 2).toUpperCase()"></div>
                                        <div>
                                            <p class="font-medium text-slate-700 dark:text-white text-xs" x-text="client.name"></p>
                                            <p class="text-[10px] text-slate-400 dark:text-slate-500" x-text="client.email"></p>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Selected Client Card --}}
                        <div x-show="selectedClient" class="p-3 rounded-xl bg-violet-50/50 dark:bg-violet-900/10 border border-violet-100 dark:border-violet-500/20 flex items-center justify-between" x-cloak>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-sm font-bold text-violet-600 dark:text-violet-400 border border-violet-100 dark:border-violet-500/20" x-text="selectedClient?.name.substring(0, 2).toUpperCase()"></div>
                                <div>
                                    <input type="hidden" name="client_id" :value="selectedClient?.id">
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white" x-text="selectedClient?.name"></h4>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400" x-text="selectedClient?.email"></p>
                                </div>
                            </div>
                            <button type="button" @click="selectedClient = null" class="p-1.5 rounded-lg hover:bg-white dark:hover:bg-slate-800 text-slate-400 hover:text-rose-500 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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
                        subtitle="Add products or services" 
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
                                    <div class="lg:col-span-12 mb-2">
                                        <div class="relative">
                                            <select :name="'items['+index+'][article_id]'" x-model="item.article" @change="selectArticle(index, $event.target.value)" class="w-full h-9 px-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 appearance-none cursor-pointer transition-all shadow-sm">
                                                <option value="">Select an article or type custom name below...</option>
                                                <template x-for="article in articles" :key="article.id">
                                                    <option :value="article.id" x-text="article.name"></option>
                                                </template>
                                            </select>
                                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                    <div class="lg:col-span-5">
                                        <label class="lg:hidden text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Item Name</label>
                                        <input type="text" :name="'items['+index+'][name]'" x-model="item.name" placeholder="Item name" class="w-full h-9 px-3 rounded-lg bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm">
                                    </div>
                                    <div class="lg:col-span-2">
                                        <label class="lg:hidden text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Qty</label>
                                        <input type="number" :name="'items['+index+'][quantity]'" x-model="item.quantity" placeholder="1" class="w-full h-9 px-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all text-center">
                                    </div>
                                    <div class="lg:col-span-2">
                                        <label class="lg:hidden text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Price</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 dark:text-slate-500" x-text="currencySymbol"></span>
                                            <input type="number" step="0.01" :name="'items['+index+'][unit_price]'" x-model="item.price" placeholder="0.00" class="w-full h-9 pl-7 pr-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all text-right">
                                        </div>
                                    </div>
                                    <div class="lg:col-span-2 flex items-center justify-between gap-2">
                                        <label class="lg:hidden text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mr-auto">Total</label>
                                        <div class="text-xs font-bold text-slate-900 dark:text-white" x-text="formatMoney(item.quantity * item.price)"></div>
                                        <button type="button" @click="removeItem(index)" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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

            {{-- Notes --}}
            <div class="stagger-3 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <x-app.card>
                    <x-app.section-header 
                        title="Notes & Terms" 
                        subtitle="Additional information" 
                        icon="document-text" 
                        variant="amber" 
                    />
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Notes to Client</label>
                            <textarea rows="2" placeholder="Add any notes..." class="w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Payment Terms</label>
                            <textarea rows="2" class="w-full px-3 py-2 rounded-xl bg-white dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm resize-none">Payment is due within 30 days of invoice date.</textarea>
                        </div>
                    </div>
                </x-app.card>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            {{-- Invoice Details --}}
            <div class="stagger-1 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <x-app.card>
                    <x-app.section-header 
                        title="Document Settings" 
                        subtitle="Configuration & defaults" 
                        icon="cog-6-tooth" 
                        variant="primary" 
                    />
                    
                    <div class="space-y-4">
                        <x-app.input label="Date" type="date" name="date" x-model="date" />
                        <x-app.input label="Due Date" type="date" name="due_date" x-model="dueDate" />
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Currency</label>
                            <select name="currency" x-model="currency" class="w-full h-10 px-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm">
                                @foreach($currencies as $curr)
                                    <option value="{{ $curr->code }}">{{ $curr->code }} - {{ $curr->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Language</label>
                            <select name="language" x-model="language" class="w-full h-10 px-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm">
                                <option value="en">English (US)</option>
                                <option value="ba">Bosnian (BA)</option>
                                <option value="de">German (DE)</option>
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
                            <span class="font-semibold" x-text="formatMoney(subtotal)"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-violet-200 text-sm">Tax (17%)</span>
                            <span class="font-semibold" x-text="formatMoney(tax)"></span>
                        </div>
                        <div class="border-t border-white/20 pt-3 mt-3">
                            <div class="flex items-center justify-between">
                                <span class="text-base font-bold">Total</span>
                                <span class="text-2xl font-bold" x-text="formatMoney(total)"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="stagger-3 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <x-app.card>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Quick Actions
                    </h3>
                    <div class="space-y-2">
                        <x-app.button @click="showPreview = true" variant="secondary" size="sm" class="w-full justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Preview Invoice
                        </x-app.button>
                        <x-app.button @click="saveAsDraft()" variant="ghost" size="sm" class="w-full justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Save as Draft
                        </x-app.button>
                    </div>
                </x-app.card>
            </div>
        </div>
    </div>

    {{-- Preview Modal --}}
    <x-app.modal name="showPreview" title="Invoice Preview" max-width="2xl">
        <div class="rounded-xl bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 p-6 text-white mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="text-xl font-bold">InvoicePro LLC</h4>
                    <p class="text-violet-200 text-sm mt-1">hello@invoicepro.com</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold">INVOICE</p>
                    <p class="text-violet-200">#INV-2024-0043</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase mb-2">Bill To</p>
                <template x-if="selectedClient">
                    <div>
                        <p class="font-semibold text-slate-900 dark:text-white" x-text="selectedClient.name"></p>
                        <p class="text-sm text-slate-500 dark:text-slate-400" x-text="selectedClient.email"></p>
                    </div>
                </template>
                <template x-if="!selectedClient">
                    <p class="text-slate-400 dark:text-slate-500 italic">No client selected</p>
                </template>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase mb-2">Details</p>
                <p class="text-sm text-slate-600 dark:text-slate-300">Issue Date: <span class="font-semibold text-slate-900 dark:text-white" x-text="date"></span></p>
                <p class="text-sm text-slate-600 dark:text-slate-300">Due Date: <span class="font-semibold text-slate-900 dark:text-white" x-text="dueDate"></span></p>
            </div>
        </div>
        <table class="w-full mb-6">
            <thead>
                <tr class="border-b border-slate-200 dark:border-slate-700">
                    <th class="text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase py-2">Item</th>
                    <th class="text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase py-2">Qty</th>
                    <th class="text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase py-2">Price</th>
                    <th class="text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase py-2">Total</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="item in items" :key="item.article">
                    <tr class="border-b border-slate-100 dark:border-slate-700/50">
                        <td class="py-3 text-sm text-slate-800 dark:text-slate-200" x-text="item.name"></td>
                        <td class="py-3 text-sm text-slate-600 dark:text-slate-400 text-center" x-text="item.quantity"></td>
                        <td class="py-3 text-sm text-slate-600 dark:text-slate-400 text-right" x-text="formatMoney(item.price)"></td>
                        <td class="py-3 text-sm font-semibold text-slate-900 dark:text-white text-right" x-text="formatMoney(item.quantity * item.price)"></td>
                    </tr>
                </template>
            </tbody>
        </table>
        <div class="flex justify-end">
            <div class="w-64 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500 dark:text-slate-400">Subtotal</span>
                    <span class="font-semibold text-slate-900 dark:text-white" x-text="formatMoney(subtotal)"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500 dark:text-slate-400">Tax (17%)</span>
                    <span class="font-semibold text-slate-900 dark:text-white" x-text="formatMoney(tax)"></span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t border-slate-200 dark:border-slate-700 pt-2">
                    <span class="text-slate-900 dark:text-white">Total</span>
                    <span class="text-violet-600 dark:text-violet-400" x-text="formatMoney(total)"></span>
                </div>
            </div>
        </div>
        <x-slot:footer>
            <x-app.button @click="showPreview = false" variant="secondary" size="sm">Close</x-app.button>
            <x-app.button variant="primary" size="sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download PDF
            </x-app.button>
        </x-slot:footer>
    </x-app.modal>

    {{-- Draft Saved Toast --}}
    <div x-show="draftSaved" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" class="fixed bottom-24 lg:bottom-8 right-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-4 py-3 rounded-xl shadow-xl shadow-emerald-500/30 flex items-center gap-2 z-[100]" x-cloak>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span class="text-sm font-medium">Draft saved successfully!</span>
    </div>
</form>

@push('scripts')
<script>
function invoiceForm() {
    return {
        selectedClient: null,
        search: '',
        open: false,
        draftSaved: false,
        clients: @json($clients),
        articles: @json($articles),
        currency: '{{ $defaults['currency'] }}',
        language: '{{ $defaults['language'] }}',
        date: '{{ date('Y-m-d') }}',
        dueDate: '{{ date('Y-m-d', strtotime('+' . $defaults['due_days'] . ' days')) }}',
        items: [],
        
        get filteredClients() {
            if (!this.search) return this.clients.slice(0, 10);
            return this.clients.filter(c => 
                c.name.toLowerCase().includes(this.search.toLowerCase()) || 
                c.email.toLowerCase().includes(this.search.toLowerCase())
            ).slice(0, 10);
        },

        get currencySymbol() {
            const symbols = { 'EUR': '€', 'USD': '$', 'BAM': 'KM' };
            return symbols[this.currency] || '';
        },

        selectClient(client) {
            this.selectedClient = client;
            this.open = false;
            this.search = '';
        },

        addItem() {
            this.items.push({
                article: '',
                name: '',
                description: '',
                quantity: 1,
                price: 0
            });
        },

        removeItem(index) {
            this.items.splice(index, 1);
        },

        selectArticle(index, articleId) {
            const article = this.articles.find(a => a.id == articleId);
            if (article) {
                this.items[index].article = article.id;
                this.items[index].name = article.name;
                this.items[index].price = article.unit_price / 100; // Assuming storage in cents
            }
        },

        get subtotal() {
            return this.items.reduce((sum, item) => sum + (item.quantity * item.price), 0);
        },

        get tax() {
            return this.subtotal * 0.17; // Assuming 17% default tax
        },

        get total() {
            return this.subtotal + this.tax;
        },

        formatMoney(amount) {
            return this.currencySymbol + amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        saveAsDraft() {
            this.draftSaved = true;
            setTimeout(() => { this.draftSaved = false; }, 3000);
        }
    }
}
</script>
@endpush
@endsection
