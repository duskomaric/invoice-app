@extends('app.invoice5.partials.layout')

@section('title', 'Create Invoice - InvoicePro')
@section('page-title', 'Create Invoice')
@section('page-subtitle', 'Create a new invoice for your client')
@section('page-badge', 'New')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-invoice5.button href="/templates/invoice5" variant="secondary" size="sm" class="hidden sm:flex">
        Cancel
    </x-invoice5.button>
    <x-invoice5.button type="submit" form="invoice-form" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Save Invoice
    </x-invoice5.button>
</div>
@endsection

@section('content')
<form id="invoice-form" x-data="invoiceForm()" class="space-y-4">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        {{-- Main Content --}}
        <div class="xl:col-span-2 space-y-4">
            {{-- Client Selection --}}
            <div class="stagger-1 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <x-invoice5.card>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-fuchsia-600 flex items-center justify-center text-white shadow-lg shadow-violet-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Client Information</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Select or add a client</p>
                        </div>
                    </div>
                    
                    <div class="relative" x-data="{ open: false, search: '' }" @click.away="open = false">
                        <div class="relative" x-show="!selectedClient">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" x-model="search" @focus="open = true" @click="open = true" placeholder="Search or select a client..." class="w-full h-9 pl-9 pr-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all">
                        </div>
                        <div x-show="open && !selectedClient" x-transition class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-slate-200 dark:border-slate-700 py-1 z-[80] max-h-64 overflow-y-auto" x-cloak>
                            <a href="#" @click.prevent class="flex items-center gap-2.5 px-3 py-2 hover:bg-violet-50 dark:hover:bg-violet-900/20 border-b border-slate-100 dark:border-slate-700 text-violet-600 dark:text-violet-400">
                                <div class="w-8 h-8 rounded-lg border-2 border-dashed border-violet-300 dark:border-violet-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                <span class="font-medium text-xs">Add new client</span>
                            </a>
                            <button type="button" @click.stop="selectedClient = {name: 'Acme Corporation', email: 'billing@acme.com', address: '123 Business Ave, New York, NY 10001'}; open = false" class="w-full flex items-center gap-2.5 px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700/50 text-left">
                                <x-invoice5.avatar name="Acme Corporation" size="xs" />
                                <div>
                                    <p class="font-medium text-slate-700 dark:text-white text-xs">Acme Corporation</p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">billing@acme.com</p>
                                </div>
                            </button>
                            <button type="button" @click.stop="selectedClient = {name: 'TechStart Inc', email: 'accounts@techstart.io', address: '456 Tech Blvd, San Francisco, CA 94102'}; open = false" class="w-full flex items-center gap-2.5 px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-700/50 text-left">
                                <x-invoice5.avatar name="TechStart Inc" size="xs" variant="emerald" />
                                <div>
                                    <p class="font-medium text-slate-700 dark:text-white text-xs">TechStart Inc</p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">accounts@techstart.io</p>
                                </div>
                            </button>
                        </div>
                    </div>
                    
                    <template x-if="selectedClient">
                        <div class="p-3 rounded-lg bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600">
                            <div class="flex items-start gap-3">
                                <x-invoice5.avatar x-bind:name="selectedClient.name" size="sm" />
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-800 dark:text-white text-sm" x-text="selectedClient.name"></p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400" x-text="selectedClient.email"></p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5" x-text="selectedClient.address"></p>
                                </div>
                                <button type="button" @click="selectedClient = null" class="p-1.5 rounded-md hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-400 dark:text-slate-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </x-invoice5.card>
            </div>

            {{-- Invoice Items --}}
            <div class="stagger-2 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <x-invoice5.card>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-cyan-600 flex items-center justify-center text-white shadow-lg shadow-sky-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Invoice Items</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Add products or services</p>
                        </div>
                    </div>
                    
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
                            <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-700/30 border border-slate-200/60 dark:border-slate-600/40">
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-start">
                                    <div class="lg:col-span-5">
                                        <label class="lg:hidden text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Article</label>
                                        <div class="relative">
                                            <select x-model="item.article" @change="selectArticle(index, $event.target.value)" class="w-full h-9 px-3 rounded-lg bg-white/80 dark:bg-slate-700/60 border border-slate-200/60 dark:border-slate-600/60 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 appearance-none cursor-pointer transition-all">
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
                                        <input type="number" x-model="item.quantity" min="1" class="w-full h-9 px-3 rounded-lg bg-white/80 dark:bg-slate-700/60 border border-slate-200/60 dark:border-slate-600/60 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all">
                                    </div>
                                    <div class="lg:col-span-2">
                                        <label class="lg:hidden text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Price</label>
                                        <div class="relative">
                                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs">€</span>
                                            <input type="number" x-model="item.price" step="0.01" class="w-full h-9 pl-7 pr-2 rounded-lg bg-white/80 dark:bg-slate-700/60 border border-slate-200/60 dark:border-slate-600/60 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all">
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
                    <button type="button" @click="addItem()" class="w-full mt-3 h-10 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 hover:border-violet-400 dark:hover:border-violet-500 hover:bg-violet-50/50 dark:hover:bg-violet-900/20 text-slate-500 dark:text-slate-400 hover:text-violet-600 dark:hover:text-violet-400 text-xs font-medium transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Item
                    </button>
                </x-invoice5.card>
            </div>

            {{-- Notes --}}
            <div class="stagger-3 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <x-invoice5.card>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-lg shadow-amber-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Notes & Terms</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Additional information</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Notes to Client</label>
                            <textarea rows="2" placeholder="Add any notes..." class="w-full px-3 py-2 rounded-xl bg-white/60 dark:bg-slate-700/60 backdrop-blur-sm border border-slate-200/60 dark:border-slate-600/60 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Payment Terms</label>
                            <textarea rows="2" class="w-full px-3 py-2 rounded-xl bg-white/60 dark:bg-slate-700/60 backdrop-blur-sm border border-slate-200/60 dark:border-slate-600/60 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all resize-none">Payment is due within 30 days of invoice date.</textarea>
                        </div>
                    </div>
                </x-invoice5.card>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            {{-- Invoice Details --}}
            <div class="stagger-1 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <x-invoice5.card>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Invoice Details
                    </h3>
                    <div class="space-y-3">
                        <x-invoice5.input label="Invoice Number" value="INV-2024-0043" readonly class="bg-slate-50 dark:bg-slate-700" />
                        <x-invoice5.input label="Issue Date" type="date" :value="date('Y-m-d')" />
                        <x-invoice5.input label="Due Date" type="date" :value="date('Y-m-d', strtotime('+30 days'))" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Currency</label>
                            <select class="w-full h-10 px-3 rounded-xl bg-white/60 dark:bg-slate-700/60 backdrop-blur-sm border border-slate-200/60 dark:border-slate-600/60 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all">
                                <option value="EUR">EUR - Euro</option>
                                <option value="USD">USD - US Dollar</option>
                                <option value="GBP">GBP - British Pound</option>
                            </select>
                        </div>
                    </div>
                </x-invoice5.card>
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

            {{-- Quick Actions --}}
            <div class="stagger-3 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <x-invoice5.card>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Quick Actions
                    </h3>
                    <div class="space-y-2">
                        <x-invoice5.button @click="showPreview = true" variant="secondary" size="sm" class="w-full justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Preview Invoice
                        </x-invoice5.button>
                        <x-invoice5.button @click="saveAsDraft()" variant="ghost" size="sm" class="w-full justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Save as Draft
                        </x-invoice5.button>
                    </div>
                </x-invoice5.card>
            </div>
        </div>
    </div>

    {{-- Preview Modal --}}
    <x-invoice5.modal name="showPreview" title="Invoice Preview" max-width="2xl">
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
                <p class="text-sm text-slate-600 dark:text-slate-300">Issue Date: <span class="font-semibold text-slate-900 dark:text-white">{{ date('M d, Y') }}</span></p>
                <p class="text-sm text-slate-600 dark:text-slate-300">Due Date: <span class="font-semibold text-slate-900 dark:text-white">{{ date('M d, Y', strtotime('+30 days')) }}</span></p>
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
                        <td class="py-3 text-sm text-slate-800 dark:text-slate-200" x-text="getArticleName(item.article)"></td>
                        <td class="py-3 text-sm text-slate-600 dark:text-slate-400 text-center" x-text="item.quantity"></td>
                        <td class="py-3 text-sm text-slate-600 dark:text-slate-400 text-right" x-text="'€' + item.price.toFixed(2)"></td>
                        <td class="py-3 text-sm font-semibold text-slate-900 dark:text-white text-right" x-text="'€' + (item.quantity * item.price).toFixed(2)"></td>
                    </tr>
                </template>
            </tbody>
        </table>
        <div class="flex justify-end">
            <div class="w-64 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500 dark:text-slate-400">Subtotal</span>
                    <span class="font-semibold text-slate-900 dark:text-white" x-text="'€' + subtotal.toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500 dark:text-slate-400">Tax (17%)</span>
                    <span class="font-semibold text-slate-900 dark:text-white" x-text="'€' + tax.toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t border-slate-200 dark:border-slate-700 pt-2">
                    <span class="text-slate-900 dark:text-white">Total</span>
                    <span class="text-violet-600 dark:text-violet-400" x-text="'€' + total.toFixed(2)"></span>
                </div>
            </div>
        </div>
        <x-slot:footer>
            <x-invoice5.button @click="showPreview = false" variant="secondary" size="sm">Close</x-invoice5.button>
            <x-invoice5.button variant="primary" size="sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download PDF
            </x-invoice5.button>
        </x-slot:footer>
    </x-invoice5.modal>

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
        showPreview: false,
        draftSaved: false,
        articles: [
            { id: 1, name: 'Website Design & Development', price: 1500 },
            { id: 2, name: 'Logo Design', price: 500 },
            { id: 3, name: 'SEO Optimization', price: 300 },
            { id: 4, name: 'Hosting Setup', price: 150 },
            { id: 5, name: 'Maintenance (Monthly)', price: 200 },
            { id: 6, name: 'Content Writing', price: 100 },
            { id: 7, name: 'Social Media Setup', price: 250 },
            { id: 8, name: 'Email Marketing Setup', price: 350 },
        ],
        items: [
            { article: 1, quantity: 1, price: 1500 },
            { article: 2, quantity: 1, price: 500 },
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
        },
        getArticleName(articleId) {
            const article = this.articles.find(a => a.id == articleId);
            return article ? article.name : 'Unknown item';
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
