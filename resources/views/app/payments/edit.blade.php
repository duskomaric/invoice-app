<x-app-layout>
    <x-slot name="title">Edit Payment - {{ $company->name }}</x-slot>

    <div class="mb-6"><h1 class="text-2xl font-semibold text-gray-900">Edit Payment</h1></div>

    <form action="{{ route('app.payments.update', [$company, $payment]) }}" method="POST">
        @csrf @method('PUT')
        <div class="max-w-2xl space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" required class="w-full h-10 px-3 text-sm border rounded-md">
                            <option value="income" {{ $payment->type?->value === 'income' ? 'selected' : '' }}>Income</option>
                            <option value="expense" {{ $payment->type?->value === 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                        <input type="number" name="amount" value="{{ $payment->amount / 100 }}" step="0.01" min="0" required class="w-full h-10 px-3 text-sm border rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="payment_date" value="{{ $payment->payment_date?->format('Y-m-d') }}" required class="w-full h-10 px-3 text-sm border rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select name="payment_method" class="w-full h-10 px-3 text-sm border rounded-md">
                            <option value="">Select</option>
                            <option value="cash" {{ $payment->payment_method === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank" {{ $payment->payment_method === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="card" {{ $payment->payment_method === 'card' ? 'selected' : '' }}>Card</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                        <select name="client_id" class="w-full h-10 px-3 text-sm border rounded-md">
                            <option value="">Select</option>
                            @foreach($clients as $client)<option value="{{ $client->id }}" {{ $payment->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Invoice</label>
                        <select name="invoice_id" class="w-full h-10 px-3 text-sm border rounded-md">
                            <option value="">Select</option>
                            @foreach($invoices as $invoice)<option value="{{ $invoice->id }}" {{ $payment->invoice_id == $invoice->id ? 'selected' : '' }}>{{ $invoice->formatted_number }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full px-3 py-2 text-sm border rounded-md">{{ $payment->notes }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('app.payments.show', [$company, $payment]) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border rounded-md">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md">Update Payment</button>
            </div>
        </div>
    </form>
</x-app-layout>
