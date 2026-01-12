@extends('layouts.app')

@section('title', 'Clients - App')
@section('page-title', 'Clients')
@section('page-subtitle', 'Manage your customer database')
@section('page-badge', $clients->total())

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button variant="secondary" size="sm" class="hidden sm:flex">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
        Export
    </x-app.button>
    <x-app.button href="{{ route('app.clients.create', $company) }}" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Client
    </x-app.button>
</div>
@endsection

@section('content')
<div class="space-y-5">
    <x-app.stats-grid>
        <x-app.stats-card
            title="Total Clients"
            value="{{ $clients->total() }}"
            icon="users"
            variant="primary"
        />
        <x-app.stats-card
            title="New This Month"
            value="{{ $company->clients()->where('created_at', '>=', now()->startOfMonth())->count() }}"
            icon="user-plus"
            variant="success"
        />
        <x-app.stats-card
            title="Active Projects"
            value="{{ $company->contracts()->where('status', 'active')->count() }}"
            icon="briefcase"
            variant="blue"
        />
{{--        <x-app.stats-grid>--}}
{{--            <x-app.stats-card--}}
{{--                title="Revenue"--}}
{{--                value="{{ $company->currency }} {{ number_format($company->invoices()->where('status', 'paid')->sum('total_amount') / 100, 2) }}"--}}
{{--                icon="banknotes"--}}
{{--                variant="violet"--}}
{{--            />--}}
{{--        </x-app.stats-grid>--}}
    </x-app.stats-grid>

    <x-app.filter-bar placeholder="Search clients...">
        <x-app.dropdown label="All Regions" icon="globe-alt" variant="secondary" size="sm" class="shadow-sm">
            <a href="#" class="block px-3 py-2 text-xs text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50">Europe</a>
            <a href="#" class="block px-3 py-2 text-xs text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50">North America</a>
        </x-app.dropdown>
    </x-app.filter-bar>

    <x-app.data-list :collection="$clients">
        <x-slot:desktop>
            <x-app.table>
                <x-slot name="head">
                    <x-app.table-th icon="user">Client</x-app.table-th>
                    <x-app.table-th icon="envelope">Contact Info</x-app.table-th>
                    <x-app.table-th icon="map-pin">Location</x-app.table-th>
                    <x-app.table-th icon="document-duplicate">Invoices</x-app.table-th>
                    <x-app.table-th class="text-right">Actions</x-app.table-th>
                </x-slot:head>

                @forelse($clients as $client)
                    <x-app.table-tr hover>
                        <x-app.table-td>
                            <div class="flex items-center gap-3">
                                <x-app.avatar :name="$client->name" size="sm" />
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white text-sm">{{ $client->name }}</div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400">ID: #{{ str_pad($client->id, 5, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </x-app.table-td>
                        <x-app.table-td>
                            <div class="space-y-0.5">
                                <div class="text-xs text-slate-700 dark:text-slate-300 font-medium">{{ $client->email ?? 'No email' }}</div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400">{{ $client->phone ?? 'No phone' }}</div>
                            </div>
                        </x-app.table-td>
                        <x-app.table-td>
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs text-slate-600 dark:text-slate-400">{{ $client->city }}, {{ $client->country }}</span>
                            </div>
                        </x-app.table-td>
                        <x-app.table-td>
                            <x-app.status-badge :status="($client->invoices_count ?? 0) . ' Invoices'" variant="slate" size="sm" />
                        </x-app.table-td>
                        <x-app.table-td class="text-right">
                            <x-app.action-buttons
                                :show-route="route('app.clients.show', [$company, $client])"
                                :edit-route="route('app.clients.edit', [$company, $client])"
                                :delete-route="route('app.clients.destroy', [$company, $client])"
                            />
                        </x-app.table-td>
                    </x-app.table-tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400 italic text-xs">No clients found</td>
                    </tr>
                @endforelse
            </x-app.table>
        </x-slot:desktop>

        <x-slot:mobile>
            @foreach($clients as $client)
                <x-app.card hover padding="p-4">
                    <div class="flex items-start gap-3 mb-4">
                        <x-app.avatar :name="$client->name" size="md" />
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-900 dark:text-white truncate">{{ $client->name }}</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $client->email }}</p>
                        </div>
                        <x-app.action-buttons
                            :show-route="route('app.clients.show', [$company, $client])"
                            size="icon-sm"
                        />
                    </div>
                    <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-100 dark:border-slate-700/50">
                        <div>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-black tracking-wider">Location</p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 mt-1">{{ $client->city }}, {{ $client->country }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-black tracking-wider">Invoices</p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 mt-1">{{ $client->invoices_count ?? 0 }} Total</p>
                        </div>
                    </div>
                </x-app.card>
            @endforeach
        </x-slot:mobile>
    </x-app.data-list>
</div>
@endsection
