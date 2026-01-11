<x-app-layout>
    <x-slot name="title">Invoice {{ $invoice->formatted_number }} - {{ $company->name }}</x-slot>

    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 text-sm text-gray-500">
                        <li><a href="{{ route('app.invoices.index', $company) }}" class="hover:text-gray-700">Invoices</a></li>
                        <li><span class="mx-1">/</span></li>
                        <li class="text-gray-900">{{ $invoice->formatted_number }}</li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-semibold text-gray-900">Invoice {{ $invoice->formatted_number }}</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('app.invoices.pdf', [$company, $invoice]) }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    PDF
                </a>
                <form action="{{ route('app.invoices.send', [$company, $invoice]) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Send
                    </button>
                </form>
                <a href="{{ route('app.invoices.edit', [$company, $invoice]) }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md hover:bg-neutral-900">
                    Edit
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Items</h3>
                </div>
                <div class="overflow-x-auto">
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
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                        @if($item->description)
                                            <div class="text-sm text-gray-500">{{ $item->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ number_format($item->unit_price / 100, 2) }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 text-right">{{ number_format($item->total / 100, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Subtotal</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 text-right">{{ number_format($invoice->subtotal / 100, 2) }} {{ $invoice->currency }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-lg font-semibold text-gray-900 text-right">Total</td>
                                <td class="px-6 py-4 text-lg font-semibold text-gray-900 text-right">{{ number_format($invoice->total / 100, 2) }} {{ $invoice->currency }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($invoice->notes)
                    <div class="px-6 py-4 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Notes</h4>
                        <p class="text-sm text-gray-600">{{ $invoice->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-4">Invoice Details</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $invoice->status->value === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $invoice->status->value === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $invoice->status->value === 'sent' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $invoice->status->value === 'overdue' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $invoice->status->value }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Date</dt>
                        <dd class="text-sm text-gray-900">{{ $invoice->date?->format('d.m.Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Due Date</dt>
                        <dd class="text-sm text-gray-900">{{ $invoice->due_date?->format('d.m.Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Currency</dt>
                        <dd class="text-sm text-gray-900">{{ $invoice->currency }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-4">Client</h3>
                @if($invoice->client)
                    <div class="text-sm">
                        <p class="font-medium text-gray-900">{{ $invoice->client->name }}</p>
                        @if($invoice->client->email)
                            <p class="text-gray-500">{{ $invoice->client->email }}</p>
                        @endif
                        @if($invoice->client->address)
                            <p class="text-gray-500 mt-2">{{ $invoice->client->address }}</p>
                        @endif
                        @if($invoice->client->city || $invoice->client->postal_code)
                            <p class="text-gray-500">{{ $invoice->client->postal_code }} {{ $invoice->client->city }}</p>
                        @endif
                    </div>
                    <a href="{{ route('app.clients.show', [$company, $invoice->client]) }}" class="mt-3 inline-flex text-sm text-indigo-600 hover:text-indigo-500">
                        View client →
                    </a>
                @else
                    <p class="text-sm text-gray-500">No client assigned</p>
                @endif
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-4">Actions</h3>
                <div class="space-y-2">
                    <form action="{{ route('app.invoices.destroy', [$company, $invoice]) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100">
                            Delete Invoice
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
