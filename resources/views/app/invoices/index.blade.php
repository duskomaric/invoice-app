<x-app-layout>
    <x-slot name="title">Invoices - {{ $company->name }}</x-slot>

    <x-pines.page-header title="Invoices" description="Manage your invoices">
        <x-slot name="actions">
            <x-pines.button href="{{ route('app.invoices.create', $company) }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Invoice
            </x-pines.button>
        </x-slot>
    </x-pines.page-header>

    <div class="space-y-4">
        @if(count($currencies) > 0)
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="inline-flex items-center p-1 bg-neutral-100 rounded-lg">
                @foreach($currencies as $currency)
                    <a href="{{ request()->fullUrlWithQuery(['currency' => $currency]) }}"
                       @class([
                           'px-4 py-2 text-sm font-medium rounded-md transition-all',
                           'bg-white shadow-sm text-neutral-900' => $activeCurrency === $currency,
                           'text-neutral-600 hover:text-neutral-900' => $activeCurrency !== $currency,
                       ])>
                        {{ $currency }}
                    </a>
                @endforeach
            </div>
            
            <div class="flex items-center gap-3">
                <form action="" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="currency" value="{{ $activeCurrency }}">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search invoices..." 
                               class="w-full sm:w-64 h-10 pl-10 pr-4 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <select name="status" onchange="this.form.submit()" class="h-10 px-3 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400">
                        <option value="">All statuses</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                        <option value="sent" @selected(request('status') === 'sent')>Sent</option>
                        <option value="paid" @selected(request('status') === 'paid')>Paid</option>
                        <option value="overdue" @selected(request('status') === 'overdue')>Overdue</option>
                    </select>
                </form>
            </div>
        </div>
        @endif

        <x-pines.table>
            <x-slot name="head">
                <tr>
                    <x-pines.th>Number</x-pines.th>
                    <x-pines.th>Client</x-pines.th>
                    <x-pines.th>Date</x-pines.th>
                    <x-pines.th>Due Date</x-pines.th>
                    <x-pines.th>Status</x-pines.th>
                    <x-pines.th align="right">Total</x-pines.th>
                    <x-pines.th align="right">Actions</x-pines.th>
                </tr>
            </x-slot>

            @forelse($invoices as $invoice)
                <tr class="hover:bg-neutral-50">
                    <x-pines.td class="font-medium">
                        <a href="{{ route('app.invoices.show', [$company, $invoice]) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                            {{ $invoice->formatted_number }}
                        </a>
                    </x-pines.td>
                    <x-pines.td>{{ $invoice->client?->name ?? '-' }}</x-pines.td>
                    <x-pines.td>{{ $invoice->date?->format('d.m.Y') }}</x-pines.td>
                    <x-pines.td>{{ $invoice->due_date?->format('d.m.Y') }}</x-pines.td>
                    <x-pines.td>
                        <x-pines.badge :variant="match($invoice->status->value) {
                            'paid' => 'success',
                            'draft' => 'default',
                            'sent' => 'info',
                            'overdue' => 'danger',
                            default => 'default'
                        }">
                            {{ ucfirst($invoice->status->value) }}
                        </x-pines.badge>
                    </x-pines.td>
                    <x-pines.td align="right" class="font-medium">
                        {{ number_format($invoice->total / 100, 2) }} {{ $invoice->currency }}
                    </x-pines.td>
                    <x-pines.td align="right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('app.invoices.show', [$company, $invoice]) }}" class="p-1.5 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded" title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('app.invoices.edit', [$company, $invoice]) }}" class="p-1.5 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <a href="{{ route('app.invoices.pdf', [$company, $invoice]) }}" class="p-1.5 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded" title="Download PDF">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </a>
                        </div>
                    </x-pines.td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <x-pines.empty-state 
                            title="No invoices yet" 
                            description="Get started by creating your first invoice.">
                            <x-slot name="action">
                                <x-pines.button href="{{ route('app.invoices.create', $company) }}" size="sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Create Invoice
                                </x-pines.button>
                            </x-slot>
                        </x-pines.empty-state>
                    </td>
                </tr>
            @endforelse
        </x-pines.table>

        @if($invoices->hasPages())
            <div class="flex items-center justify-between px-4 py-3 bg-white border border-neutral-200/60 rounded-lg">
                <div class="text-sm text-neutral-500">
                    Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} results
                </div>
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
