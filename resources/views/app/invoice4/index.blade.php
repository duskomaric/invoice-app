@extends('app.invoice4.partials.layout')

@section('title', 'Invoices - InvoicePro')
@section('page-title', 'Invoices')
@section('page-badge', '24 total')

@section('content')
@php
    $invoices = [
        ['id' => 1, 'number' => 'INV-2024-0001', 'client' => 'Acme Corporation', 'email' => 'billing@acme.com', 'amount' => 245000, 'currency' => 'EUR', 'status' => 'paid', 'date' => '2024-01-15', 'due_date' => '2024-02-15'],
        ['id' => 2, 'number' => 'INV-2024-0002', 'client' => 'TechStart Inc', 'email' => 'accounts@techstart.io', 'amount' => 189050, 'currency' => 'USD', 'status' => 'pending', 'date' => '2024-01-18', 'due_date' => '2024-02-18'],
        ['id' => 3, 'number' => 'INV-2024-0003', 'client' => 'Global Services Ltd', 'email' => 'finance@global.com', 'amount' => 520000, 'currency' => 'EUR', 'status' => 'overdue', 'date' => '2024-01-10', 'due_date' => '2024-01-25'],
        ['id' => 4, 'number' => 'INV-2024-0004', 'client' => 'Design Studio Pro', 'email' => 'hello@design.co', 'amount' => 75000, 'currency' => 'USD', 'status' => 'draft', 'date' => '2024-01-20', 'due_date' => '2024-02-20'],
        ['id' => 5, 'number' => 'INV-2024-0005', 'client' => 'Marketing Pro Agency', 'email' => 'invoices@marketing.com', 'amount' => 310000, 'currency' => 'EUR', 'status' => 'paid', 'date' => '2024-01-12', 'due_date' => '2024-02-12'],
        ['id' => 6, 'number' => 'INV-2024-0006', 'client' => 'CloudNet Solutions', 'email' => 'ap@cloudnet.io', 'amount' => 425000, 'currency' => 'USD', 'status' => 'sent', 'date' => '2024-01-22', 'due_date' => '2024-02-22'],
    ];
    $statusColors = [
        'paid' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
        'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500'],
        'sent' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500'],
        'overdue' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'dot' => 'bg-red-500'],
        'draft' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'dot' => 'bg-slate-400'],
    ];
@endphp

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
    <div class="card-glass rounded-xl p-4 border border-white/60 hover:shadow-lg transition-shadow">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-violet-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <p class="text-xl font-bold text-slate-800">24</p>
                <p class="text-[11px] text-slate-500">Total</p>
            </div>
        </div>
    </div>
    <div class="card-glass rounded-xl p-4 border border-white/60 hover:shadow-lg transition-shadow">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xl font-bold text-slate-800">12.4k</p>
                <p class="text-[11px] text-slate-500">Paid</p>
            </div>
        </div>
    </div>
    <div class="card-glass rounded-xl p-4 border border-white/60 hover:shadow-lg transition-shadow">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xl font-bold text-slate-800">4.9k</p>
                <p class="text-[11px] text-slate-500">Pending</p>
            </div>
        </div>
    </div>
    <div class="card-glass rounded-xl p-4 border border-white/60 hover:shadow-lg transition-shadow">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-rose-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="text-xl font-bold text-slate-800">5.2k</p>
                <p class="text-[11px] text-slate-500">Overdue</p>
            </div>
        </div>
    </div>
</div>

