<x-app-layout>
    <x-slot name="title">Payment - {{ $company->name }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <nav class="flex mb-2 text-sm text-gray-500">
                <a href="{{ route('app.payments.index', $company) }}" class="hover:text-gray-700">Payments</a>
                <span class="mx-1">/</span>
                <span class="text-gray-900">{{ $payment->payment_date?->format('d.m.Y') }}</span>
            </nav>
            <h1 class="text-2xl font-semibold text-gray-900">Payment Details</h1>
        </div>
        <a href="{{ route('app.payments.edit', [$company, $payment]) }}" class="px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md hover:bg-neutral-900">Edit</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white shadow rounded-lg p-6">
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Date</dt><dd class="font-medium">{{ $payment->payment_date?->format('d.m.Y') }}</dd></div>
                <div><dt class="text-gray-500">Type</dt><dd><span class="px-2 py-1 text-xs rounded-full {{ $payment->type?->value === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $payment->type?->value }}</span></dd></div>
                <div><dt class="text-gray-500">Amount</dt><dd class="font-medium text-lg {{ $payment->type?->value === 'income' ? 'text-green-600' : 'text-red-600' }}">{{ number_format($payment->amount / 100, 2) }}</dd></div>
                <div><dt class="text-gray-500">Payment Method</dt><dd class="font-medium">{{ $payment->payment_method ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Client</dt><dd class="font-medium">{{ $payment->client?->name ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Invoice</dt><dd class="font-medium">
                    @if($payment->invoice)
                        <a href="{{ route('app.invoices.show', [$company, $payment->invoice]) }}" class="text-indigo-600">{{ $payment->invoice->formatted_number }}</a>
                    @else - @endif
                </dd></div>
            </dl>
            @if($payment->notes)
                <div class="mt-4 pt-4 border-t">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Notes</h4>
                    <p class="text-sm">{{ $payment->notes }}</p>
                </div>
            @endif
        </div>

        <div class="bg-white shadow rounded-lg p-6 h-fit">
            <form action="{{ route('app.payments.destroy', [$company, $payment]) }}" method="POST" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100">Delete Payment</button>
            </form>
        </div>
    </div>
</x-app-layout>
