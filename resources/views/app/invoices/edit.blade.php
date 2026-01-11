<x-app-layout>
    <x-slot name="title">Edit Invoice {{ $invoice->formatted_number }} - {{ $company->name }}</x-slot>

    <div class="mb-6">
        <nav class="flex mb-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 text-sm text-gray-500">
                <li><a href="{{ route('app.invoices.index', $company) }}" class="hover:text-gray-700">Invoices</a></li>
                <li><span class="mx-1">/</span></li>
                <li><a href="{{ route('app.invoices.show', [$company, $invoice]) }}" class="hover:text-gray-700">{{ $invoice->formatted_number }}</a></li>
                <li><span class="mx-1">/</span></li>
                <li class="text-gray-900">Edit</li>
            </ol>
        </nav>
        <h1 class="text-2xl font-semibold text-gray-900">Edit Invoice {{ $invoice->formatted_number }}</h1>
    </div>

    <form action="{{ route('app.invoices.update', [$company, $invoice]) }}" method="POST" 
          x-data="invoiceForm({{ json_encode($invoice->items->map(fn($i) => ['name' => $i->name, 'quantity' => $i->quantity, 'unit_price' => $i->unit_price / 100, 'total' => $i->total / 100])) }})">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                            <select name="client_id" id="client_id" required
                                    class="w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400">
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ $invoice->client_id == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" required
                                    class="w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400">
                                <option value="draft" {{ $invoice->status->value === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="sent" {{ $invoice->status->value === 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="paid" {{ $invoice->status->value === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ $invoice->status->value === 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="cancelled" {{ $invoice->status->value === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="date" id="date" value="{{ $invoice->date?->format('Y-m-d') }}" required
                                   class="w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400">
                        </div>

                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                            <input type="date" name="due_date" id="due_date" value="{{ $invoice->due_date?->format('Y-m-d') }}"
                                   class="w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400">
                        </div>

                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                            <select name="currency" id="currency" required
                                    class="w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400">
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->code }}" {{ $invoice->currency == $currency->code ? 'selected' : '' }}>
                                        {{ $currency->code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                            <select name="language" id="language" required
                                    class="w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400">
                                <option value="en" {{ $invoice->language?->value === 'en' ? 'selected' : '' }}>English</option>
                                <option value="bs" {{ $invoice->language?->value === 'bs' ? 'selected' : '' }}>Bosnian</option>
                                <option value="hr" {{ $invoice->language?->value === 'hr' ? 'selected' : '' }}>Croatian</option>
                                <option value="sr" {{ $invoice->language?->value === 'sr' ? 'selected' : '' }}>Serbian</option>
                                <option value="de" {{ $invoice->language?->value === 'de' ? 'selected' : '' }}>German</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Items</h3>
                        <button type="button" @click="addItem()" 
                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Item
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-5">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Name</label>
                                        <input type="text" x-model="item.name" :name="'items['+index+'][name]'" required
                                               class="w-full h-9 px-3 py-1 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Qty</label>
                                        <input type="number" x-model="item.quantity" :name="'items['+index+'][quantity]'" min="1" required
                                               @input="calculateTotal(index)"
                                               class="w-full h-9 px-3 py-1 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Price</label>
                                        <input type="number" x-model="item.unit_price" :name="'items['+index+'][unit_price]'" min="0" step="0.01" required
                                               @input="calculateTotal(index)"
                                               class="w-full h-9 px-3 py-1 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Total</label>
                                        <div class="h-9 px-3 py-1 text-sm bg-gray-50 border border-gray-200 rounded-md flex items-center" x-text="formatNumber(item.total)"></div>
                                    </div>
                                    <div class="col-span-1 flex items-end justify-end">
                                        <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                                class="h-9 w-9 flex items-center justify-center text-red-500 hover:text-red-700 hover:bg-red-50 rounded-md">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-end">
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Subtotal</div>
                                <div class="text-2xl font-semibold text-gray-900" x-text="formatNumber(grandTotal())"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                    <textarea name="notes" rows="3" 
                              class="w-full px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400"
                              placeholder="Add any notes or payment instructions...">{{ $invoice->notes }}</textarea>
                </div>
            </div>

            <div>
                <div class="bg-white shadow rounded-lg p-6 sticky top-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Summary</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Items</dt>
                            <dd class="font-medium text-gray-900" x-text="items.length"></dd>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-gray-200">
                            <dt class="text-gray-900 font-medium">Total</dt>
                            <dd class="font-semibold text-gray-900" x-text="formatNumber(grandTotal())"></dd>
                        </div>
                    </dl>

                    <div class="mt-6 space-y-3">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md hover:bg-neutral-900">
                            Update Invoice
                        </button>
                        <a href="{{ route('app.invoices.show', [$company, $invoice]) }}" class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function invoiceForm(existingItems = []) {
            return {
                items: existingItems.length > 0 ? existingItems : [{ name: '', quantity: 1, unit_price: 0, total: 0 }],
                
                addItem() {
                    this.items.push({ name: '', quantity: 1, unit_price: 0, total: 0 });
                },
                
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                
                calculateTotal(index) {
                    this.items[index].total = (parseFloat(this.items[index].quantity) || 0) * (parseFloat(this.items[index].unit_price) || 0);
                },
                
                grandTotal() {
                    return this.items.reduce((sum, item) => sum + (item.total || 0), 0);
                },
                
                formatNumber(num) {
                    return new Intl.NumberFormat('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
                }
            }
        }
    </script>
</x-app-layout>
