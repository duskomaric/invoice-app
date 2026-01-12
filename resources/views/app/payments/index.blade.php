@extends('layouts.app')

@section('title', 'Payments - App')
@section('page-title', 'Payments')
@section('page-subtitle', 'Track and manage your incoming payments')
@section('page-badge', $payments->total())

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.payments.create', $company) }}" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Record Payment
    </x-app.button>
</div>
@endsection

@section('content')
<div class="space-y-5">
    <x-app.stats-grid>
        <x-app.stats-card
            title="Total Payments"
            value="{{ $payments->total() }}"
            icon="credit-card"
            variant="primary"
        />
        <x-app.stats-card
            title="Total Received"
            value="{{ $company->currency }} {{ number_format($company->payments()->sum('amount') / 100, 2) }}"
            icon="banknotes"
            variant="success"
        />
{{--        <x-app.stats-card --}}
{{--            title="This Month" --}}
{{--            value="{{ $company->currency }} {{ number_format($company->payments()->where('date', '>=', now()->startOfMonth())->sum('amount') / 100, 2) }}" --}}
{{--            icon="calendar-days" --}}
{{--            variant="blue"--}}
{{--        />--}}
        <x-app.stats-card
            title="Avg. Payment"
            value="{{ $company->currency }} {{ number_format(($company->payments()->avg('amount') ?? 0) / 100, 2) }}"
            icon="chart-pie"
            variant="violet"
        />
    </x-app.stats-grid>

    <x-app.filter-bar placeholder="Search payments...">
        <x-app.dropdown label="Method" icon="funnel" variant="secondary" size="sm" class="shadow-sm">
            <a href="{{ request()->fullUrlWithQuery(['method' => '']) }}" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-2 {{ !request('method') ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20' : 'text-slate-600 dark:text-slate-400' }}">
                <span class="w-4 h-4 rounded border flex items-center justify-center {{ !request('method') ? 'border-violet-500 bg-violet-500' : 'border-slate-300 dark:border-slate-600' }}">
                    @if(!request('method'))<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>@endif
                </span>
                All Methods
            </a>
            @foreach(['bank_transfer', 'cash', 'credit_card', 'paypal'] as $method)
                <a href="{{ request()->fullUrlWithQuery(['method' => $method]) }}" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center gap-2 {{ request('method') == $method ? 'text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/20' : 'text-slate-600 dark:text-slate-400' }}">
                    <span class="w-4 h-4 rounded border flex items-center justify-center {{ request('method') == $method ? 'border-violet-500 bg-violet-500' : 'border-slate-300 dark:border-slate-600' }}">
                        @if(request('method') == $method)<svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>@endif
                    </span>
                    {{ ucfirst(str_replace('_', ' ', $method)) }}
                </a>
            @endforeach
        </x-app.dropdown>
    </x-app.filter-bar>

    <x-app.data-list :collection="$payments">
        <x-slot:desktop>
            <x-app.table>
                <x-slot name="head">
                    <x-app.table-th icon="hashtag">Payment ID</x-app.table-th>
                    <x-app.table-th icon="document-text">Invoice</x-app.table-th>
                    <x-app.table-th icon="user">Client</x-app.table-th>
                    <x-app.table-th icon="calendar">Date</x-app.table-th>
                    <x-app.table-th icon="banknotes">Amount</x-app.table-th>
                    <x-app.table-th icon="credit-card">Method</x-app.table-th>
                    <x-app.table-th class="text-right">Actions</x-app.table-th>
                </x-slot:head>

                @forelse($payments as $payment)
                    <x-app.table-tr hover>
                        <x-app.table-td>
                            <span class="font-bold text-slate-900 dark:text-white font-mono text-xs">#PAY-{{ $payment->id }}</span>
                        </x-app.table-td>
                        <x-app.table-td>
                            @if($payment->invoice)
                                <a href="{{ route('app.invoices.show', [$company, $payment->invoice]) }}" class="text-xs font-bold text-violet-600 hover:text-violet-700 dark:text-violet-400 font-mono">
                                    {{ $payment->invoice->invoice_prefix }}{{ $payment->invoice->id }}
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic">Unlinked</span>
                            @endif
                        </x-app.table-td>
                        <x-app.table-td>
                            <div class="flex items-center gap-2.5">
                                <x-app.avatar :name="$payment->client->name" size="xs" />
                                <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">{{ $payment->client->name }}</span>
                            </div>
                        </x-app.table-td>
                        <x-app.table-td>
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">{{ $payment->date }}</span>
{{--                            ->format('M d, Y')--}}
                        </x-app.table-td>
                        <x-app.table-td>
                            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">+{{ $payment->currency === 'EUR' ? '€' : ($payment->currency === 'USD' ? '$' : $payment->currency) }}{{ number_format($payment->amount / 100, 2) }}</span>
                        </x-app.table-td>
                        <x-app.table-td>
                            <x-app.status-badge :status="ucfirst(str_replace('_', ' ', $payment->method))" variant="slate" size="sm" />
                        </x-app.table-td>
                        <x-app.table-td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <form action="{{ route('app.payments.destroy', [$company, $payment]) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-app.button type="submit" variant="ghost" size="icon-xs" title="Delete" class="text-rose-500 hover:text-rose-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </x-app.button>
                                </form>
                            </div>
                        </x-app.table-td>
                    </x-app.table-tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400 italic text-xs">No payments found</td>
                    </tr>
                @endforelse
            </x-app.table>
        </x-slot:desktop>

        <x-slot:mobile>
            @foreach($payments as $payment)
                <x-app.card hover padding="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <span class="font-bold text-slate-900 dark:text-white font-mono text-xs">#PAY-{{ $payment->id }}</span>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $payment->client->name }}</p>
                        </div>
                        <x-app.status-badge :status="ucfirst(str_replace('_', ' ', $payment->method))" variant="slate" size="xs" />
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-700/50">
                        <div>
                            <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">+{{ $payment->currency === 'EUR' ? '€' : ($payment->currency === 'USD' ? '$' : $payment->currency) }}{{ number_format($payment->amount / 100, 2) }}</p>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500">{{ $payment->date }}</p>
{{--                            ->format('M d, Y')--}}
                        </div>
                        <div class="flex items-center gap-1">
                            @if($payment->invoice)
                                <x-app.button href="{{ route('app.invoices.show', [$company, $payment->invoice]) }}" variant="ghost" size="sm" class="text-[10px]">
                                    View Invoice
                                </x-app.button>
                            @endif
                        </div>
                    </div>
                </x-app.card>
            @endforeach
        </x-slot:mobile>
    </x-app.data-list>
</div>
@endsection
