@extends('layouts.app')

@section('title', 'Contract Details - App')
@section('page-title', 'View Contract')
@section('page-subtitle', 'Review contract terms and agreement details')
@section('page-badge', 'Contract')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.contracts.index', $company) }}" variant="secondary" size="sm" class="hidden sm:flex">
        Back to List
    </x-app.button>
    <x-app.button href="{{ route('app.contracts.edit', [$company, $contract]) }}" variant="secondary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        Edit
    </x-app.button>
    <form action="{{ route('app.contracts.convert-invoice', [$company, $contract]) }}" method="POST">
        @csrf
        <x-app.button type="submit" variant="primary" size="sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Generate Invoice
        </x-app.button>
    </form>
</div>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Contract Header Info --}}
    <x-app.card class="relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 -mr-16 -mt-16 bg-violet-500/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 -ml-16 -mb-16 bg-fuchsia-500/5 rounded-full blur-3xl"></div>

        <div class="flex flex-col md:flex-row justify-between gap-8 p-4 relative z-10">
            <div>
                <x-app.badge variant="secondary" size="sm" class="mb-2">CONTRACT</x-app.badge>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white font-mono uppercase tracking-tight">
                    {{ $contract->contract_prefix }}{{ $contract->id }}
                </h2>
                <div class="flex items-center gap-4 mt-2">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Start Date</span>
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $contract->date->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <div class="inline-flex flex-col items-end">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status</span>
                    @php
                        $statusColors = [
                            'draft' => 'slate',
                            'active' => 'emerald',
                            'cancelled' => 'rose',
                            'expired' => 'slate',
                        ];
                        $color = $statusColors[strtolower($contract->status)] ?? 'violet';
                    @endphp
                    <x-app.status-badge status="{{ $contract->status }}" variant="{{ $color }}" />
                </div>
                <div class="mt-4">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Total Contract Value</span>
                    <span class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($contract->total, 2) }} {{ $contract->currency }}</span>
                </div>
            </div>
        </div>
    </x-app.card>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Client Details --}}
        <x-app.card class="md:col-span-1">
            <x-app.section-header title="Client Details" icon="user" variant="primary" />
            <div class="mt-4 space-y-4">
                <div class="flex items-center gap-3">
                    <x-app.avatar :name="$contract->client->name" size="sm" />
                    <div>
                        <div class="font-bold text-slate-900 dark:text-white text-sm">{{ $contract->client->name }}</div>
                        <div class="text-[10px] text-slate-500 line-clamp-1 italic">{{ $contract->client->email }}</div>
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-2">
                    <div class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $contract->client->address }}, {{ $contract->client->city }}</span>
                    </div>
                </div>
                <x-app.button href="{{ route('app.clients.show', [$company, $contract->client]) }}" variant="secondary" size="xs" class="w-full">
                    View Full Profile
                </x-app.button>
            </div>
        </x-app.card>

        {{-- Items Table --}}
        <x-app.card class="md:col-span-2 no-padding overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Contract Items
                </h3>
            </div>
            
            <x-app.table>
                <x-slot name="head">
                    <x-app.table-th>Description</x-slot>
                    <x-app.table-th class="text-center w-20">Qty</x-app.table-th>
                    <x-app.table-th class="text-right w-32">Unit Price</x-app.table-th>
                    <x-app.table-th class="text-right w-32">Total</x-app.table-th>
                </x-slot>

                @foreach($contract->items as $item)
                    <x-app.table-tr>
                        <x-app.table-td>
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900 dark:text-white text-xs">{{ $item->name }}</span>
                                @if($item->description)
                                    <span class="text-[10px] text-slate-500 font-medium">{{ $item->description }}</span>
                                @endif
                            </div>
                        </x-app.table-td>
                        <x-app.table-td class="text-center">
                            <span class="text-xs text-slate-700 dark:text-slate-300">{{ number_format($item->quantity, 2) }}</span>
                        </x-app.table-td>
                        <x-app.table-td class="text-right">
                            <span class="text-xs text-slate-700 dark:text-slate-300">{{ number_format($item->unit_price / 100, 2) }}</span>
                        </x-app.table-td>
                        <x-app.table-td class="text-right">
                            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ number_format($item->total / 100, 2) }}</span>
                        </x-app.table-td>
                    </x-app.table-tr>
                @endforeach
            </x-app.table>

            <div class="p-6 bg-slate-50/30 dark:bg-slate-800/20 border-t border-slate-100 dark:border-slate-800">
                <div class="flex flex-col items-end space-y-2">
                    <div class="flex items-center justify-between w-full max-w-[240px] text-xs">
                        <span class="text-slate-500 font-medium uppercase tracking-wider">Subtotal</span>
                        <span class="font-bold text-slate-900 dark:text-white">{{ number_format($contract->total / 100, 2) }} {{ $contract->currency }}</span>
                    </div>
                    <div class="flex items-center justify-between w-full max-w-[240px] pt-2 mt-2 border-t border-slate-200 dark:border-slate-700">
                        <span class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Total</span>
                        <span class="text-xl font-black text-violet-600 dark:text-violet-400">{{ number_format($contract->total / 100, 2) }} {{ $contract->currency }}</span>
                    </div>
                </div>
            </div>
        </x-app.card>
    </div>
</div>
@endsection
