@extends('app.invoice4.partials.layout')

@section('title', 'Invoice INV-2024-0001 - InvoicePro')
@section('page-title', 'Invoice Details')
@section('page-subtitle', 'View and manage invoice INV-2024-0001')

@section('header-actions')
<div class="flex items-center gap-2">
    <button onclick="window.print()" class="hidden sm:flex items-center gap-2 h-10 px-4 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        Print
    </button>
    <button class="hidden sm:flex items-center gap-2 h-10 px-4 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Download PDF
    </button>
    <a href="/templates/invoice4/1/edit" class="flex items-center gap-2 h-10 px-5 rounded-xl btn-primary text-white text-sm font-semibold shadow-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit
    </a>
</div>
@endsection

@section('content')
@php
    $invoice = [
        'number' => 'INV-2024-0001',
        'status' => 'paid',
        'date' => '2024-01-15',
        'due_date' => '2024-02-15',
        'client' => [
            'name' => 'Acme Corporation',
            'email' => 'billing@acme.com',
            'address' => '123 Business Avenue',
            'city' => 'New York, NY 10001',
            'country' => 'United States',
            'vat' => 'US123456789',
        ],
        'company' => [
            'name' => 'InvoicePro LLC',
            'email' => 'hello@invoicepro.com',
            'address' => '456 Tech Street',
            'city' => 'San Francisco, CA 94102',
            'country' => 'United States',
            'vat' => 'US987654321',
        ],
        'items' => [
            ['description' => 'Website Design & Development', 'details' => 'Full responsive website with 10 pages', 'quantity' => 1, 'price' => 150000],
            ['description' => 'Logo Design', 'details' => 'Brand identity package with 3 concepts', 'quantity' => 1, 'price' => 50000],
            ['description' => 'SEO Optimization', 'details' => 'On-page SEO for all pages', 'quantity' => 1, 'price' => 30000],
            ['description' => 'Hosting Setup', 'details' => 'Premium hosting configuration', 'quantity' => 1, 'price' => 15000],
        ],
        'currency' => 'EUR',
        'tax_rate' => 17,
    ];
    $subtotal = collect($invoice['items'])->sum(fn($item) => $item['quantity'] * $item['price']);
    $tax = $subtotal * ($invoice['tax_rate'] / 100);
    $total = $subtotal + $tax;
    $statusColors = [
        'paid' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
        'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
        'sent' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
        'overdue' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200'],
        'draft' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'border' => 'border-slate-200'],
    ];
@endphp

