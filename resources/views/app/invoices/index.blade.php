@extends('layouts.app')

@section('title', 'Invoices - App')
@section('page-title', 'Invoices')
@section('page-subtitle', 'Manage and track all your invoices')

@section('header-actions')
    <x-app.button href="{{ route('app.invoices.create', $company) }}" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Invoice
    </x-app.button>
@endsection

@php
$statusColors = [
    'paid' => 'bg-emerald-500',
    'sent' => 'bg-amber-500',
    'overdue' => 'bg-rose-500',
    'draft' => 'bg-slate-500',
];
$statusVariants = [
    'paid' => 'emerald',
    'sent' => 'amber',
    'overdue' => 'rose',
    'draft' => 'slate',
];
@endphp

@section('content')
<div class="space-y-5">
    <x-app.stats-grid>
        <x-app.stats-card 
            title="Total Invoices" 
            value="{{ $company->currency }} {{ number_format($invoices->sum('total_amount') / 100, 2) }}" 
            icon="document-text" 
            variant="primary"
        />
        <x-app.stats-card 
            title="Paid" 
            value="{{ $company->currency }} {{ number_format($invoices->where('status', \App\Enums\InvoiceStatusEnum::Paid)->sum('total_amount') / 100, 2) }}" 
            icon="check-badge" 
            variant="success"
        />
        <x-app.stats-card 
            title="Pending" 
            value="{{ $company->currency }} {{ number_format($invoices->where('status', \App\Enums\InvoiceStatusEnum::Sent)->sum('total_amount') / 100, 2) }}" 
            icon="clock" 
            variant="warning"
        />
        <x-app.stats-card 
            title="Overdue" 
            value="{{ $company->currency }} {{ number_format($invoices->where('status', \App\Enums\InvoiceStatusEnum::Overdue)->sum('total_amount') / 100, 2) }}" 
            icon="exclamation-triangle" 
            variant="danger"
        />
    </x-app.stats-grid>

    <x-app.filter-bar placeholder="Search invoices...">
        <x-app.dropdown label="Status" icon="funnel" variant="secondary" size="sm" class="shadow-sm">
            <x-app.status-filter :statuses="['paid', 'sent', 'overdue', 'draft']" :colors="$statusColors" />
        </x-app.dropdown>
    </x-app.filter-bar>

    <x-app.data-list :collection="$invoices">
        <x-slot:desktop>
            <x-app.table>
                <x-slot:head>
                    <x-app.table-th icon="document-text">Invoice</x-app.table-th>
                    <x-app.table-th icon="user">Client</x-app.table-th>
                    <x-app.table-th icon="currency-dollar">Amount</x-app.table-th>
                    <x-app.table-th>Status</x-app.table-th>
                    <x-app.table-th icon="calendar">Due</x-app.table-th>
                    <x-app.table-th align="right">Actions</x-app.table-th>
                </x-slot:head>

                @forelse($invoices as $invoice)
                    <x-app.table-tr hover>
                        <x-app.table-td>
                            <a href="{{ route('app.invoices.show', [$company, $invoice]) }}" class="font-bold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 text-xs">
                                {{ $invoice->invoice_prefix }}{{ $invoice->id }}
                            </a>
                        </x-app.table-td>
                        <x-app.table-td>
                            <div class="flex items-center gap-2.5">
                                <x-app.avatar :name="$invoice->client->name" size="sm" />
                                <div>
                                    <p class="font-medium text-slate-800 dark:text-white text-xs">{{ $invoice->client->name }}</p>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500">{{ $invoice->client->email }}</p>
                                </div>
                            </div>
                        </x-app.table-td>
                        <x-app.table-td>
                            <x-app.currency :amount="$invoice->total_amount" :currency="$invoice->currency" class="font-bold text-slate-900 dark:text-white text-sm" />
                        </x-app.table-td>
                        <x-app.table-td>
                            <x-app.status-badge :status="$invoice->status->value" :variant="$statusVariants[$invoice->status->value] ?? 'slate'" size="sm" dot :pulse="$invoice->status->value === 'overdue'" />
                        </x-app.table-td>
                        <x-app.table-td class="text-slate-500 dark:text-slate-400 text-xs">
                            {{ $invoice->due_date->format('M d, Y') }}
                        </x-app.table-td>
                        <x-app.table-td class="text-right">
                            <x-app.action-buttons 
                                :show-route="route('app.invoices.show', [$company, $invoice])"
                                :edit-route="route('app.invoices.edit', [$company, $invoice])"
                            />
                        </x-app.table-td>
                    </x-app.table-tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 italic text-xs">No invoices found</td>
                    </tr>
                @endforelse
            </x-app.table>
        </x-slot:desktop>
        
        <x-slot:mobile>
            @foreach($invoices as $invoice)
            <x-app.card hover padding="p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <a href="{{ route('app.invoices.show', [$company, $invoice]) }}" class="font-bold text-violet-600 dark:text-violet-400 text-sm">#{{ $invoice->invoice_prefix }}{{ $invoice->id }}</a>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $invoice->client->name }}</p>
                    </div>
                    <x-app.status-badge :status="$invoice->status->value" :variant="$statusVariants[$invoice->status->value] ?? 'slate'" size="xs" dot />
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-700/50">
                    <div>
                        <x-app.currency :amount="$invoice->total_amount" :currency="$invoice->currency" class="text-xl font-bold text-slate-900 dark:text-white" />
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">Due: {{ $invoice->due_date->format('M d') }}</p>
                    </div>
                    <x-app.action-buttons 
                        :show-route="route('app.invoices.show', [$company, $invoice])"
                        :edit-route="route('app.invoices.edit', [$company, $invoice])"
                        size="icon-sm"
                    />
                </div>
            </x-app.card>
            @endforeach
        </x-slot:mobile>
    </x-app.data-list>
</div>
@endsection
