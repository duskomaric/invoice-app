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

    <x-app.filter-bar placeholder="Search contracts...">
        <x-app.dropdown label="Status" icon="funnel" variant="secondary" size="sm" class="shadow-sm">
            <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-2 {{ !request('status') ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20' : 'text-slate-600 dark:text-slate-400' }}">
                <span class="w-4 h-4 rounded border flex items-center justify-center {{ !request('status') ? 'border-violet-500 bg-violet-500' : 'border-slate-300 dark:border-slate-600' }}">
                    @if(!request('status'))<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>@endif
                </span>
                All Statuses
            </a>
            @foreach(['draft', 'active', 'cancelled', 'expired'] as $status)
                <a href="{{ request()->fullUrlWithQuery(['status' => $status]) }}" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-2 {{ request('status') == $status ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20' : 'text-slate-600 dark:text-slate-400' }}">
                    <span class="w-4 h-4 rounded border flex items-center justify-center {{ request('status') == $status ? 'border-violet-500 bg-violet-500' : 'border-slate-300 dark:border-slate-600' }}">
                        @if(request('status') == $status)<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>@endif
                    </span>
                    @php $dotColors = ['draft' => 'bg-slate-500', 'active' => 'bg-emerald-500', 'cancelled' => 'bg-rose-500', 'expired' => 'bg-slate-400']; @endphp
                    <span class="w-2 h-2 rounded-full {{ $dotColors[$status] }}"></span>
                    {{ ucfirst($status) }}
                </a>
            @endforeach
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
                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $contract->currency === 'EUR' ? '€' : ($contract->currency === 'USD' ? '$' : $contract->currency) }}{{ number_format($contract->total_amount / 100, 2) }}</span>
                        </x-app.table-td>
                        <x-app.table-td>
                            @php
                                $statusColors = ['draft' => 'slate', 'active' => 'emerald', 'cancelled' => 'rose', 'expired' => 'slate'];
                                $color = $statusColors[strtolower($contract->status)] ?? 'violet';
                            @endphp
                            <x-app.status-badge :status="$contract->status" :variant="$color" size="sm" dot />
                        </x-app.table-td>
                        <x-app.table-td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <x-app.button href="{{ route('app.contracts.show', [$company, $contract]) }}" variant="ghost" size="icon-xs" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </x-app.button>
                                <x-app.button href="{{ route('app.contracts.edit', [$company, $contract]) }}" variant="ghost" size="icon-xs" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </x-app.button>
                            </div>
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
                        @php
                            $statusColors = ['draft' => 'slate', 'active' => 'emerald', 'cancelled' => 'rose', 'expired' => 'slate'];
                            $color = $statusColors[strtolower($contract->status)] ?? 'violet';
                        @endphp
                        <x-app.status-badge :status="$contract->status" :variant="$color" size="xs" dot />
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-700/50">
                        <div>
                            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $contract->currency === 'EUR' ? '€' : ($contract->currency === 'USD' ? '$' : $contract->currency) }}{{ number_format($contract->total_amount / 100, 2) }}</p>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500">Started: {{ $contract->date->format('M d, Y') }}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <x-app.button href="{{ route('app.contracts.show', [$company, $contract]) }}" variant="ghost" size="icon-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </x-app.button>
                        </div>
                    </div>
                </x-app.card>
            @endforeach
        </x-slot:mobile>
    </x-app.data-list>
</div>
@endsection
