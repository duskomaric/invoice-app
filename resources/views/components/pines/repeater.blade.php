@props([
    'name' => 'items',
    'items' => [],
    'addLabel' => 'Add Item',
    'emptyMessage' => 'No items added yet.',
])

<div 
    x-data="{
        items: {{ json_encode($items ?: [['name' => '', 'quantity' => 1, 'unit_price' => '', 'total' => '']]) }},
        addItem() {
            this.items.push({ name: '', quantity: 1, unit_price: '', total: '' });
        },
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },
        updateTotal(index) {
            const item = this.items[index];
            const qty = parseFloat(item.quantity) || 0;
            const price = parseFloat(item.unit_price) || 0;
            item.total = (qty * price).toFixed(2);
        },
        get grandTotal() {
            return this.items.reduce((sum, item) => sum + (parseFloat(item.total) || 0), 0).toFixed(2);
        }
    }"
    {{ $attributes }}
>
    <div class="space-y-3">
        <template x-for="(item, index) in items" :key="index">
            <div class="flex items-start gap-3 p-4 bg-neutral-50 rounded-lg border border-neutral-200/60">
                <div class="flex-1 grid grid-cols-12 gap-3">
                    <div class="col-span-12 sm:col-span-5">
                        <label class="block text-xs font-medium text-neutral-500 mb-1">Item Name</label>
                        <input 
                            type="text" 
                            x-model="item.name" 
                            :name="`{{ $name }}[${index}][name]`"
                            class="w-full h-10 px-3 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400"
                            required
                        >
                    </div>
                    <div class="col-span-4 sm:col-span-2">
                        <label class="block text-xs font-medium text-neutral-500 mb-1">Qty</label>
                        <input 
                            type="number" 
                            x-model="item.quantity" 
                            @input="updateTotal(index)"
                            :name="`{{ $name }}[${index}][quantity]`"
                            min="1"
                            step="1"
                            class="w-full h-10 px-3 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400"
                            required
                        >
                    </div>
                    <div class="col-span-4 sm:col-span-2">
                        <label class="block text-xs font-medium text-neutral-500 mb-1">Unit Price</label>
                        <input 
                            type="number" 
                            x-model="item.unit_price" 
                            @input="updateTotal(index)"
                            :name="`{{ $name }}[${index}][unit_price]`"
                            min="0"
                            step="0.01"
                            class="w-full h-10 px-3 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400"
                            required
                        >
                    </div>
                    <div class="col-span-4 sm:col-span-2">
                        <label class="block text-xs font-medium text-neutral-500 mb-1">Total</label>
                        <input 
                            type="text" 
                            x-model="item.total" 
                            :name="`{{ $name }}[${index}][total]`"
                            class="w-full h-10 px-3 text-sm bg-neutral-100 border rounded-md border-neutral-300 text-neutral-600"
                            readonly
                        >
                    </div>
                    <div class="col-span-12 sm:col-span-1 flex items-end justify-end">
                        <button 
                            type="button" 
                            @click="removeItem(index)"
                            x-show="items.length > 1"
                            class="h-10 w-10 flex items-center justify-center text-red-500 hover:text-red-700 hover:bg-red-50 rounded-md transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
    
    <div class="flex items-center justify-between mt-4">
        <button 
            type="button" 
            @click="addItem()"
            class="inline-flex items-center px-3 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ $addLabel }}
        </button>
        
        <div class="text-right">
            <span class="text-sm text-neutral-500">Grand Total:</span>
            <span class="ml-2 text-lg font-semibold text-neutral-900" x-text="grandTotal"></span>
        </div>
    </div>
</div>
