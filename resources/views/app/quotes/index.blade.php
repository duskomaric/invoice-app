<x-app-layout>
    <x-slot name="title">Quotes - {{ $company->name }}</x-slot>

    <x-pines.page-header title="Quotes" description="Manage your quotes">
        <x-slot name="actions">
            <x-pines.button href="{{ route('app.quotes.create', $company) }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Quote
            </x-pines.button>
        </x-slot>
    </x-pines.page-header>

    <x-pines.table>
        <x-slot name="head">
            <tr>
                <x-pines.th>Number</x-pines.th>
                <x-pines.th>Client</x-pines.th>
                <x-pines.th>Date</x-pines.th>
                <x-pines.th>Valid Until</x-pines.th>
                <x-pines.th>Status</x-pines.th>
                <x-pines.th align="right">Total</x-pines.th>
                <x-pines.th align="right">Actions</x-pines.th>
            </tr>
        </x-slot>

        @forelse($quotes as $quote)
            <tr class="hover:bg-neutral-50">
                <x-pines.td class="font-medium">
                    <a href="{{ route('app.quotes.show', [$company, $quote]) }}" class="text-blue-600 hover:text-blue-800 hover:underline">{{ $quote->formatted_number }}</a>
                </x-pines.td>
                <x-pines.td>{{ $quote->client?->name ?? '-' }}</x-pines.td>
                <x-pines.td>{{ $quote->date?->format('d.m.Y') }}</x-pines.td>
                <x-pines.td>{{ $quote->valid_until?->format('d.m.Y') }}</x-pines.td>
                <x-pines.td>
                    <x-pines.badge :variant="match($quote->status->value) {
                        'accepted' => 'success',
                        'draft' => 'default',
                        'sent' => 'info',
                        'rejected' => 'danger',
                        default => 'default'
                    }">{{ ucfirst($quote->status->value) }}</x-pines.badge>
                </x-pines.td>
                <x-pines.td align="right" class="font-medium">{{ number_format($quote->total / 100, 2) }} {{ $quote->currency }}</x-pines.td>
                <x-pines.td align="right">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('app.quotes.show', [$company, $quote]) }}" class="p-1.5 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded" title="View">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="{{ route('app.quotes.edit', [$company, $quote]) }}" class="p-1.5 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                    </div>
                </x-pines.td>
            </tr>
        @empty
            <tr>
                <td colspan="7">
                    <x-pines.empty-state title="No quotes yet" description="Get started by creating your first quote.">
                        <x-slot name="action">
                            <x-pines.button href="{{ route('app.quotes.create', $company) }}" size="sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Create Quote
                            </x-pines.button>
                        </x-slot>
                    </x-pines.empty-state>
                </td>
            </tr>
        @endforelse
    </x-pines.table>

    @if($quotes->hasPages())
        <div class="mt-4 flex items-center justify-between px-4 py-3 bg-white border border-neutral-200/60 rounded-lg">
            <div class="text-sm text-neutral-500">Showing {{ $quotes->firstItem() }} to {{ $quotes->lastItem() }} of {{ $quotes->total() }} results</div>
            {{ $quotes->links() }}
        </div>
    @endif
</x-app-layout>
