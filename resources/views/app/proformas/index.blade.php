@extends('layouts.app')

@section('title', 'Proformas - App')
@section('page-title', 'Proformas')
@section('page-subtitle', 'Manage your proforma invoices and pre-billing')
@section('page-badge', $proformas->total())

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.proformas.create', $company) }}" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Proforma
    </x-app.button>
</div>
@endsection

@php
$statusColors = [
    'draft' => 'bg-slate-500',
    'sent' => 'bg-amber-500',
    'converted' => 'bg-emerald-500',
    'expired' => 'bg-rose-500',
];
$statusVariants = [
    'draft' => 'slate',
    'sent' => 'amber',
    'converted' => 'emerald',
    'expired' => 'rose',
];
@endphp

@section('content')
<div class="space-y-5">
    <x-app.stats-grid>
        <x-app.stats-card 
            title="Total Proformas" 
            value="{{ $company->currency }} {{ number_format($proformas->sum('total_amount') / 100, 2) }}" 
            icon="document-duplicate" 
            variant="primary"
        />
        <x-app.stats-card 
            title="Pending" 
            value="{{ $company->proformas()->where('status', 'sent')->count() }}" 
            icon="clock" 
            variant="warning"
        />
        <x-app.stats-card 
            title="Converted" 
            value="{{ $company->proformas()->where('status', 'converted')->count() }}" 
            icon="check-circle" 
            variant="success"
        />
        <x-app.stats-card 
            title="Expired" 
            value="{{ $company->proformas()->where('status', 'expired')->count() }}" 
            icon="x-circle" 
            variant="slate"
        />
    </x-app.stats-grid>

    <x-app.filter-bar placeholder="Search proformas...">
        <x-app.dropdown label="Status" icon="funnel" variant="secondary" size="sm" class="shadow-sm">
            <x-app.status-filter :statuses="['draft', 'sent', 'converted', 'expired']" :colors="$statusColors" />
        </x-app.dropdown>
    </x-app.filter-bar>

    <x-app.data-list :collection="$proformas">
        <x-slot:desktop>
            <x-app.table>
                <x-slot:head>
                    <x-app.table-th icon="document-text">Proforma</x-app.table-th>
                    <x-app.table-th icon="user">Client</x-app.table-th>
                    <x-app.table-th icon="currency-dollar">Amount</x-app.table-th>
                    <x-app.table-th>Status</x-app.table-th>
                    <x-app.table-th icon="calendar">Valid Until</x-app.table-th>
                    <x-app.table-th align="right">Actions</x-app.table-th>
                </x-slot:head>

                @forelse($proformas as $proforma)
                    <x-app.table-tr hover>
                        <x-app.table-td>
                            <a href="{{ route('app.proformas.show', [$company, $proforma]) }}" class="font-bold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 text-xs">
                                {{ $proforma->proforma_prefix }}{{ $proforma->id }}
                            </a>
                        </x-app.table-td>
                        <x-app.table-td>
                            <div class="flex items-center gap-2.5">
                                <x-app.avatar :name="$proforma->client->name" size="sm" />
                                <div>
                                    <p class="font-medium text-slate-800 dark:text-white text-xs">{{ $proforma->client->name }}</p>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500">{{ $proforma->client->email }}</p>
                                </div>
                            </div>
                        </x-app.table-td>
                        <x-app.table-td>
                            <x-app.currency :amount="$proforma->total_amount" :currency="$proforma->currency" class="font-bold text-slate-900 dark:text-white text-sm" />
                        </x-app.table-td>
                        <x-app.table-td>
                            <x-app.status-badge :status="$proforma->status->value" :variant="$statusVariants[strtolower($proforma->status->value)] ?? 'slate'" size="sm" dot :pulse="strtolower($proforma->status->value) === 'sent'" />
                        </x-app.table-td>
                        <x-app.table-td class="text-slate-500 dark:text-slate-400 text-xs">
                            {{ $proforma->valid_until ? $proforma->valid_until->format('M d, Y') : 'N/A' }}
                        </x-app.table-td>
                        <x-app.table-td class="text-right">
                            <x-app.action-buttons 
                                :show-route="route('app.proformas.show', [$company, $proforma])"
                                :edit-route="route('app.proformas.edit', [$company, $proforma])"
                            />
                        </x-app.table-td>
                    </x-app.table-tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 italic text-xs">No proformas found</td>
                    </tr>
                @endforelse
            </x-app.table>
        </x-slot:desktop>
        
        <x-slot:mobile>
            @foreach($proformas as $proforma)
            <x-app.card hover padding="p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <a href="{{ route('app.proformas.show', [$company, $proforma]) }}" class="font-bold text-violet-600 dark:text-violet-400 text-sm">#{{ $proforma->proforma_prefix }}{{ $proforma->id }}</a>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $proforma->client->name }}</p>
                    </div>
                    <x-app.status-badge :status="$proforma->status->value" :variant="$statusVariants[strtolower($proforma->status->value)] ?? 'slate'" size="xs" dot />
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-700/50">
                    <div>
                        <x-app.currency :amount="$proforma->total_amount" :currency="$proforma->currency" class="text-xl font-bold text-slate-900 dark:text-white" />
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">Until: {{ $proforma->valid_until ? $proforma->valid_until->format('M d') : 'N/A' }}</p>
                    </div>
                    <x-app.action-buttons 
                        :show-route="route('app.proformas.show', [$company, $proforma])"
                        :edit-route="route('app.proformas.edit', [$company, $proforma])"
                        size="icon-sm"
                    />
                </div>
            </x-app.card>
            @endforeach
        </x-slot:mobile>
    </x-app.data-list>
</div>
@endsection
