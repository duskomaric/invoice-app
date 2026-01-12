@extends('layouts.app')

@section('title', 'Contracts - App')
@section('page-title', 'Contracts')
@section('page-subtitle', 'Manage your recurring agreements and service contracts')
@section('page-badge', $contracts->total())

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.contracts.create', $company) }}" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Contract
    </x-app.button>
</div>
@endsection

@section('content')
<div class="space-y-5">
    <x-app.stats-grid>
        <x-app.stats-card 
            title="Total Contracts" 
            value="{{ $contracts->total() }}" 
            icon="document-duplicate" 
            variant="primary"
        />
        <x-app.stats-card 
            title="Total Value" 
            value="{{ $company->currency }} {{ number_format($company->contracts()->sum('total_amount') / 100, 2) }}" 
            icon="banknotes" 
            variant="blue"
        />
        <x-app.stats-card 
            title="Active" 
            value="{{ $company->contracts()->where('status', 'active')->count() }}" 
            icon="check-badge" 
            variant="success"
        />
        <x-app.stats-card 
            title="Expiring Soon" 
            value="{{ $company->contracts()->where('status', 'active')->where('end_date', '<', now()->addMonth())->count() }}" 
            icon="clock" 
            variant="warning"
        />
    </x-app.stats-grid>

@php
$statusColors = [
    'draft' => 'bg-slate-500',
    'active' => 'bg-emerald-500',
    'cancelled' => 'bg-rose-500',
    'expired' => 'bg-slate-400',
];
$statusVariants = [
    'draft' => 'slate',
    'active' => 'emerald',
    'cancelled' => 'rose',
    'expired' => 'slate',
];
@endphp

    <x-app.filter-bar placeholder="Search contracts...">
        <x-app.dropdown label="Status" icon="funnel" variant="secondary" size="sm" class="shadow-sm">
            <x-app.status-filter :statuses="['draft', 'active', 'cancelled', 'expired']" :colors="$statusColors" />
        </x-app.dropdown>
    </x-app.filter-bar>

    <x-app.data-list :collection="$contracts">
        <x-slot:desktop>
            <x-app.table>
                <x-slot:head>
                    <x-app.table-th icon="document-text">Contract</x-app.table-th>
                    <x-app.table-th icon="user">Client</x-app.table-th>
                    <x-app.table-th icon="calendar">Start Date</x-app.table-th>
                    <x-app.table-th icon="banknotes">Amount</x-app.table-th>
                    <x-app.table-th>Status</x-app.table-th>
                    <x-app.table-th class="text-right">Actions</x-app.table-th>
                </x-slot:head>

                @forelse($contracts as $contract)
                    <x-app.table-tr hover>
                        <x-app.table-td>
                            <a href="{{ route('app.contracts.show', [$company, $contract]) }}" class="font-bold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 text-xs">
                                {{ $contract->contract_prefix }}{{ $contract->id }}
                            </a>
                        </x-app.table-td>
                        <x-app.table-td>
                            <div class="flex items-center gap-2.5">
                                <x-app.avatar :name="$contract->client->name" size="xs" />
                                <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">{{ $contract->client->name }}</span>
                            </div>
                        </x-app.table-td>
                        <x-app.table-td>
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">{{ $contract->date->format('M d, Y') }}</span>
                        </x-app.table-td>
                        <x-app.table-td>
                            <x-app.currency :amount="$contract->total_amount" :currency="$contract->currency" class="text-sm font-bold text-slate-900 dark:text-white" />
                        </x-app.table-td>
                        <x-app.table-td>
                            <x-app.status-badge :status="$contract->status" :variant="$statusVariants[strtolower($contract->status)] ?? 'slate'" size="sm" dot />
                        </x-app.table-td>
                        <x-app.table-td class="text-right">
                            <x-app.action-buttons 
                                :show-route="route('app.contracts.show', [$company, $contract])"
                                :edit-route="route('app.contracts.edit', [$company, $contract])"
                            />
                        </x-app.table-td>
                    </x-app.table-tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 italic text-xs">No contracts found</td>
                    </tr>
                @endforelse
            </x-app.table>
        </x-slot:desktop>

        <x-slot:mobile>
            @foreach($contracts as $contract)
                <x-app.card hover padding="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <a href="{{ route('app.contracts.show', [$company, $contract]) }}" class="font-bold text-violet-600 dark:text-violet-400 text-sm">#{{ $contract->contract_prefix }}{{ $contract->id }}</a>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $contract->client->name }}</p>
                        </div>
                        <x-app.status-badge :status="$contract->status" :variant="$statusVariants[strtolower($contract->status)] ?? 'slate'" size="xs" dot />
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-700/50">
                        <div>
                            <x-app.currency :amount="$contract->total_amount" :currency="$contract->currency" class="text-xl font-bold text-slate-900 dark:text-white" />
                            <p class="text-[10px] text-slate-400 dark:text-slate-500">Started: {{ $contract->date->format('M d, Y') }}</p>
                        </div>
                        <x-app.action-buttons 
                            :show-route="route('app.contracts.show', [$company, $contract])"
                            size="icon-sm"
                        />
                    </div>
                </x-app.card>
            @endforeach
        </x-slot:mobile>
    </x-app.data-list>
</div>
@endsection
