<x-app-layout>
    <x-slot name="title">Edit Proforma {{ $proforma->formatted_number }}</x-slot>

    <div class="mb-6"><h1 class="text-2xl font-semibold text-gray-900">Edit Proforma {{ $proforma->formatted_number }}</h1></div>

    <form action="{{ route('app.proformas.update', [$company, $proforma]) }}" method="POST" 
          x-data="documentForm({{ json_encode($proforma->items->map(fn($i) => ['name' => $i->name, 'quantity' => $i->quantity, 'unit_price' => $i->unit_price / 100, 'total' => $i->total / 100])) }})">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Client</label>
                            <select name="client_id" required class="w-full h-10 px-3 text-sm border rounded-md">
                                @foreach($clients as $c)<option value="{{ $c->id }}" {{ $proforma->client_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Status</label>
                            <select name="status" required class="w-full h-10 px-3 text-sm border rounded-md">
                                <option value="draft" {{ $proforma->status->value === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="sent" {{ $proforma->status->value === 'sent' ? 'selected' : '' }}>Sent</option>
                            </select>
                        </div>
                        <div><label class="block text-sm font-medium mb-1">Date</label><input type="date" name="date" value="{{ $proforma->date?->format('Y-m-d') }}" required class="w-full h-10 px-3 text-sm border rounded-md"></div>
                        <div><label class="block text-sm font-medium mb-1">Due Date</label><input type="date" name="due_date" value="{{ $proforma->due_date?->format('Y-m-d') }}" class="w-full h-10 px-3 text-sm border rounded-md"></div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Currency</label>
                            <select name="currency" required class="w-full h-10 px-3 text-sm border rounded-md">
                                @foreach($currencies as $c)<option value="{{ $c->code }}" {{ $proforma->currency == $c->code ? 'selected' : '' }}>{{ $c->code }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Language</label>
                            <select name="language" required class="w-full h-10 px-3 text-sm border rounded-md">
                                <option value="en" {{ $proforma->language?->value === 'en' ? 'selected' : '' }}>English</option>
                                <option value="bs" {{ $proforma->language?->value === 'bs' ? 'selected' : '' }}>Bosnian</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex justify-between mb-4"><h3 class="text-lg font-medium">Items</h3><button type="button" @click="addItem()" class="text-sm text-indigo-600">+ Add</button></div>
                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="border rounded-lg p-4 grid grid-cols-12 gap-4">
                                <div class="col-span-5"><input type="text" x-model="item.name" :name="'items['+index+'][name]'" required class="w-full h-9 px-3 text-sm border rounded-md"></div>
                                <div class="col-span-2"><input type="number" x-model="item.quantity" :name="'items['+index+'][quantity]'" @input="calcTotal(index)" class="w-full h-9 px-3 text-sm border rounded-md"></div>
                                <div class="col-span-2"><input type="number" x-model="item.unit_price" :name="'items['+index+'][unit_price]'" step="0.01" @input="calcTotal(index)" class="w-full h-9 px-3 text-sm border rounded-md"></div>
                                <div class="col-span-2 flex items-center text-sm" x-text="fmt(item.total)"></div>
                                <div class="col-span-1"><button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-red-500">×</button></div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6"><textarea name="notes" rows="3" class="w-full px-3 py-2 text-sm border rounded-md">{{ $proforma->notes }}</textarea></div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 h-fit sticky top-6">
                <button type="submit" class="w-full mb-3 px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md">Update</button>
                <a href="{{ route('app.proformas.show', [$company, $proforma]) }}" class="w-full block text-center px-4 py-2 text-sm text-gray-700 border rounded-md">Cancel</a>
            </div>
        </div>
    </form>

    <script>
    function documentForm(existing = []) {
        return {
            items: existing.length ? existing : [{ name: '', quantity: 1, unit_price: 0, total: 0 }],
            addItem() { this.items.push({ name: '', quantity: 1, unit_price: 0, total: 0 }); },
            removeItem(i) { this.items.splice(i, 1); },
            calcTotal(i) { this.items[i].total = (parseFloat(this.items[i].quantity) || 0) * (parseFloat(this.items[i].unit_price) || 0); },
            fmt(n) { return new Intl.NumberFormat('de-DE', { minimumFractionDigits: 2 }).format(n); }
        }
    }
    </script>
</x-app-layout>