{{-- Filters Bar --}}
<div class="card-glass rounded-xl border border-white/60 p-3 mb-4">
    <div class="flex flex-wrap items-center gap-2">
        {{-- Search --}}
        <div class="relative flex-1 min-w-[200px]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Search invoices..." class="w-full h-9 pl-9 pr-3 rounded-lg bg-white/60 border border-slate-200/60 text-slate-700 placeholder-slate-400 text-xs focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 transition-all">
        </div>

        {{-- Status Filter --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="h-9 px-3 rounded-lg bg-white/60 border border-slate-200/60 text-xs text-slate-600 flex items-center gap-2 hover:border-violet-300 transition-colors" :class="filters.status && 'border-violet-300 bg-violet-50'">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                <span x-text="filters.status ? filters.status.charAt(0).toUpperCase() + filters.status.slice(1) : 'Status'"></span>
                <span x-show="filters.status" class="w-5 h-5 rounded-full bg-violet-500 text-white text-[10px] flex items-center justify-center">1</span>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-xl border border-slate-100 py-1 z-30 dropdown-enter" x-cloak>
                <button @click="filters.status = ''; open = false" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 flex items-center gap-2" :class="!filters.status && 'text-violet-600 bg-violet-50'">
                    <span class="w-4 h-4 rounded border flex items-center justify-center" :class="!filters.status ? 'border-violet-500 bg-violet-500' : 'border-slate-300'">
                        <svg x-show="!filters.status" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    All Statuses
                </button>
                <button @click="filters.status = 'paid'; open = false" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 flex items-center gap-2" :class="filters.status === 'paid' && 'text-violet-600 bg-violet-50'">
                    <span class="w-4 h-4 rounded border flex items-center justify-center" :class="filters.status === 'paid' ? 'border-violet-500 bg-violet-500' : 'border-slate-300'">
                        <svg x-show="filters.status === 'paid'" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Paid
                </button>
                <button @click="filters.status = 'pending'; open = false" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 flex items-center gap-2" :class="filters.status === 'pending' && 'text-violet-600 bg-violet-50'">
                    <span class="w-4 h-4 rounded border flex items-center justify-center" :class="filters.status === 'pending' ? 'border-violet-500 bg-violet-500' : 'border-slate-300'">
                        <svg x-show="filters.status === 'pending'" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    Pending
                </button>
                <button @click="filters.status = 'overdue'; open = false" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 flex items-center gap-2" :class="filters.status === 'overdue' && 'text-violet-600 bg-violet-50'">
                    <span class="w-4 h-4 rounded border flex items-center justify-center" :class="filters.status === 'overdue' ? 'border-violet-500 bg-violet-500' : 'border-slate-300'">
                        <svg x-show="filters.status === 'overdue'" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    Overdue
                </button>
            </div>
        </div>

        {{-- Currency Multiselect --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="h-9 px-3 rounded-lg bg-white/60 border border-slate-200/60 text-xs text-slate-600 flex items-center gap-2 hover:border-violet-300 transition-colors" :class="filters.currencies.length && 'border-violet-300 bg-violet-50'">
                <span>Currency</span>
                <span x-show="filters.currencies.length" class="w-5 h-5 rounded-full bg-violet-500 text-white text-[10px] flex items-center justify-center" x-text="filters.currencies.length"></span>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-xl border border-slate-100 py-1 z-30 dropdown-enter" x-cloak>
                <template x-for="curr in ['EUR', 'USD', 'GBP', 'BAM']" :key="curr">
                    <button @click="toggleCurrency(curr)" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 flex items-center gap-2" :class="filters.currencies.includes(curr) && 'text-violet-600 bg-violet-50'">
                        <span class="w-4 h-4 rounded border flex items-center justify-center" :class="filters.currencies.includes(curr) ? 'border-violet-500 bg-violet-500' : 'border-slate-300'">
                            <svg x-show="filters.currencies.includes(curr)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span x-text="curr"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- Boolean Toggle --}}
        <div class="flex items-center gap-2 h-9 px-3 rounded-lg bg-white/60 border border-slate-200/60" :class="filters.hasAttachments && 'border-violet-300 bg-violet-50'">
            <span class="text-xs text-slate-600">Attachments</span>
            <button @click="filters.hasAttachments = !filters.hasAttachments" class="relative w-9 h-5 rounded-full toggle-switch" :class="filters.hasAttachments ? 'bg-violet-500' : 'bg-slate-200'">
                <span class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow toggle-dot" :class="filters.hasAttachments && 'translate-x-4'"></span>
            </button>
        </div>

        {{-- Active Filters --}}
        <template x-if="filters.status || filters.currencies.length || filters.hasAttachments">
            <div class="flex items-center gap-1.5 ml-auto">
                <template x-if="filters.status">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-violet-100 text-violet-700 text-[11px] font-medium">
                        <span x-text="filters.status"></span>
                        <button @click="filters.status = ''" class="hover:text-violet-900"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </span>
                </template>
                <template x-for="curr in filters.currencies" :key="curr">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-violet-100 text-violet-700 text-[11px] font-medium">
                        <span x-text="curr"></span>
                        <button @click="toggleCurrency(curr)" class="hover:text-violet-900"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </span>
                </template>
                <button @click="clearFilters()" class="text-[11px] text-slate-500 hover:text-slate-700 underline ml-1">Clear all</button>
            </div>
        </template>
    </div>
</div>

{{-- Desktop Table --}}
<div class="card-glass rounded-xl border border-white/60 overflow-hidden hidden lg:block">
    <table class="w-full">
        <thead>
            <tr class="border-b border-slate-200/60 bg-slate-50/30">
                <th class="text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider px-4 py-3">Invoice</th>
                <th class="text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider px-4 py-3">Client</th>
                <th class="text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider px-4 py-3">Amount</th>
                <th class="text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider px-4 py-3">Status</th>
                <th class="text-left text-[10px] font-semibold text-slate-500 uppercase tracking-wider px-4 py-3">Due</th>
                <th class="text-right text-[10px] font-semibold text-slate-500 uppercase tracking-wider px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($invoices as $invoice)
            <tr class="hover:bg-violet-50/30 transition-colors">
                <td class="px-4 py-3">
                    <a href="/templates/invoice4/{{ $invoice['id'] }}" class="font-semibold text-violet-600 hover:text-violet-700 text-xs">{{ $invoice['number'] }}</a>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-md gradient-bg flex items-center justify-center text-white text-[10px] font-bold">{{ strtoupper(substr($invoice['client'], 0, 2)) }}</div>
                        <div>
                            <p class="font-medium text-slate-800 text-xs">{{ $invoice['client'] }}</p>
                            <p class="text-[10px] text-slate-400">{{ $invoice['email'] }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <span class="font-bold text-slate-900 text-xs">{{ $invoice['currency'] === 'EUR' ? '€' : '$' }}{{ number_format($invoice['amount'] / 100, 2) }}</span>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-semibold {{ $statusColors[$invoice['status']]['bg'] }} {{ $statusColors[$invoice['status']]['text'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $statusColors[$invoice['status']]['dot'] }}"></span>
                        {{ ucfirst($invoice['status']) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-slate-500 text-xs">{{ \Carbon\Carbon::parse($invoice['due_date'])->format('M d') }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-0.5">
                        <a href="/templates/invoice4/{{ $invoice['id'] }}" class="p-1.5 rounded-md hover:bg-violet-100 text-slate-400 hover:text-violet-600 transition-colors" title="View">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="/templates/invoice4/{{ $invoice['id'] }}/edit" class="p-1.5 rounded-md hover:bg-violet-100 text-slate-400 hover:text-violet-600 transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <button class="p-1.5 rounded-md hover:bg-violet-100 text-slate-400 hover:text-violet-600 transition-colors" title="Download">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </button>
                        <button class="p-1.5 rounded-md hover:bg-rose-100 text-slate-400 hover:text-rose-600 transition-colors" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-slate-100 flex items-center justify-between text-xs">
        <p class="text-slate-500">Showing <span class="font-semibold">1-{{ count($invoices) }}</span> of <span class="font-semibold">{{ count($invoices) }}</span></p>
        <div class="flex items-center gap-1">
            <button class="px-2.5 py-1.5 rounded-md border border-slate-200 text-slate-400" disabled>Prev</button>
            <button class="px-2.5 py-1.5 rounded-md gradient-bg text-white font-medium">1</button>
            <button class="px-2.5 py-1.5 rounded-md border border-slate-200 text-slate-600 hover:bg-slate-50">Next</button>
        </div>
    </div>
</div>

{{-- Mobile Cards --}}
<div class="lg:hidden space-y-3">
    @foreach($invoices as $invoice)
    <div class="card-glass rounded-xl border border-white/60 p-3">
        <div class="flex items-start justify-between mb-2">
            <div>
                <a href="/templates/invoice4/{{ $invoice['id'] }}" class="font-bold text-violet-600 text-sm">{{ $invoice['number'] }}</a>
                <p class="text-xs text-slate-500">{{ $invoice['client'] }}</p>
            </div>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $statusColors[$invoice['status']]['bg'] }} {{ $statusColors[$invoice['status']]['text'] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $statusColors[$invoice['status']]['dot'] }}"></span>
                {{ ucfirst($invoice['status']) }}
            </span>
        </div>
        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
            <div>
                <p class="text-lg font-bold text-slate-900">{{ $invoice['currency'] === 'EUR' ? '€' : '$' }}{{ number_format($invoice['amount'] / 100, 2) }}</p>
                <p class="text-[10px] text-slate-400">Due: {{ \Carbon\Carbon::parse($invoice['due_date'])->format('M d') }}</p>
            </div>
            <div class="flex items-center gap-0.5">
                <a href="/templates/invoice4/{{ $invoice['id'] }}" class="p-2 rounded-lg hover:bg-violet-100 text-slate-400 hover:text-violet-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </a>
                <a href="/templates/invoice4/{{ $invoice['id'] }}/edit" class="p-2 rounded-lg hover:bg-violet-100 text-slate-400 hover:text-violet-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <button class="p-2 rounded-lg hover:bg-slate-100 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
