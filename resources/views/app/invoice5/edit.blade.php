@extends('app.invoice5.partials.layout')

@section('title', 'Edit Invoice INV-2024-0042 - InvoicePro')
@section('page-title', 'Edit Invoice')
@section('page-subtitle', 'INV-2024-0042')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-invoice5.button href="/templates/invoice5/1" variant="secondary" size="sm" class="hidden sm:flex">
        Cancel
    </x-invoice5.button>
    <x-invoice5.button type="submit" form="invoice-form" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Update Invoice
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
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Update client details</p>
                        </div>
                    </div>
                    
                    <div class="p-4 rounded-xl bg-gradient-to-r from-slate-50 to-slate-100/50 dark:from-slate-700/50 dark:to-slate-700/30 border border-slate-200/60 dark:border-slate-600/40">
                        <div class="flex items-start gap-4">
                            <x-invoice5.avatar name="Acme Corporation" size="md" />
                            <div class="flex-1">
                                <p class="font-bold text-slate-900 dark:text-white">Acme Corporation</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">billing@acme.com</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">123 Business Ave, New York, NY 10001</p>
                            </div>
                            <x-invoice5.button variant="ghost" size="xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                Change
                            </x-invoice5.button>
                        </div>
                    </div>
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
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Modify products or services</p>
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

            {{-- Danger Zone --}}
            <div class="stagger-3 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <x-invoice5.card class="border-rose-200 dark:border-rose-900/50">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center text-white shadow-lg shadow-rose-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-rose-700 dark:text-rose-400">Danger Zone</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Irreversible actions</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60">
                        <div>
                            <p class="text-sm font-medium text-rose-800 dark:text-rose-200">Delete this invoice</p>
                            <p class="text-xs text-rose-600/70 dark:text-rose-300/70">Once deleted, this cannot be undone</p>
                        </div>
                        <x-invoice5.button variant="danger" size="sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete Invoice
                        </x-invoice5.button>
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
                        <x-invoice5.input label="Invoice Number" value="INV-2024-0042" readonly class="bg-slate-50 dark:bg-slate-700" />
                        <x-invoice5.input label="Issue Date" type="date" value="2024-01-15" />
                        <x-invoice5.input label="Due Date" type="date" value="2024-02-14" />
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Status</label>
                            <select class="w-full h-10 px-3 rounded-xl bg-white/60 dark:bg-slate-700/60 backdrop-blur-sm border border-slate-200/60 dark:border-slate-600/60 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all">
                                <option value="draft">Draft</option>
                                <option value="pending">Pending</option>
                                <option value="paid" selected>Paid</option>
                                <option value="overdue">Overdue</option>
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

            {{-- Activity --}}
            <div class="stagger-3 page-enter opacity-0 pb-24 lg:pb-0" style="animation-fill-mode: forwards;">
                <x-invoice5.card>
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
                </x-invoice5.card>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function invoiceForm() {
    return {
        articles: [
            { id: 1, name: 'Website Design & Development', price: 1500 },
            { id: 2, name: 'Logo Design', price: 500 },
            { id: 3, name: 'SEO Optimization', price: 300 },
            { id: 4, name: 'Hosting Setup', price: 150 },
            { id: 5, name: 'Maintenance (Monthly)', price: 200 },
        ],
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
