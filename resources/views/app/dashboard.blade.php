@extends('layouts.app')

@section('title', 'Dashboard - App')
@section('page-title', 'Overview')
@section('page-subtitle', 'Welcome back! Here is what is happening with your business today.')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.invoices.create', $company) }}" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Invoice
    </x-app.button>
</div>
@endsection

@section('content')
<div class="space-y-8">
    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <x-app.stats-card
            title="Total Invoices"
            value="{{ $stats['invoices_count'] }}"
            icon="document-text"
            variant="primary"
        />
        <x-app.stats-card
            title="Open Quotes"
            value="{{ $stats['quotes_count'] }}"
            icon="chat-bubble-bottom-center-text"
            variant="blue"
        />
        <x-app.stats-card
            title="Proformas"
            value="{{ $stats['proformas_count'] }}"
            icon="document-duplicate"
            variant="warning"
        />
        <x-app.stats-card
            title="Active Contracts"
            value="{{ $stats['contracts_count'] }}"
            icon="shield-check"
            variant="success"
        />
    </div>

    {{-- Recent Invoices --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                <span class="w-2 h-4 bg-violet-500 rounded-full"></span>
                Recent Invoices
            </h2>
            <a href="{{ route('app.invoices.index', $company) }}" class="text-[11px] font-bold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 transition-colors uppercase tracking-tight">View All Invoices →</a>
        </div>

        <div class="relative z-10">
            <x-app.table>
                <x-slot name="head">
                    <x-app.table-th icon="hashtag">Invoice</x-app.table-th>
                    <x-app.table-th icon="user">Client</x-app.table-th>
                    <x-app.table-th icon="calendar">Date</x-app.table-th>
                    <x-app.table-th icon="banknotes">Amount</x-app.table-th>
                    <x-app.table-th>Status</x-app.table-th>
                    <x-app.table-th class="text-right">Actions</x-app.table-th>
                </x-slot>

                @forelse($stats['recent_invoices'] as $invoice)
                    <x-app.table-tr hover>
                        <x-app.table-td>
                            <a href="{{ route('app.invoices.show', [$company, $invoice]) }}" class="font-bold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 text-xs">
                                {{ $invoice->invoice_prefix }}{{ $invoice->id }}
                            </a>
                        </x-app.table-td>
                        <x-app.table-td>
                            <div class="flex items-center gap-2.5">
                                <x-app.avatar :name="$invoice->client->name" size="xs" />
                                <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">{{ $invoice->client->name }}</span>
                            </div>
                        </x-app.table-td>
                        <x-app.table-td>
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">{{ $invoice->date->format('M d, Y') }}</span>
                        </x-app.table-td>
                        <x-app.table-td>
                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $invoice->currency === 'EUR' ? '€' : ($invoice->currency === 'USD' ? '$' : $invoice->currency) }}{{ number_format($invoice->total_amount / 100, 2) }}</span>
                        </x-app.table-td>
                        <x-app.table-td>
                            @php
                                $statusColors = [
                                    'paid' => 'success',
                                    'draft' => 'slate',
                                    'sent' => 'amber',
                                    'overdue' => 'danger',
                                    'cancelled' => 'slate',
                                ];
                                $color = $statusColors[strtolower($invoice->status->value)] ?? 'violet';
                            @endphp
                            <x-app.status-badge :status="$invoice->status->value" :variant="$color" size="sm" dot :pulse="$invoice->status->value === 'overdue'" />
                        </x-app.table-td>
                        <x-app.table-td class="text-right">
                            <x-app.button href="{{ route('app.invoices.show', [$company, $invoice]) }}" variant="ghost" size="icon-xs" title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </x-app.button>
                        </x-app.table-td>
                    </x-app.table-tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <p class="text-sm font-medium">No recent invoices found</p>
                        </td>
                    </tr>
                @endforelse
            </x-app.table>
        </div>
    </div>
</div>
@endsection
