@extends('app.invoice5.partials.layout')

@section('title', 'Invoices - InvoicePro')
@section('page-title', 'Invoices')
@section('page-subtitle', 'Manage and track all your invoices')

@php
$invoices = [
    ['id' => 1, 'number' => 'INV-2024-0042', 'client' => 'Acme Corporation', 'email' => 'billing@acme.com', 'amount' => 125000, 'currency' => 'EUR', 'status' => 'paid', 'date' => '2024-01-15', 'due_date' => '2024-02-14'],
    ['id' => 2, 'number' => 'INV-2024-0041', 'client' => 'TechStart Inc', 'email' => 'accounts@techstart.io', 'amount' => 89500, 'currency' => 'USD', 'status' => 'pending', 'date' => '2024-01-12', 'due_date' => '2024-02-11'],
    ['id' => 3, 'number' => 'INV-2024-0040', 'client' => 'Global Services Ltd', 'email' => 'finance@global.com', 'amount' => 234000, 'currency' => 'EUR', 'status' => 'overdue', 'date' => '2024-01-08', 'due_date' => '2024-01-22'],
    ['id' => 4, 'number' => 'INV-2024-0039', 'client' => 'Design Studio Pro', 'email' => 'hello@designstudio.co', 'amount' => 45000, 'currency' => 'EUR', 'status' => 'paid', 'date' => '2024-01-05', 'due_date' => '2024-02-04'],
    ['id' => 5, 'number' => 'INV-2024-0038', 'client' => 'Marketing Agency', 'email' => 'team@marketing.io', 'amount' => 178000, 'currency' => 'USD', 'status' => 'pending', 'date' => '2024-01-02', 'due_date' => '2024-02-01'],
];

$statusConfig = [
    'paid' => ['variant' => 'success', 'icon' => 'check-circle'],
    'pending' => ['variant' => 'warning', 'icon' => 'clock'],
    'overdue' => ['variant' => 'danger', 'icon' => 'exclamation-circle'],
    'draft' => ['variant' => 'default', 'icon' => 'pencil'],
];
@endphp

