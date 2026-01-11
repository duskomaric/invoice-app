<x-app-layout>
    <x-slot name="title">Create Quote - {{ $company->name }}</x-slot>

    <div class="mb-6">
        <nav class="flex mb-2 text-sm text-gray-500">
            <a href="{{ route('app.quotes.index', $company) }}" class="hover:text-gray-700">Quotes</a>
            <span class="mx-1">/</span><span class="text-gray-900">Create</span>
        </nav>
        <h1 class="text-2xl font-semibold text-gray-900">Create Quote</h1>
        <p class="text-sm text-gray-500">Next number: {{ $previewNumber }}</p>
    </div>

    <form action="{{ route('app.quotes.store', $company) }}" method="POST" x-data="documentForm()">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">Quote Details</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                            <select name="client_id" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300 focus:ring-2 focus:ring-neutral-400">
                                <option value="">Select client</option>
                                @foreach($clients as $client)<option value="{{ $client->id }}">{{ $client->name }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valid Until</label>
                            <input type="date" name="valid_until" value="{{ now()->addDays(30)->format('Y-m-d') }}" class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                            <select name="currency" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                                @foreach($currencies as $c)<option value="{{ $c->code }}">{{ $c->code }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                            <select name="language" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                                <option value="en">English</option><option value="bs">Bosnian</option><option value="hr">Croatian</option><option value="de">German</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-medium">Items</h3>
                        <button type="button" @click="addItem()" class="px-3 py-1.5 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100">+ Add Item</button>
                    </div>
                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="border rounded-lg p-4 grid grid-cols-12 gap-4">
                                <div class="col-span-5">
                                    <input type="text" x-model="item.name" :name="'items['+index+'][name]'" placeholder="Name" required class="w-full h-9 px-3 text-sm border rounded-md">
                                </div>
                                <div class="col-span-2">
                                    <input type="number" x-model="item.quantity" :name="'items['+index+'][quantity]'" min="1" @input="calcTotal(index)" class="w-full h-9 px-3 text-sm border rounded-md">
                                </div>
                                <div class="col-span-2">
                                    <input type="number" x-model="item.unit_price" :name="'items['+index+'][unit_price]'" step="0.01" @input="calcTotal(index)" class="w-full h-9 px-3 text-sm border rounded-md">
                                </div>
                                <div class="col-span-2 flex items-center text-sm font-medium" x-text="fmt(item.total)"></div>
                                <div class="col-span-1 flex items-center justify-end">
                                    <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-red-500 hover:text-red-700">×</button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="mt-4 pt-4 border-t text-right text-xl font-semibold" x-text="fmt(grandTotal())"></div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">Notes</h3>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 text-sm border rounded-md border-neutral-300"></textarea>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 h-fit sticky top-6">
                <button type="submit" class="w-full mb-3 px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md hover:bg-neutral-900">Create Quote</button>
                <a href="{{ route('app.quotes.index', $company) }}" class="w-full block text-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Cancel</a>
            </div>
        </div>
    </form>

    <script>
    function documentForm() {
        return {
            items: [{ name: '', quantity: 1, unit_price: 0, total: 0 }],
            addItem() { this.items.push({ name: '', quantity: 1, unit_price: 0, total: 0 }); },
            removeItem(i) { this.items.splice(i, 1); },
            calcTotal(i) { this.items[i].total = (parseFloat(this.items[i].quantity) || 0) * (parseFloat(this.items[i].unit_price) || 0); },
            grandTotal() { return this.items.reduce((s, it) => s + (it.total || 0), 0); },
            fmt(n) { return new Intl.NumberFormat('de-DE', { minimumFractionDigits: 2 }).format(n); }
        }
    }
    </script>
</x-app-layout>
