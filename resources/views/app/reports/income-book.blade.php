@extends('layouts.app')

@section('title', 'Income Book - Reports - App')
@section('page-title', 'Income Book')
@section('page-subtitle', 'Official record of issued invoices and earned income')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.reports.index', $company) }}" variant="secondary" size="sm" class="hidden sm:flex">
        Back to Reports
    </x-app.button>
    <x-app.button variant="primary" size="sm" onclick="window.print()">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        Print Report
    </x-app.button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Filter Toolbar --}}
    <x-app.card class="no-padding">
        <div class="p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <form action="{{ route('app.reports.income-book', $company) }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex flex-col">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Fiscal Year</label>
                    <select name="year" onchange="this.form.submit()" class="h-10 px-3 rounded-xl bg-slate-50 dark:bg-slate-900 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm font-bold">
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Month (Optional)</label>
                    <select name="month" onchange="this.form.submit()" class="h-10 px-3 rounded-xl bg-slate-50 dark:bg-slate-900 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-violet-500 transition-all text-sm">
                        <option value="">All Months</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="flex gap-4">
                <div class="text-right">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Cash</div>
                    <div class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($totals['cash'], 2) }}</div>
                </div>
                <div class="text-right border-l border-slate-100 dark:border-slate-800 pl-4">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Bank</div>
                    <div class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($totals['bank'], 2) }}</div>
                </div>
                <div class="text-right border-l border-slate-100 dark:border-slate-800 pl-4">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Grand Total</div>
                    <div class="text-lg font-bold text-violet-600 dark:text-violet-400">{{ number_format($totals['total'], 2) }}</div>
                </div>
            </div>
        </div>
    </x-app.card>

    {{-- Report Table --}}
    <x-app.card class="no-padding overflow-hidden">
        <x-app.table>
            <x-slot name="head">
                <x-app.table-th class="w-12">#</x-app.table-th>
                <x-app.table-th>Date</x-app.table-th>
                <x-app.table-th>Document</x-app.table-th>
                <x-app.table-th>Client</x-app.table-th>
                <x-app.table-th class="text-right">Cash Amount</x-app.table-th>
                <x-app.table-th class="text-right">Bank Amount</x-app.table-th>
                <x-app.table-th class="text-right">Total</x-app.table-th>
            </x-slot>

            @php $counter = ($entries->currentPage() - 1) * $entries->perPage() + 1; @endphp
            @forelse($entries as $entry)
                <x-app.table-tr>
                    <x-app.table-td class="font-mono text-[10px] text-slate-400">{{ $counter++ }}</x-app.table-td>
                    <x-app.table-td class="text-xs font-medium">{{ $entry->date->format('d.m.Y') }}</x-app.table-td>
                    <x-app.table-td>
                        <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $entry->document_number }}</span>
                    </x-app.table-td>
                    <x-app.table-td class="text-xs text-slate-600 dark:text-slate-400">{{ $entry->client_name }}</x-app.table-td>
                    <x-app.table-td class="text-right font-mono text-xs">
                        {{ $entry->payment_method === 'cash' ? number_format($entry->amount, 2) : '-' }}
                    </x-app.table-td>
                    <x-app.table-td class="text-right font-mono text-xs">
                        {{ $entry->payment_method === 'bank' ? number_format($entry->amount, 2) : '-' }}
                    </x-app.table-td>
                    <x-app.table-td class="text-right font-bold text-xs text-slate-900 dark:text-white">
                        {{ number_format($entry->amount, 2) }}
                    </x-app.table-td>
                </x-app.table-tr>
            @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-slate-400 italic text-sm">
                        No entries found for the selected period.
                    </td>
                </tr>
            @endforelse
        </x-app.table>

        @if($entries->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $entries->links() }}
            </div>
        @endif
    </x-app.card>
</div>

<style>
    @media print {
        header, .sidebar, .header-actions, .filter-toolbar button, .filter-toolbar select, .page-subtitle, .page-badge {
            display: none !important;
        }
        body {
            background: white !important;
            padding: 0 !important;
        }
        .x-app-card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection
