@extends('layouts.app')

@section('title', 'Client Details - App')
@section('page-title', 'Client Profile')
@section('page-subtitle', 'Detailed overview of client history and information')
@section('page-badge', 'Profile')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.clients.index', $company) }}" variant="secondary" size="sm" class="hidden sm:flex">
        Back to List
    </x-app.button>
    <x-app.button href="{{ route('app.clients.edit', [$company, $client]) }}" variant="secondary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        Edit Client
    </x-app.button>
    <x-app.button href="{{ route('app.invoices.create', $company) }}?client_id={{ $client->id }}" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Invoice
    </x-app.button>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left Column: Client Info --}}
    <div class="lg:col-span-1 space-y-6">
        <x-app.card>
            <div class="flex flex-col items-center text-center p-4">
                <x-app.avatar :name="$client->name" size="xl" class="mb-4" />
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $client->name }}</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Client since {{ $client->created_at->format('M Y') }}</p>
                
                <div class="grid grid-cols-2 gap-3 w-full mt-6">
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-700/50 text-left border border-slate-100 dark:border-slate-700">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Spent</p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white">€0.00</p> {{-- Calculate this in controller --}}
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-700/50 text-left border border-slate-100 dark:border-slate-700">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Invoices</p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $client->invoices->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 dark:border-slate-700 mt-2 pt-4 px-4 space-y-4 pb-4">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Email</p>
                    <p class="text-sm text-slate-700 dark:text-slate-300">{{ $client->email ?? 'No email provided' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Phone</p>
                    <p class="text-sm text-slate-700 dark:text-slate-300">{{ $client->phone ?? 'No phone provided' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Address</p>
                    <p class="text-sm text-slate-700 dark:text-slate-300">
                        {{ $client->address }}<br>
                        {{ $client->postal_code }} {{ $client->city }}<br>
                        {{ $client->country }}
                    </p>
                </div>
                @if($client->vat_number)
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">VAT Number</p>
                    <p class="text-sm text-slate-700 dark:text-slate-300">{{ $client->vat_number }}</p>
                </div>
                @endif
            </div>
        </x-app.card>

        @if($client->notes)
        <x-app.card>
            <x-app.section-header title="Internal Notes" icon="document-text" variant="primary" />
            <div class="mt-4 text-xs text-slate-600 dark:text-slate-400 leading-relaxed italic">
                "{{ $client->notes }}"
            </div>
        </x-app.card>
        @endif
    </div>

    {{-- Right Column: Activity & History --}}
    <div class="lg:col-span-2 space-y-6">
        <x-app.card class="no-padding overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Recent Invoices
                </h3>
            </div>
            
            <x-app.table>
                <x-slot name="head">
                    <x-app.table-th>Invoice #</x-slot>
                    <x-app.table-th>Date</x-app.table-th>
                    <x-app.table-th>Amount</x-app.table-th>
                    <x-app.table-th>Status</x-app.table-th>
                    <x-app.table-th class="text-right">Actions</x-app.table-th>
                </x-slot>

                @forelse($client->invoices as $invoice)
                    <x-app.table-tr hover>
                        <x-app.table-td>
                            <span class="font-bold text-slate-900 dark:text-white font-mono text-xs">{{ $invoice->invoice_prefix }}{{ $invoice->id }}</span>
                        </x-app.table-td>
                        <x-app.table-td>
                            <span class="text-xs text-slate-600 dark:text-slate-400">{{ $invoice->date->format('M d, Y') }}</span>
                        </x-app.table-td>
                        <x-app.table-td>
                            <span class="text-xs font-bold text-slate-900 dark:text-white">€{{ number_format($invoice->total, 2) }}</span>
                        </x-app.table-td>
                        <x-app.table-td>
                            @php
                                $statusColors = [
                                    'paid' => 'emerald',
                                    'pending' => 'amber',
                                    'overdue' => 'rose',
                                    'draft' => 'slate',
                                ];
                                $color = $statusColors[strtolower($invoice->status)] ?? 'violet';
                            @endphp
                            <x-app.status-badge status="{{ $invoice->status }}" variant="{{ $color }}" />
                        </x-app.table-td>
                        <x-app.table-td class="text-right">
                            <x-app.button href="{{ route('app.invoices.show', [$company, $invoice]) }}" variant="secondary" size="icon-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </x-app.button>
                        </x-app.table-td>
                    </x-app.table-tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            No invoices found for this client.
                        </td>
                    </tr>
                @endforelse
            </x-app.table>
        </x-app.card>
    </div>
</div>
@endsection
