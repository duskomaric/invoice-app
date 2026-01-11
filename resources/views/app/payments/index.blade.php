<x-app-layout>
    <x-slot name="title">Payments - {{ $company->name }}</x-slot>

    <x-pines.page-header title="Payments" description="Track income and expenses">
        <x-slot name="actions">
            <x-pines.button href="{{ route('app.payments.create', $company) }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Payment
            </x-pines.button>
        </x-slot>
    </x-pines.page-header>

    <x-pines.table>
        <x-slot name="head">
            <tr>
                <x-pines.th>Date</x-pines.th>
                <x-pines.th>Client</x-pines.th>
                <x-pines.th>Invoice</x-pines.th>
                <x-pines.th>Type</x-pines.th>
                <x-pines.th align="right">Amount</x-pines.th>
                <x-pines.th align="right">Actions</x-pines.th>
            </tr>
        </x-slot>

        @forelse($payments as $payment)
            <tr class="hover:bg-neutral-50">
                <x-pines.td>{{ $payment->payment_date?->format('d.m.Y') }}</x-pines.td>
                <x-pines.td>{{ $payment->client?->name ?? '-' }}</x-pines.td>
                <x-pines.td>
                    @if($payment->invoice)
                        <a href="{{ route('app.invoices.show', [$company, $payment->invoice]) }}" class="text-blue-600 hover:text-blue-800 hover:underline">{{ $payment->invoice->formatted_number }}</a>
                    @else
                        -
                    @endif
                </x-pines.td>
                <x-pines.td>
                    <x-pines.badge :variant="$payment->type?->value === 'income' ? 'success' : 'danger'">
                        {{ ucfirst($payment->type?->value ?? '-') }}
                    </x-pines.badge>
                </x-pines.td>
                <x-pines.td align="right" class="font-medium {{ $payment->type?->value === 'income' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $payment->type?->value === 'income' ? '+' : '-' }}{{ number_format($payment->amount / 100, 2) }}
                </x-pines.td>
                <x-pines.td align="right">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('app.payments.show', [$company, $payment]) }}" class="p-1.5 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded" title="View">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="{{ route('app.payments.edit', [$company, $payment]) }}" class="p-1.5 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                    </div>
                </x-pines.td>
            </tr>
        @empty
            <tr>
                <td colspan="6">
                    <x-pines.empty-state title="No payments yet" description="Start tracking your income and expenses.">
                        <x-slot name="action">
                            <x-pines.button href="{{ route('app.payments.create', $company) }}" size="sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Add Payment
                            </x-pines.button>
                        </x-slot>
                    </x-pines.empty-state>
                </td>
            </tr>
        @endforelse
    </x-pines.table>

    @if($payments->hasPages())
        <div class="mt-4 flex items-center justify-between px-4 py-3 bg-white border border-neutral-200/60 rounded-lg">
            <div class="text-sm text-neutral-500">Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} results</div>
            {{ $payments->links() }}
        </div>
    @endif
</x-app-layout>
