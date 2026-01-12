@extends('layouts.app')

@section('title', 'Record Payment - App')
@section('page-title', 'Record Payment')
@section('page-subtitle', 'Add a new payment record for a client or invoice')
@section('page-badge', 'New')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.payments.index', $company) }}" variant="secondary" size="sm">
        Cancel
    </x-app.button>
    <x-app.button type="submit" form="payment-form" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Save Payment
    </x-app.button>
</div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <form id="payment-form" action="{{ route('app.payments.store', $company) }}" method="POST">
        @csrf
        
        <x-app.card>
            <x-app.section-header title="Payment Details" subtitle="Enter the transaction information" icon="credit-card" variant="primary" />
            
            <div class="mt-6 space-y-4">
                {{-- Client & Invoice Selection --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div x-data="{ open: false, search: '', clients: @js($clients) }">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Client</label>
                        <div class="relative">
                            <input type="hidden" name="client_id" :value="search ? clients.find(c => c.name === search)?.id : ''">
                            <input type="text" x-model="search" @focus="open = true" @click.away="open = false" placeholder="Search clients..." class="w-full h-10 px-3 rounded-xl bg-white dark:bg-slate-800 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm shadow-sm" required>
                            
                            <div x-show="open" class="absolute left-0 right-0 top-full mt-1 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 overflow-hidden z-50 max-h-48 overflow-y-auto">
                                <template x-for="client in clients.filter(c => c.name.toLowerCase().includes(search.toLowerCase()))" :key="client.id">
                                    <button type="button" @click="search = client.name; open = false" class="w-full text-left px-4 py-2 text-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 text-slate-700 dark:text-slate-300">
                                        <div class="font-bold cursor-pointer" x-text="client.name"></div>
                                        <div class="text-[10px] text-slate-500" x-text="client.email"></div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Link to Invoice (Optional)</label>
                        <x-app.input type="text" name="invoice_id" placeholder="Enter invoice number..." />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-app.input label="Payment Date" type="date" name="date" value="{{ date('Y-m-d') }}" required />
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Payment Method</label>
                        <select name="method" class="w-full h-10 px-3 rounded-xl bg-white dark:bg-slate-800 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm shadow-sm" required>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-app.input label="Amount" type="number" step="0.01" name="amount" placeholder="0.00" required />
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Currency</label>
                        <select name="currency" class="w-full h-10 px-3 rounded-xl bg-white dark:bg-slate-800 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm shadow-sm" required>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->code }}">{{ $currency->code }} - {{ $currency->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Notes / Reference</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm text-slate-600 placeholder-slate-400" placeholder="Add transaction reference or internal notes..."></textarea>
                </div>
            </div>
        </x-app.card>
    </form>
</div>
@endsection
