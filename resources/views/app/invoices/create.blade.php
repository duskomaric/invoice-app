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
                <x-documents.client-information />
            </div>

            {{-- Invoice Items --}}
            <div class="stagger-2 page-enter opacity-0 relative z-10" style="animation-fill-mode: forwards;">
                <x-documents.items items="items" currency="currency" title="Invoice Items" />
            </div>

            {{-- Danger Zone --}}
            <div class="stagger-3 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <x-documents.danger-zone 
                    text="Delete this invoice" 
                    subtext="Once deleted, this cannot be undone" 
                    button-text="Delete Invoice" 
                />
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            {{-- Invoice Details --}}
            <div class="stagger-1 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <x-documents.settings 
                    :preview-number="$previewNumber" 
                    :currencies="$currencies" 
                />
            </div>

            {{-- Summary --}}
            <div class="stagger-2 page-enter opacity-0" style="animation-fill-mode: forwards;">
                <x-documents.summary />
            </div>

            {{-- Activity --}}
            <div class="stagger-3 page-enter opacity-0 pb-24 lg:pb-0" style="animation-fill-mode: forwards;">
                <x-documents.activity />
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
        open: true,
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

        filteredArticles(query) {
            if (!query) return this.articles.slice(0, 10);
            return this.articles.filter(a => 
                a.name.toLowerCase().includes(query.toLowerCase())
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
                article: null,
                name: '',
                description: '',
                quantity: 1,
                price: 0
            });
        },

        removeItem(index) {
            this.items.splice(index, 1);
        },

        selectArticle(index, article) {
            this.items[index].article = article.id;
            this.items[index].name = article.name;
            // Use prices_meta if available, otherwise assume 0 or fallback
            // price is stored in prices_meta as 'CURRENCY' => float
            this.items[index].price = article.prices_meta?.[this.currency] || 0; 
            
            // Close the search dropdown for this item (handled by click event on parent)
        },

        updateCurrency(newCurrency) {
            this.currency = newCurrency;
            // Update prices for all selected articles
            this.items.forEach(item => {
                if (item.article) {
                    const article = this.articles.find(a => a.id == item.article);
                    if (article) {
                        item.price = article.prices_meta?.[this.currency] || 0;
                    }
                }
            });
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
        
        formatPrice(amount) {
            return amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        saveAsDraft() {
            this.draftSaved = true;
            setTimeout(() => { this.draftSaved = false; }, 3000);
        },

        init() {
            this.addItem(); // Add one empty item by default
        }
    }
}
</script>
@endpush
@endsection
