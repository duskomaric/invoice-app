<x-app-layout>
    <x-slot name="title">Create Contract - {{ $company->name }}</x-slot>

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Create Contract</h1>
        <p class="text-sm text-gray-500">Next number: {{ $previewNumber }}</p>
    </div>

    <form action="{{ route('app.contracts.store', $company) }}" method="POST" x-data="documentForm()">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                            <select name="client_id" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                                <option value="">Select</option>
                                @foreach($clients as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" required class="w-full h-10 px-3 text-sm border rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                            <select name="currency" required class="w-full h-10 px-3 text-sm border rounded-md">
                                @foreach($currencies as $c)<option value="{{ $c->code }}">{{ $c->code }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                            <select name="language" required class="w-full h-10 px-3 text-sm border rounded-md">
                                <option value="en">English</option><option value="bs">Bosnian</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-medium">Items</h3>
                        <button type="button" @click="addItem()" class="text-sm text-indigo-600">+ Add</button>
                    </div>
                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="border rounded-lg p-4 grid grid-cols-12 gap-4">
                                <div class="col-span-5"><input type="text" x-model="item.name" :name="'items['+index+'][name]'" placeholder="Name" required class="w-full h-9 px-3 text-sm border rounded-md"></div>
                                <div class="col-span-2"><input type="number" x-model="item.quantity" :name="'items['+index+'][quantity]'" min="1" @input="calcTotal(index)" class="w-full h-9 px-3 text-sm border rounded-md"></div>
                                <div class="col-span-2"><input type="number" x-model="item.unit_price" :name="'items['+index+'][unit_price]'" step="0.01" @input="calcTotal(index)" class="w-full h-9 px-3 text-sm border rounded-md"></div>
                                <div class="col-span-2 flex items-center text-sm" x-text="fmt(item.total)"></div>
                                <div class="col-span-1"><button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-red-500">×</button></div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <textarea name="notes" rows="3" placeholder="Notes" class="w-full px-3 py-2 text-sm border rounded-md"></textarea>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 h-fit sticky top-6">
                <button type="submit" class="w-full mb-3 px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md">Create Contract</button>
                <a href="{{ route('app.contracts.index', $company) }}" class="w-full block text-center px-4 py-2 text-sm text-gray-700 border rounded-md">Cancel</a>
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
            fmt(n) { return new Intl.NumberFormat('de-DE', { minimumFractionDigits: 2 }).format(n); }
        }
    }
    </script>
</x-app-layout>
