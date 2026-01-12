@extends('app.invoice5.partials.layout')

@section('title', 'Invoice INV-2024-0042 - InvoicePro')
@section('page-title', 'Invoice Details')
@section('page-subtitle', 'INV-2024-0042')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-invoice5.button href="/templates/invoice5" variant="secondary" size="sm" class="hidden sm:flex">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back
    </x-invoice5.button>
    <x-invoice5.button href="/templates/invoice5/1/edit" variant="secondary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit
    </x-invoice5.button>
    <x-invoice5.button variant="primary" size="sm" onclick="window.print()">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        Print
    </x-invoice5.button>
</div>
@endsection

@php
$invoice = [
    'id' => 1,
    'number' => 'INV-2024-0042',
    'client' => 'Acme Corporation',
    'email' => 'billing@acme.com',
    'address' => '123 Business Ave, New York, NY 10001',
    'amount' => 234000,
    'currency' => 'EUR',
    'status' => 'paid',
    'date' => '2024-01-15',
    'due_date' => '2024-02-14',
    'paid_date' => '2024-02-10',
];

$items = [
    ['description' => 'Website Design & Development', 'quantity' => 1, 'price' => 1500],
    ['description' => 'Logo Design', 'quantity' => 1, 'price' => 500],
    ['description' => 'SEO Optimization Package', 'quantity' => 1, 'price' => 340],
];
@endphp

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    {{-- Status Banner --}}
    <div class="stagger-1 page-enter opacity-0" style="animation-fill-mode: forwards;">
        <div class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 p-4 text-white shadow-xl shadow-emerald-500/30 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="font-bold text-lg">Payment Received</p>
                    <p class="text-emerald-100 text-sm">Paid on {{ \Carbon\Carbon::parse($invoice['paid_date'])->format('F d, Y') }}</p>
                </div>
            </div>
            <x-invoice5.button variant="secondary" size="sm" class="bg-white/20 border-white/30 text-white hover:bg-white/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download Receipt
            </x-invoice5.button>
        </div>
    </div>

    {{-- Invoice Document --}}
    <div class="stagger-2 page-enter opacity-0" style="animation-fill-mode: forwards;">
        <x-invoice5.card padding="p-0" class="overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 p-8 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">InvoicePro LLC</h2>
                                <p class="text-violet-200 text-sm">hello@invoicepro.com</p>
                            </div>
                        </div>
                        <p class="text-violet-200 text-sm">456 Invoice Street</p>
                        <p class="text-violet-200 text-sm">Business City, BC 12345</p>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold mb-2">INVOICE</p>
                        <p class="text-violet-200 text-lg">#{{ $invoice['number'] }}</p>
                        <x-invoice5.badge variant="success" size="md" class="mt-3 bg-white/20 text-white">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Paid
                        </x-invoice5.badge>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-8">
                {{-- Client & Dates --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Bill To
                        </p>
                        <div class="flex items-start gap-3">
                            <x-invoice5.avatar :name="$invoice['client']" size="md" />
                            <div>
                                <p class="font-extrabold text-slate-900 dark:text-white">{{ $invoice['client'] }}</p>
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ $invoice['email'] }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $invoice['address'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="md:text-right">
                        <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2 md:justify-end">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Invoice Details
                        </p>
                        <div class="space-y-1">
                            <p class="text-sm text-slate-600 dark:text-slate-300">Issue Date: <span class="font-semibold text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($invoice['date'])->format('M d, Y') }}</span></p>
                            <p class="text-sm text-slate-600 dark:text-slate-300">Due Date: <span class="font-semibold text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($invoice['due_date'])->format('M d, Y') }}</span></p>
                            <p class="text-sm text-slate-600 dark:text-slate-300">Paid Date: <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ \Carbon\Carbon::parse($invoice['paid_date'])->format('M d, Y') }}</span></p>
                        </div>
                    </div>
                </div>

                {{-- Items Table --}}
                <div class="rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden mb-8">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50">
                                <th class="text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-4 py-3">Description</th>
                                <th class="text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-4 py-3">Qty</th>
                                <th class="text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-4 py-3">Price</th>
                                <th class="text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-4 py-3">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($items as $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-4 py-4 text-sm font-medium text-slate-900 dark:text-slate-200">{{ $item['description'] }}</td>
                                <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-400 text-center">{{ $item['quantity'] }}</td>
                                <td class="px-4 py-4 text-sm text-slate-700 dark:text-slate-400 text-right">€{{ number_format($item['price'], 2) }}</td>
                                <td class="px-4 py-4 text-sm font-bold text-slate-900 dark:text-white text-right">€{{ number_format($item['quantity'] * $item['price'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Summary --}}
                <div class="flex justify-end">
                    <div class="w-72 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 dark:text-slate-400">Subtotal</span>
                            <span class="font-semibold text-slate-900 dark:text-white">€2,340.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 dark:text-slate-400">Tax (17%)</span>
                            <span class="font-semibold text-slate-900 dark:text-white">€397.80</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold border-t border-slate-200 dark:border-slate-700 pt-3 mt-3">
                            <span class="text-slate-900 dark:text-white">Total</span>
                            <span class="text-violet-600 dark:text-violet-400">€2,737.80</span>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Notes</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Thank you for your business! Payment was received on time.</p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-8 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-700">
                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                    <p>Generated by InvoicePro</p>
                    <p>Page 1 of 1</p>
                </div>
            </div>
        </x-invoice5.card>
    </div>

    {{-- Actions --}}
    <div class="stagger-3 page-enter opacity-0 pb-24 lg:pb-0" style="animation-fill-mode: forwards;">
        <x-invoice5.card>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-cyan-600 flex items-center justify-center text-white shadow-lg shadow-sky-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-slate-900 dark:text-white text-sm">Share Invoice</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Send to client or download</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <x-invoice5.button variant="secondary" size="sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Send Email
                    </x-invoice5.button>
                    <x-invoice5.button variant="secondary" size="sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Copy Link
                    </x-invoice5.button>
                    <x-invoice5.button variant="primary" size="sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download PDF
                    </x-invoice5.button>
                </div>
            </div>
        </x-invoice5.card>
    </div>
</div>
@endsection
