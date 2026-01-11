<x-app-layout>
    <x-slot name="title">Quote {{ $quote->formatted_number }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <nav class="flex mb-2 text-sm text-gray-500">
                <a href="{{ route('app.quotes.index', $company) }}" class="hover:text-gray-700">Quotes</a>
                <span class="mx-1">/</span>
                <span class="text-gray-900">{{ $quote->formatted_number }}</span>
            </nav>
            <h1 class="text-2xl font-semibold text-gray-900">Quote {{ $quote->formatted_number }}</h1>
        </div>
        <div class="flex space-x-3">
            <form action="{{ route('app.quotes.convert-to-proforma', [$company, $quote]) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Convert to Proforma</button>
            </form>
            <form action="{{ route('app.quotes.convert-to-invoice', [$company, $quote]) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Convert to Invoice</button>
            </form>
            <a href="{{ route('app.quotes.edit', [$company, $quote]) }}" class="px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md hover:bg-neutral-900">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b"><h3 class="text-lg font-medium">Items</h3></div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($quote->items as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $item->name }}</td>
                            <td class="px-6 py-4 text-sm text-right">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-sm text-right">{{ number_format($item->unit_price / 100, 2) }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-right">{{ number_format($item->total / 100, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-lg font-semibold text-right">Total</td>
                        <td class="px-6 py-4 text-lg font-semibold text-right">{{ number_format($quote->total / 100, 2) }} {{ $quote->currency }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-4">Details</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd><span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100">{{ $quote->status->value }}</span></dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Date</dt><dd>{{ $quote->date?->format('d.m.Y') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Valid Until</dt><dd>{{ $quote->valid_until?->format('d.m.Y') }}</dd></div>
                </dl>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-4">Client</h3>
                @if($quote->client)
                    <p class="text-sm font-medium">{{ $quote->client->name }}</p>
                    <p class="text-sm text-gray-500">{{ $quote->client->email }}</p>
                @endif
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <form action="{{ route('app.quotes.destroy', [$company, $quote]) }}" method="POST" onsubmit="return confirm('Delete this quote?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100">Delete Quote</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
