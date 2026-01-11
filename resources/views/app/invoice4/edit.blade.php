@extends('app.invoice4.partials.layout')

@section('title', 'Edit Invoice INV-2024-0001 - InvoicePro')
@section('page-title', 'Edit Invoice')
@section('page-subtitle', 'Update invoice INV-2024-0001')

@section('header-actions')
<div class="flex items-center gap-2">
    <a href="/templates/invoice4/1" class="hidden sm:flex items-center gap-2 h-10 px-4 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium transition-colors">
        Cancel
    </a>
    <button type="submit" form="invoice-form" class="flex items-center gap-2 h-10 px-5 rounded-xl btn-primary text-white text-sm font-semibold shadow-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        Update Invoice
    </button>
</div>
@endsection

@section('content')
<form id="invoice-form" x-data="invoiceEditForm()" class="space-y-6">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
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
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center text-white font-bold shadow-md">AC</div>
                        <div class="flex-1">
                            <p class="font-bold text-slate-900">Acme Corporation</p>
                            <p class="text-sm text-slate-500">billing@acme.com</p>
                            <p class="text-sm text-slate-500 mt-1">123 Business Avenue, New York, NY 10001</p>
                        </div>
                        <button type="button" class="p-2 rounded-lg hover:bg-slate-200 text-indigo-600 text-sm font-medium">
                            Change
                        </button>
                    </div>
                </div>
            </div>

            {{-- Invoice Items --}}
            <div class="bg-white rounded-2xl card-shadow border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg gradient-info flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    Invoice Items
                </h3>
                
                <div class="hidden lg:grid lg:grid-cols-12 gap-4 mb-3 px-2">
                    <div class="col-span-5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Description</div>
                    <div class="col-span-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Quantity</div>
                    <div class="col-span-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Price</div>
                    <div class="col-span-2 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Total</div>
                    <div class="col-span-1"></div>
                </div>

                <div class="space-y-3">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/60">
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
                                <div class="lg:col-span-5">
                                    <label class="lg:hidden text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Description</label>
                                    <input type="text" x-model="item.description" placeholder="Item description" class="w-full h-11 px-4 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm">
                                </div>
                                <div class="lg:col-span-2">
                                    <label class="lg:hidden text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Quantity</label>
                                    <input type="number" x-model="item.quantity" min="1" class="w-full h-11 px-4 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm">
                                </div>
                                <div class="lg:col-span-2">
                                    <label class="lg:hidden text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Price</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">€</span>
                                        <input type="number" x-model="item.price" step="0.01" class="w-full h-11 pl-8 pr-4 rounded-lg border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm">
                                    </div>
                                </div>
                                <div class="lg:col-span-2 flex items-center lg:justify-end">
                                    <label class="lg:hidden text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 block mr-auto">Total</label>
                                    <span class="text-lg font-bold text-slate-900" x-text="'€' + (item.quantity * item.price).toFixed(2)"></span>
                                </div>
                                <div class="lg:col-span-1 flex justify-end">
                                    <button type="button" @click="removeItem(index)" class="p-2 rounded-lg hover:bg-red-100 text-slate-400 hover:text-red-600 transition-colors" :disabled="items.length === 1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <button type="button" @click="addItem()" class="w-full mt-4 h-12 rounded-xl border-2 border-dashed border-slate-300 hover:border-indigo-400 hover:bg-indigo-50/50 text-slate-500 hover:text-indigo-600 font-medium transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
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
                        <textarea rows="3" placeholder="Add any notes..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm resize-none">Thank you for your business!</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Payment Terms</label>
                        <textarea rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm resize-none">Payment is due within 30 days of invoice date.</textarea>
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
                        <input type="text" value="INV-2024-0001" class="w-full h-11 px-4 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 text-sm" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                        <select class="w-full h-11 px-4 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm bg-white">
                            <option value="draft">Draft</option>
                            <option value="sent">Sent</option>
                            <option value="pending">Pending</option>
                            <option value="paid" selected>Paid</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Issue Date</label>
                        <input type="date" value="2024-01-15" class="w-full h-11 px-4 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Due Date</label>
                        <input type="date" value="2024-02-15" class="w-full h-11 px-4 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Currency</label>
                        <select class="w-full h-11 px-4 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm bg-white">
                            <option value="EUR" selected>EUR - Euro</option>
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

            {{-- Danger Zone --}}
            <div class="bg-white rounded-2xl card-shadow border border-red-200 p-6">
                <h3 class="text-lg font-bold text-red-600 mb-4">Danger Zone</h3>
                <p class="text-sm text-slate-600 mb-4">Once you delete this invoice, there is no going back.</p>
                <button type="button" class="w-full h-11 rounded-xl border border-red-200 hover:bg-red-50 text-red-600 text-sm font-medium transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete Invoice
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function invoiceEditForm() {
    return {
        items: [
            { description: 'Website Design & Development', quantity: 1, price: 1500 },
            { description: 'Logo Design', quantity: 1, price: 500 },
            { description: 'SEO Optimization', quantity: 1, price: 300 },
            { description: 'Hosting Setup', quantity: 1, price: 150 },
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
            this.items.push({ description: '', quantity: 1, price: 0 });
        },
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        }
    }
}
</script>
@endpush
@endsection
