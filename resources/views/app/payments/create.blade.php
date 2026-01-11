<x-app-layout>
    <x-slot name="title">Record Payment - {{ $company->name }}</x-slot>

    <div class="mb-6"><h1 class="text-2xl font-semibold text-gray-900">Record Payment</h1></div>

    <form action="{{ route('app.payments.store', $company) }}" method="POST">
        @csrf
        <div class="max-w-2xl space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                        <input type="number" name="amount" step="0.01" min="0" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="payment_date" value="{{ now()->format('Y-m-d') }}" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select name="payment_method" class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                            <option value="">Select</option>
                            <option value="cash">Cash</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="card">Card</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                        <select name="client_id" class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                            <option value="">Select client</option>
                            @foreach($clients as $client)<option value="{{ $client->id }}">{{ $client->name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Invoice</label>
                        <select name="invoice_id" class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                            <option value="">Select invoice</option>
                            @foreach($invoices as $invoice)<option value="{{ $invoice->id }}">{{ $invoice->formatted_number }}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="w-full px-3 py-2 text-sm border rounded-md border-neutral-300"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('app.payments.index', $company) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border rounded-md hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md hover:bg-neutral-900">Record Payment</button>
            </div>
        </div>
    </form>
</x-app-layout>
