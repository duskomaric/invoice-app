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
            <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-2 {{ !request('status') ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20' : 'text-slate-600 dark:text-slate-400' }}">
                <span class="w-4 h-4 rounded border flex items-center justify-center {{ !request('status') ? 'border-violet-500 bg-violet-500' : 'border-slate-300 dark:border-slate-600' }}">
                    @if(!request('status'))<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>@endif
                </span>
                All Statuses
            </a>
            @foreach(['draft', 'sent', 'accepted', 'declined', 'expired'] as $status)
                <a href="{{ request()->fullUrlWithQuery(['status' => $status]) }}" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-2 {{ request('status') == $status ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20' : 'text-slate-600 dark:text-slate-400' }}">
                    <span class="w-4 h-4 rounded border flex items-center justify-center {{ request('status') == $status ? 'border-violet-500 bg-violet-500' : 'border-slate-300 dark:border-slate-600' }}">
                        @if(request('status') == $status)<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>@endif
                    </span>
                    @php $dotColors = ['draft' => 'bg-slate-500', 'sent' => 'bg-amber-500', 'accepted' => 'bg-emerald-500', 'declined' => 'bg-rose-500', 'expired' => 'bg-slate-400']; @endphp
                    <span class="w-2 h-2 rounded-full {{ $dotColors[$status] }}"></span>
                    {{ ucfirst($status) }}
                </a>
            @endforeach
        </x-app.dropdown>
    </x-app.filter-bar>

    <x-app.data-list :collection="$quotes">
        <x-slot:desktop>
            <x-app.table>
                <x-slot:head>
                    <th class="text-left text-[10px] font-bold text-slate-700 dark:text-slate-400 uppercase tracking-wider px-4 py-3">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Quote
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
                            Valid Until
                        </div>
                    </th>
                    <th class="text-right text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-4 py-3">Actions</th>
                </x-slot:head>

                @foreach($quotes as $quote)
                <tr class="group hover:bg-violet-50/30 dark:hover:bg-violet-900/10 transition-colors">
                    <td class="px-4 py-3">
                        <a href="{{ route('app.quotes.show', [$company, $quote]) }}" class="font-bold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 text-xs">
                            {{ $quote->quote_prefix }}{{ $quote->id }}
                        </a>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2.5">
                            <x-app.avatar :name="$quote->client->name" size="sm" />
                            <div>
                                <p class="font-medium text-slate-800 dark:text-white text-xs">{{ $quote->client->name }}</p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500">{{ $quote->client->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $quote->currency === 'EUR' ? '€' : ($quote->currency === 'USD' ? '$' : $quote->currency) }}{{ number_format($quote->total_amount / 100, 2) }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $statusColors = [
                                'draft' => 'slate',
                                'sent' => 'amber',
                                'accepted' => 'emerald',
                                'declined' => 'rose',
                                'expired' => 'slate',
                            ];
                            $color = $statusColors[strtolower($quote->status->value)] ?? 'violet';
                        @endphp
                        <x-app.status-badge :status="$quote->status->value" :variant="$color" size="sm" dot pulse="{{ strtolower($quote->status->value) === 'sent' }}" />
                    </td>
                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400 text-xs">{{ $quote->valid_until->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <x-app.button href="{{ route('app.quotes.show', [$company, $quote]) }}" variant="ghost" size="icon-xs" title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </x-app.button>
                            <x-app.button href="{{ route('app.quotes.edit', [$company, $quote]) }}" variant="ghost" size="icon-xs" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </x-app.button>
                        </div>
                    </td>
                </tr>
                @endforeach
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
                    @php
                        $statusColors = [
                            'draft' => 'slate',
                            'sent' => 'amber',
                            'accepted' => 'emerald',
                            'declined' => 'rose',
                            'expired' => 'slate',
                        ];
                        $color = $statusColors[strtolower($quote->status->value)] ?? 'violet';
                    @endphp
                    <x-app.status-badge :status="$quote->status->value" :variant="$color" size="xs" dot />
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-700/50">
                    <div>
                        <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $quote->currency === 'EUR' ? '€' : ($quote->currency === 'USD' ? '$' : $quote->currency) }}{{ number_format($quote->total_amount / 100, 2) }}</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500">Until: {{ $quote->valid_until->format('M d') }}</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <x-app.button href="{{ route('app.quotes.show', [$company, $quote]) }}" variant="ghost" size="icon-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </x-app.button>
                        <x-app.button href="{{ route('app.quotes.edit', [$company, $quote]) }}" variant="ghost" size="icon-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </x-app.button>
                    </div>
                </div>
            </x-app.card>
            @endforeach
        </x-slot:mobile>
    </x-app.data-list>
</div>
@endsection