@section('content')
<div class="space-y-5">
    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 stagger-1 page-enter opacity-0" style="animation-fill-mode: forwards;">
        <x-invoice5.stats-card 
            title="Total Invoices" 
            value="€67.2k" 
            icon="document-text" 
            variant="primary"
            trend="+12.5%"
            :trend-up="true"
        />
        <x-invoice5.stats-card 
            title="Paid" 
            value="€48.5k" 
            icon="check-badge" 
            variant="success"
            trend="+8.2%"
            :trend-up="true"
        />
        <x-invoice5.stats-card 
            title="Pending" 
            value="€13.5k" 
            icon="clock" 
            variant="warning"
            trend="-3.1%"
            :trend-up="false"
        />
        <x-invoice5.stats-card 
            title="Overdue" 
            value="€5.2k" 
            icon="exclamation-triangle" 
            variant="danger"
            trend="+2.4%"
            :trend-up="true"
        />
    </div>

    {{-- Filters --}}
    <div class="stagger-2 page-enter opacity-0 relative z-50" style="animation-fill-mode: forwards;">
        <x-invoice5.card padding="p-3">
            <div class="flex flex-wrap items-center gap-2">
                {{-- Search --}}
                <div class="relative flex-1 min-w-[200px]">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Search invoices..." class="w-full h-9 pl-9 pr-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 text-xs focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all">
                </div>

                {{-- Status Filter --}}
                <x-invoice5.dropdown label="Status" icon="funnel" class="shadow-sm">
                    <button @click="filters.status = ''" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-2" :class="!filters.status && 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20'">
                        <span class="w-4 h-4 rounded border flex items-center justify-center" :class="!filters.status ? 'border-violet-500 bg-violet-500' : 'border-slate-300 dark:border-slate-600'">
                            <svg x-show="!filters.status" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        All Statuses
                    </button>
                    <button @click="filters.status = 'paid'" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-2" :class="filters.status === 'paid' && 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20'">
                        <span class="w-4 h-4 rounded border flex items-center justify-center" :class="filters.status === 'paid' ? 'border-violet-500 bg-violet-500' : 'border-slate-300 dark:border-slate-600'">
                            <svg x-show="filters.status === 'paid'" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Paid
                    </button>
                    <button @click="filters.status = 'pending'" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-2" :class="filters.status === 'pending' && 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20'">
                        <span class="w-4 h-4 rounded border flex items-center justify-center" :class="filters.status === 'pending' ? 'border-violet-500 bg-violet-500' : 'border-slate-300 dark:border-slate-600'">
                            <svg x-show="filters.status === 'pending'" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Pending
                    </button>
                    <button @click="filters.status = 'overdue'" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-2" :class="filters.status === 'overdue' && 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20'">
                        <span class="w-4 h-4 rounded border flex items-center justify-center" :class="filters.status === 'overdue' ? 'border-violet-500 bg-violet-500' : 'border-slate-300 dark:border-slate-600'">
                            <svg x-show="filters.status === 'overdue'" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Overdue
                    </button>
                </x-invoice5.dropdown>

                {{-- Currency Filter --}}
                <x-invoice5.dropdown label="Currency" class="shadow-sm">
                    <template x-for="curr in ['EUR', 'USD', 'GBP', 'BAM']" :key="curr">
                        <button @click="toggleCurrency(curr)" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-2" :class="filters.currencies.includes(curr) && 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20'">
                            <span class="w-4 h-4 rounded border flex items-center justify-center" :class="filters.currencies.includes(curr) ? 'border-violet-500 bg-violet-500' : 'border-slate-300 dark:border-slate-600'">
                                <svg x-show="filters.currencies.includes(curr)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span x-text="curr"></span>
                        </button>
                    </template>
                </x-invoice5.dropdown>

                {{-- Attachments Toggle --}}
                <div class="flex items-center gap-2 h-9 px-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300/80 dark:border-slate-600 shadow-sm" :class="filters.hasAttachments && 'border-violet-400 dark:border-violet-500/50 bg-violet-50 dark:bg-violet-900/20'">
                    <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <span class="text-xs text-slate-600 dark:text-slate-300">Files</span>
                    <button @click="filters.hasAttachments = !filters.hasAttachments" class="relative w-9 h-5 rounded-full transition-colors" :class="filters.hasAttachments ? 'bg-violet-500' : 'bg-slate-200 dark:bg-slate-600'">
                        <span class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform" :class="filters.hasAttachments && 'translate-x-4'"></span>
                    </button>
                </div>

                {{-- Active Filters --}}
                <template x-if="filters.status || filters.currencies.length || filters.hasAttachments">
                    <div class="flex items-center gap-1.5 ml-auto">
                        <template x-if="filters.status">
                            <x-invoice5.badge variant="primary" size="xs">
                                <span x-text="filters.status"></span>
                                <button @click="filters.status = ''" class="hover:text-violet-900 dark:hover:text-violet-200"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                            </x-invoice5.badge>
                        </template>
                        <template x-for="curr in filters.currencies" :key="curr">
                            <x-invoice5.badge variant="primary" size="xs">
                                <span x-text="curr"></span>
                                <button @click="toggleCurrency(curr)" class="hover:text-violet-900 dark:hover:text-violet-200"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                            </x-invoice5.badge>
                        </template>
                        <button @click="clearFilters()" class="text-[11px] text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 underline ml-1">Clear</button>
                    </div>
                </template>
            </div>
        </x-invoice5.card>
    </div>

    {{-- Table --}}
    <div class="stagger-3 page-enter opacity-0 hidden lg:block relative z-10" style="animation-fill-mode: forwards;">
        <x-invoice5.table>
            <x-slot:head>
                <th class="text-left text-[10px] font-bold text-slate-700 dark:text-slate-400 uppercase tracking-wider px-4 py-3">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Invoice
                    </div>
                </th>
                <th class="text-left text-[10px] font-bold text-slate-700 dark:text-slate-400 uppercase tracking-wider px-4 py-3">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Client
                    </div>
                </th>
                <th class="text-left text-[10px] font-bold text-slate-700 dark:text-slate-400 uppercase tracking-wider px-4 py-3">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Amount
                    </div>
                </th>
                <th class="text-left text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-4 py-3">Status</th>
                <th class="text-left text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-4 py-3">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Due
                    </div>
                </th>
                <th class="text-right text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-4 py-3">Actions</th>
            </x-slot:head>

            @foreach($invoices as $invoice)
            <tr class="group hover:bg-violet-50/30 dark:hover:bg-violet-900/10 transition-colors">
                <td class="px-4 py-3">
                    <a href="/templates/invoice5/{{ $invoice['id'] }}" class="font-bold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 text-xs">{{ $invoice['number'] }}</a>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2.5">
                        <x-invoice5.avatar :name="$invoice['client']" size="sm" />
                        <div>
                            <p class="font-medium text-slate-800 dark:text-white text-xs">{{ $invoice['client'] }}</p>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500">{{ $invoice['email'] }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $invoice['currency'] === 'EUR' ? '€' : '$' }}{{ number_format($invoice['amount'] / 100, 2) }}</span>
                </td>
                <td class="px-4 py-3">
                    <x-invoice5.badge :variant="$statusConfig[$invoice['status']]['variant']" size="sm" dot pulse="{{ $invoice['status'] === 'overdue' }}">
                        {{ ucfirst($invoice['status']) }}
                    </x-invoice5.badge>
                </td>
                <td class="px-4 py-3 text-slate-500 dark:text-slate-400 text-xs">{{ \Carbon\Carbon::parse($invoice['due_date'])->format('M d, Y') }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        <a href="/templates/invoice5/{{ $invoice['id'] }}" class="p-1.5 rounded-lg hover:bg-violet-100 dark:hover:bg-violet-900/30 text-slate-400 dark:text-slate-500 hover:text-violet-600 dark:hover:text-violet-400 transition-colors" title="View">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="/templates/invoice5/{{ $invoice['id'] }}/edit" class="p-1.5 rounded-lg hover:bg-violet-100 dark:hover:bg-violet-900/30 text-slate-400 dark:text-slate-500 hover:text-violet-600 dark:hover:text-violet-400 transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <button class="p-1.5 rounded-lg hover:bg-violet-100 dark:hover:bg-violet-900/30 text-slate-400 dark:text-slate-500 hover:text-violet-600 dark:hover:text-violet-400 transition-colors" title="Download">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </button>
                        <button class="p-1.5 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-900/30 text-slate-400 dark:text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 transition-colors" title="Delete">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach

            <x-slot:pagination>
                <div class="flex items-center justify-between text-xs">
                    <p class="text-slate-500 dark:text-slate-400">Showing <span class="font-semibold text-slate-700 dark:text-slate-200">1-{{ count($invoices) }}</span> of <span class="font-semibold text-slate-700 dark:text-slate-200">{{ count($invoices) }}</span></p>
                    <div class="flex items-center gap-1">
                        <x-invoice5.button variant="secondary" size="xs" disabled>Prev</x-invoice5.button>
                        <x-invoice5.button variant="primary" size="xs">1</x-invoice5.button>
                        <x-invoice5.button variant="secondary" size="xs">Next</x-invoice5.button>
                    </div>
                </div>
            </x-slot:pagination>
        </x-invoice5.table>
    </div>

    {{-- Mobile Cards --}}
    <div class="lg:hidden space-y-3 stagger-3 page-enter opacity-0 pb-24" style="animation-fill-mode: forwards;">
        @foreach($invoices as $invoice)
        <x-invoice5.card hover padding="p-4">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <a href="/templates/invoice5/{{ $invoice['id'] }}" class="font-bold text-violet-600 dark:text-violet-400 text-sm">{{ $invoice['number'] }}</a>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $invoice['client'] }}</p>
                </div>
                <x-invoice5.badge :variant="$statusConfig[$invoice['status']]['variant']" size="xs" dot>
                    {{ ucfirst($invoice['status']) }}
                </x-invoice5.badge>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-700/50">
                <div>
                    <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $invoice['currency'] === 'EUR' ? '€' : '$' }}{{ number_format($invoice['amount'] / 100, 2) }}</p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500">Due: {{ \Carbon\Carbon::parse($invoice['due_date'])->format('M d') }}</p>
                </div>
                <div class="flex items-center gap-1">
                    <a href="/templates/invoice5/{{ $invoice['id'] }}" class="p-2 rounded-lg hover:bg-violet-100 dark:hover:bg-violet-900/30 text-slate-400 hover:text-violet-600 dark:hover:text-violet-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </a>
                    <a href="/templates/invoice5/{{ $invoice['id'] }}/edit" class="p-2 rounded-lg hover:bg-violet-100 dark:hover:bg-violet-900/30 text-slate-400 hover:text-violet-600 dark:hover:text-violet-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                </div>
            </div>
        </x-invoice5.card>
        @endforeach
    </div>
</div>
@endsection
