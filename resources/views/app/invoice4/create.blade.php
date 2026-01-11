@extends('app.invoice4.partials.layout')

@section('title', 'Create Invoice - InvoicePro')
@section('page-title', 'Create Invoice')
@section('page-badge', 'New')

@section('header-actions')
<div class="flex items-center gap-2">
    <a href="/templates/invoice4" class="hidden sm:flex items-center gap-1.5 h-9 px-3 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-medium transition-colors">
        Cancel
    </a>
    <button type="submit" form="invoice-form" class="flex items-center gap-1.5 h-9 px-4 rounded-lg gradient-bg text-white text-xs font-semibold shadow-md shadow-violet-500/25 hover:shadow-lg transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Save Invoice
    </button>
</div>
@endsection

@section('content')
<form id="invoice-form" x-data="invoiceForm()" class="space-y-4">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        {{-- Main Content --}}
        <div class="xl:col-span-2 space-y-6">
            {{-- Client Selection --}}
            <div class="bg-white rounded-2xl card-shadow border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg gradient-primary flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    Client Information
                </h3>
                <div class="relative" x-data="{ open: false, search: '' }">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" x-model="search" @focus="open = true" placeholder="Search or select a client..." class="w-full h-12 pl-12 pr-4 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm">
                    </div>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-slate-200/60 py-2 z-20 max-h-64 overflow-y-auto" x-cloak>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-50 border-b border-slate-100 text-indigo-600">
                            <div class="w-10 h-10 rounded-full border-2 border-dashed border-indigo-300 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <span class="font-medium">Add new client</span>
                        </a>
                        <button type="button" @click="selectedClient = {name: 'Acme Corporation', email: 'billing@acme.com', address: '123 Business Ave, New York, NY 10001'}; open = false" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-left">
                            <div class="w-10 h-10 rounded-full gradient-primary flex items-center justify-center text-white text-xs font-bold">AC</div>
                            <div>
                                <p class="font-medium text-slate-800">Acme Corporation</p>
                                <p class="text-xs text-slate-500">billing@acme.com</p>
                            </div>
                        </button>
                        <button type="button" @click="selectedClient = {name: 'TechStart Inc', email: 'accounts@techstart.io', address: '456 Tech Blvd, San Francisco, CA 94102'}; open = false" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-left">
                            <div class="w-10 h-10 rounded-full gradient-info flex items-center justify-center text-white text-xs font-bold">TS</div>
                            <div>
                                <p class="font-medium text-slate-800">TechStart Inc</p>
                                <p class="text-xs text-slate-500">accounts@techstart.io</p>
                            </div>
                        </button>
                        <button type="button" @click="selectedClient = {name: 'Global Services Ltd', email: 'finance@global.com', address: '789 Global Way, London, UK'}; open = false" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-slate-50 text-left">
                            <div class="w-10 h-10 rounded-full gradient-success flex items-center justify-center text-white text-xs font-bold">GS</div>
                            <div>
                                <p class="font-medium text-slate-800">Global Services Ltd</p>
                                <p class="text-xs text-slate-500">finance@global.com</p>
                            </div>
                        </button>
                    </div>
                </div>
                <template x-if="selectedClient">
                    <div class="mt-4 p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center text-white font-bold shadow-md" x-text="selectedClient.name.substring(0,2).toUpperCase()"></div>
                            <div class="flex-1">
                                <p class="font-bold text-slate-900" x-text="selectedClient.name"></p>
                                <p class="text-sm text-slate-500" x-text="selectedClient.email"></p>
                                <p class="text-sm text-slate-500 mt-1" x-text="selectedClient.address"></p>
                            </div>
                            <button type="button" @click="selectedClient = null" class="p-2 rounded-lg hover:bg-slate-200 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Invoice Items --}}
            <div class="card-glass rounded-xl border border-white/60 p-4">
                <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    Invoice Items
                </h3>
                
                {{-- Items Header (Desktop) --}}
                <div class="hidden lg:grid lg:grid-cols-12 gap-3 mb-2 px-1">
                    <div class="col-span-5 text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Article</div>
                    <div class="col-span-2 text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Qty</div>
                    <div class="col-span-2 text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Price</div>
                    <div class="col-span-2 text-[10px] font-semibold text-slate-500 uppercase tracking-wider text-right">Total</div>
                    <div class="col-span-1"></div>
                </div>

                {{-- Items --}}
                <div class="space-y-2">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="bg-slate-50/80 rounded-lg p-3 border border-slate-200/60">
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-start">
                                <div class="lg:col-span-5">
                                    <label class="lg:hidden text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Article</label>
                                    <div class="relative">
                                        <select x-model="item.article" @change="selectArticle(index, $event.target.value)" class="w-full h-9 px-3 rounded-lg border border-slate-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 outline-none transition-all text-xs bg-white appearance-none cursor-pointer">
                                            <option value="">Select article...</option>
                                            <template x-for="article in articles" :key="article.id">
                                                <option :value="article.id" x-text="article.name" :selected="item.article == article.id"></option>
                                            </template>
                                        </select>
                                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                                <div class="lg:col-span-2">
                                    <label class="lg:hidden text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Qty</label>
                                    <input type="number" x-model="item.quantity" min="1" class="w-full h-9 px-3 rounded-lg border border-slate-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 outline-none transition-all text-xs">
                                </div>
                                <div class="lg:col-span-2">
                                    <label class="lg:hidden text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Price</label>
                                    <div class="relative">
                                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">€</span>
                                        <input type="number" x-model="item.price" step="0.01" class="w-full h-9 pl-7 pr-2 rounded-lg border border-slate-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 outline-none transition-all text-xs">
                                    </div>
                                </div>
                                <div class="lg:col-span-2 flex items-center lg:justify-end h-9">
                                    <label class="lg:hidden text-[10px] font-semibold text-slate-500 uppercase tracking-wider mr-auto">Total</label>
                                    <span class="text-sm font-bold text-slate-900" x-text="'€' + (item.quantity * item.price).toFixed(2)"></span>
                                </div>
                                <div class="lg:col-span-1 flex justify-end">
                                    <button type="button" @click="removeItem(index)" class="p-1.5 rounded-md hover:bg-rose-100 text-slate-400 hover:text-rose-600 transition-colors" :disabled="items.length === 1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Add Item Button --}}
                <button type="button" @click="addItem()" class="w-full mt-3 h-9 rounded-lg border-2 border-dashed border-slate-300 hover:border-violet-400 hover:bg-violet-50/50 text-slate-500 hover:text-violet-600 text-xs font-medium transition-all flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Item
                </button>
            </div>

            {{-- Notes & Terms --}}
            <div class="bg-white rounded-2xl card-shadow border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg gradient-warning flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    Notes & Terms
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Notes to Client</label>
                        <textarea rows="3" placeholder="Add any notes for your client..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Payment Terms</label>
                        <textarea rows="2" placeholder="e.g., Payment due within 30 days..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm resize-none">Payment is due within 30 days of invoice date.</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Invoice Details --}}
            <div class="bg-white rounded-2xl card-shadow border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Invoice Details</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Invoice Number</label>
                        <input type="text" value="INV-2024-0007" class="w-full h-11 px-4 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 text-sm" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Issue Date</label>
                        <input type="date" value="{{ date('Y-m-d') }}" class="w-full h-11 px-4 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Due Date</label>
                        <input type="date" value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="w-full h-11 px-4 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Currency</label>
                        <select class="w-full h-11 px-4 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm bg-white">
                            <option value="EUR">EUR - Euro</option>
                            <option value="USD">USD - US Dollar</option>
                            <option value="GBP">GBP - British Pound</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 text-white">
                <h3 class="text-lg font-bold mb-4">Summary</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-indigo-200">Subtotal</span>
                        <span class="font-semibold" x-text="'€' + subtotal.toFixed(2)"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-indigo-200">Tax (17%)</span>
                        <span class="font-semibold" x-text="'€' + tax.toFixed(2)"></span>
                    </div>
                    <div class="border-t border-white/20 pt-3 mt-3">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold">Total</span>
                            <span class="text-2xl font-bold" x-text="'€' + total.toFixed(2)"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="card-glass rounded-xl border border-white/60 p-4">
                <h3 class="text-sm font-bold text-slate-900 mb-3">Quick Actions</h3>
                <div class="space-y-2">
                    <button type="button" @click="showPreview = true" class="w-full h-9 rounded-lg border border-slate-200 hover:bg-violet-50 hover:border-violet-300 text-slate-700 text-xs font-medium transition-colors flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Preview Invoice
                    </button>
                    <button type="button" @click="saveAsDraft()" class="w-full h-9 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium transition-colors flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Save as Draft
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Preview Modal --}}
    <div x-show="showPreview" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click.self="showPreview = false" x-cloak>
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-auto" @click.stop>
            <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900">Invoice Preview</h3>
                <button @click="showPreview = false" class="p-2 rounded-lg hover:bg-slate-100 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                <div class="bg-gradient-to-r from-violet-600 to-purple-700 rounded-xl p-6 text-white mb-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-xl font-bold">InvoicePro LLC</h4>
                            <p class="text-violet-200 text-sm mt-1">hello@invoicepro.com</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold">INVOICE</p>
                            <p class="text-violet-200">#INV-2024-0007</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-2">Bill To</p>
                        <template x-if="selectedClient">
                            <div>
                                <p class="font-semibold text-slate-900" x-text="selectedClient.name"></p>
                                <p class="text-sm text-slate-500" x-text="selectedClient.email"></p>
                            </div>
                        </template>
                        <template x-if="!selectedClient">
                            <p class="text-slate-400 italic">No client selected</p>
                        </template>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-2">Details</p>
                        <p class="text-sm text-slate-600">Issue Date: <span class="font-semibold text-slate-900">{{ date('M d, Y') }}</span></p>
                        <p class="text-sm text-slate-600">Due Date: <span class="font-semibold text-slate-900">{{ date('M d, Y', strtotime('+30 days')) }}</span></p>
                    </div>
                </div>
                <table class="w-full mb-6">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left text-xs font-semibold text-slate-500 uppercase py-2">Item</th>
                            <th class="text-center text-xs font-semibold text-slate-500 uppercase py-2">Qty</th>
                            <th class="text-right text-xs font-semibold text-slate-500 uppercase py-2">Price</th>
                            <th class="text-right text-xs font-semibold text-slate-500 uppercase py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="item in items" :key="item.article">
                            <tr class="border-b border-slate-100">
                                <td class="py-3 text-sm text-slate-800" x-text="getArticleName(item.article)"></td>
                                <td class="py-3 text-sm text-slate-600 text-center" x-text="item.quantity"></td>
                                <td class="py-3 text-sm text-slate-600 text-right" x-text="'€' + item.price.toFixed(2)"></td>
                                <td class="py-3 text-sm font-semibold text-slate-900 text-right" x-text="'€' + (item.quantity * item.price).toFixed(2)"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="flex justify-end">
                    <div class="w-64 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="font-semibold" x-text="'€' + subtotal.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Tax (17%)</span>
                            <span class="font-semibold" x-text="'€' + tax.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t border-slate-200 pt-2">
                            <span>Total</span>
                            <span class="text-violet-600" x-text="'€' + total.toFixed(2)"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-100 px-6 py-4 flex justify-end gap-2">
                <button @click="showPreview = false" class="h-9 px-4 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-medium">Close</button>
                <button class="h-9 px-4 rounded-lg gradient-bg text-white text-xs font-semibold shadow-md">Download PDF</button>
            </div>
        </div>
    </div>

    {{-- Draft Saved Toast --}}
    <div x-show="draftSaved" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="fixed bottom-24 lg:bottom-8 right-4 bg-emerald-600 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 z-50" x-cloak>
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
