@extends('layouts.app')

@section('title', 'Quotes - App')
@section('page-title', 'Quotes')
@section('page-subtitle', 'Manage your price offerings and estimates')
@section('page-badge', $quotes->total())

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.quotes.create', $company) }}" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Quote
    </x-app.button>
</div>
@endsection

@php
$statusColors = [
    'draft' => 'bg-slate-500',
    'sent' => 'bg-amber-500',
    'accepted' => 'bg-emerald-500',
    'declined' => 'bg-rose-500',
    'expired' => 'bg-slate-400',
];
$statusVariants = [
    'draft' => 'slate',
    'sent' => 'amber',
    'accepted' => 'emerald',
    'declined' => 'rose',
    'expired' => 'slate',
];
@endphp

@section('content')
<div class="space-y-5">
    <x-app.stats-grid>
        <x-app.stats-card 
            title="Total Quotes" 
            value="{{ $company->currency }} {{ number_format($quotes->sum('total_amount') / 100, 2) }}" 
            icon="document-text" 
            variant="primary"
        />
        <x-app.stats-card 
            title="Accepted" 
            value="{{ $company->quotes()->where('status', 'accepted')->count() }}" 
            icon="check-badge" 
            variant="success"
        />
        <x-app.stats-card 
            title="Pending" 
            value="{{ $company->quotes()->where('status', 'sent')->count() }}" 
            icon="clock" 
            variant="warning"
        />
        <x-app.stats-card 
            title="Expired" 
            value="{{ $company->quotes()->where('status', 'expired')->count() }}" 
            icon="x-circle" 
            variant="slate"
        />
    </x-app.stats-grid>

    <x-app.filter-bar placeholder="Search quotes...">
        <x-app.dropdown label="Status" icon="funnel" variant="secondary" size="sm" class="shadow-sm">
            <x-app.status-filter :statuses="['draft', 'sent', 'accepted', 'declined', 'expired']" :colors="$statusColors" />
        </x-app.dropdown>
    </x-app.filter-bar>

    <x-app.data-list :collection="$quotes">
        <x-slot:desktop>
            <x-app.table>
                <x-slot:head>
                    <x-app.table-th icon="document-text">Quote</x-app.table-th>
                    <x-app.table-th icon="user">Client</x-app.table-th>
                    <x-app.table-th icon="currency-dollar">Amount</x-app.table-th>
                    <x-app.table-th>Status</x-app.table-th>
                    <x-app.table-th icon="calendar">Valid Until</x-app.table-th>
                    <x-app.table-th align="right">Actions</x-app.table-th>
                </x-slot:head>

                @forelse($quotes as $quote)
                    <x-app.table-tr hover>
                        <x-app.table-td>
                            <a href="{{ route('app.quotes.show', [$company, $quote]) }}" class="font-bold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 text-xs">
                                {{ $quote->quote_prefix }}{{ $quote->id }}
                            </a>
                        </x-app.table-td>
                        <x-app.table-td>
                            <div class="flex items-center gap-2.5">
                                <x-app.avatar :name="$quote->client->name" size="sm" />
                                <div>
                                    <p class="font-medium text-slate-800 dark:text-white text-xs">{{ $quote->client->name }}</p>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500">{{ $quote->client->email }}</p>
                                </div>
                            </div>
                        </x-app.table-td>
                        <x-app.table-td>
                            <x-app.currency :amount="$quote->total_amount" :currency="$quote->currency" class="font-bold text-slate-900 dark:text-white text-sm" />
                        </x-app.table-td>
                        <x-app.table-td>
                            <x-app.status-badge :status="$quote->status->value" :variant="$statusVariants[strtolower($quote->status->value)] ?? 'slate'" size="sm" dot :pulse="strtolower($quote->status->value) === 'sent'" />
                        </x-app.table-td>
                        <x-app.table-td class="text-slate-500 dark:text-slate-400 text-xs">
                            {{ $quote->valid_until->format('M d, Y') }}
                        </x-app.table-td>
                        <x-app.table-td class="text-right">
                            <x-app.action-buttons 
                                :show-route="route('app.quotes.show', [$company, $quote])"
                                :edit-route="route('app.quotes.edit', [$company, $quote])"
                            />
                        </x-app.table-td>
                    </x-app.table-tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 italic text-xs">No quotes found</td>
                    </tr>
                @endforelse
            </x-app.table>
        </x-slot:desktop>
        
        <x-slot:mobile>
            @foreach($quotes as $quote)
            <x-app.card hover padding="p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <a href="{{ route('app.quotes.show', [$company, $quote]) }}" class="font-bold text-violet-600 dark:text-violet-400 text-sm">#{{ $quote->quote_prefix }}{{ $quote->id }}</a>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $quote->client->name }}</p>
                    </div>
                    <x-app.status-badge :status="$quote->status->value" :variant="$statusVariants[strtolower($quote->status->value)] ?? 'slate'" size="xs" dot />
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-700/50">
                    <div>
                        <x-app.currency :amount="$quote->total_amount" :currency="$quote->currency" class="text-xl font-bold text-slate-900 dark:text-white" />
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">Until: {{ $quote->valid_until->format('M d') }}</p>
                    </div>
                    <x-app.action-buttons 
                        :show-route="route('app.quotes.show', [$company, $quote])"
                        :edit-route="route('app.quotes.edit', [$company, $quote])"
                        size="icon-sm"
                    />
                </div>
            </x-app.card>
            @endforeach
        </x-slot:mobile>
    </x-app.data-list>
</div>
@endsection