<div class="max-w-4xl mx-auto">
    {{-- Action Bar (Mobile) --}}
    <div class="lg:hidden flex items-center gap-2 mb-4 overflow-x-auto pb-2">
        <button onclick="window.print()" class="flex-shrink-0 flex items-center gap-2 h-10 px-4 rounded-xl border border-slate-200 bg-white text-slate-600 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print
        </button>
        <button class="flex-shrink-0 flex items-center gap-2 h-10 px-4 rounded-xl border border-slate-200 bg-white text-slate-600 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            PDF
        </button>
        <button class="flex-shrink-0 flex items-center gap-2 h-10 px-4 rounded-xl border border-slate-200 bg-white text-slate-600 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Send
        </button>
    </div>

    {{-- Invoice Document --}}
    <div class="bg-white rounded-2xl card-shadow border border-slate-100 overflow-hidden print:shadow-none print:border-0 print:rounded-none">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-700 px-8 py-10 text-white print:bg-indigo-600">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold">{{ $invoice['company']['name'] }}</h1>
                            <p class="text-indigo-200 text-sm">{{ $invoice['company']['email'] }}</p>
                        </div>
                    </div>
                    <p class="text-indigo-200 text-sm">{{ $invoice['company']['address'] }}</p>
                    <p class="text-indigo-200 text-sm">{{ $invoice['company']['city'] }}, {{ $invoice['company']['country'] }}</p>
                    <p class="text-indigo-200 text-sm mt-1">VAT: {{ $invoice['company']['vat'] }}</p>
                </div>
                <div class="text-left sm:text-right">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $statusColors[$invoice['status']]['bg'] }} {{ $statusColors[$invoice['status']]['text'] }} text-sm font-bold mb-4">
                        <span class="w-2 h-2 rounded-full bg-current"></span>
                        {{ strtoupper($invoice['status']) }}
                    </div>
                    <h2 class="text-4xl font-bold tracking-tight">INVOICE</h2>
                    <p class="text-xl text-indigo-200 mt-1">#{{ $invoice['number'] }}</p>
                </div>
            </div>
        </div>

        {{-- Invoice Info & Client --}}
        <div class="px-8 py-8 border-b border-slate-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Bill To</p>
                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                        <p class="text-lg font-bold text-slate-900">{{ $invoice['client']['name'] }}</p>
                        <p class="text-slate-600 mt-1">{{ $invoice['client']['email'] }}</p>
                        <p class="text-slate-500 text-sm mt-3">{{ $invoice['client']['address'] }}</p>
                        <p class="text-slate-500 text-sm">{{ $invoice['client']['city'] }}, {{ $invoice['client']['country'] }}</p>
                        <p class="text-slate-500 text-sm mt-1">VAT: {{ $invoice['client']['vat'] }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Invoice Details</p>
                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Invoice Number</span>
                            <span class="font-semibold text-slate-900">{{ $invoice['number'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Issue Date</span>
                            <span class="font-semibold text-slate-900">{{ \Carbon\Carbon::parse($invoice['date'])->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Due Date</span>
                            <span class="font-semibold text-slate-900">{{ \Carbon\Carbon::parse($invoice['due_date'])->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Currency</span>
                            <span class="font-semibold text-slate-900">{{ $invoice['currency'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="px-8 py-8">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Items</p>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-slate-200">
                            <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wider py-3">Description</th>
                            <th class="text-center text-xs font-bold text-slate-500 uppercase tracking-wider py-3 w-24">Qty</th>
                            <th class="text-right text-xs font-bold text-slate-500 uppercase tracking-wider py-3 w-32">Price</th>
                            <th class="text-right text-xs font-bold text-slate-500 uppercase tracking-wider py-3 w-32">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($invoice['items'] as $item)
                        <tr>
                            <td class="py-4">
                                <p class="font-semibold text-slate-900">{{ $item['description'] }}</p>
                                <p class="text-sm text-slate-500 mt-0.5">{{ $item['details'] }}</p>
                            </td>
                            <td class="py-4 text-center text-slate-600">{{ $item['quantity'] }}</td>
                            <td class="py-4 text-right text-slate-600">€{{ number_format($item['price'] / 100, 2) }}</td>
                            <td class="py-4 text-right font-semibold text-slate-900">€{{ number_format(($item['quantity'] * $item['price']) / 100, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Totals --}}
            <div class="mt-8 flex justify-end">
                <div class="w-full max-w-sm">
                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 space-y-3">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-semibold">€{{ number_format($subtotal / 100, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Tax ({{ $invoice['tax_rate'] }}%)</span>
                            <span class="font-semibold">€{{ number_format($tax / 100, 2) }}</span>
                        </div>
                        <div class="border-t border-slate-200 pt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-slate-900">Total</span>
                                <span class="text-2xl font-bold gradient-primary bg-clip-text text-transparent">€{{ number_format($total / 100, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-8 py-6 bg-slate-50 border-t border-slate-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Payment Information</p>
                    <p class="text-sm text-slate-600">Bank: First National Bank</p>
                    <p class="text-sm text-slate-600">IBAN: DE89 3704 0044 0532 0130 00</p>
                    <p class="text-sm text-slate-600">BIC/SWIFT: COBADEFFXXX</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Notes</p>
                    <p class="text-sm text-slate-600">Payment is due within 30 days of invoice date. Thank you for your business!</p>
                </div>
            </div>
        </div>

        {{-- Actions (Desktop) --}}
        <div class="px-8 py-6 bg-white border-t border-slate-100 flex flex-wrap items-center justify-between gap-4 no-print">
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-500">Status:</span>
                <select class="h-10 px-4 rounded-xl border border-slate-200 text-sm font-medium bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    <option value="draft">Draft</option>
                    <option value="sent">Sent</option>
                    <option value="pending">Pending</option>
                    <option value="paid" selected>Paid</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button class="h-10 px-4 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Send Invoice
                </button>
                <button class="h-10 px-4 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Duplicate
                </button>
                <button class="h-10 px-4 rounded-xl border border-red-200 hover:bg-red-50 text-red-600 text-sm font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    body { background: white !important; }
    .no-print { display: none !important; }
    .print\:shadow-none { box-shadow: none !important; }
    .print\:border-0 { border: 0 !important; }
    .print\:rounded-none { border-radius: 0 !important; }
    .print\:bg-indigo-600 { background: #4f46e5 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>
@endpush
@endsection
