<x-app-layout>
    <x-slot name="title">Create Invoice - {{ $company->name }}</x-slot>

    <x-pines.page-header 
        title="Create Invoice" 
        :breadcrumbs="[
            ['label' => 'Invoices', 'url' => route('app.invoices.index', $company)],
            ['label' => 'Create']
        ]"
    />

    <form action="{{ route('app.invoices.store', $company) }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-9 space-y-6">
                <x-pines.form-section title="Invoice" :description="'Next number: ' . $previewNumber">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="sm:col-span-2">
                            <x-pines.select 
                                label="Client" 
                                name="client_id" 
                                :options="$clients->pluck('name', 'id')->toArray()" 
                                placeholder="Select client..."
                                :searchable="true"
                                required 
                            />
                        </div>
                        <div>
                            <x-pines.input type="date" label="Date" name="date" :value="now()->format('Y-m-d')" required />
                        </div>
                        <div>
                            <x-pines.input type="date" label="Due Date" name="due_date" :value="now()->addDays($defaults['due_days'])->format('Y-m-d')" />
                        </div>
                    </div>
                </x-pines.form-section>

                <x-pines.form-section title="Items">
                    <div x-data="{
                        items: [{ article_id: '', name: '', description: '', quantity: 1, unit_price: '', total: '' }],
                        articles: {{ Js::from($articles->map(fn($a) => ['id' => $a->id, 'name' => $a->name, 'description' => $a->description, 'prices' => $a->prices_meta])) }},
                        currency: '{{ $defaults['currency'] }}',
                        addItem() {
                            this.items.push({ article_id: '', name: '', description: '', quantity: 1, unit_price: '', total: '' });
                        },
                        removeItem(index) {
                            if (this.items.length > 1) this.items.splice(index, 1);
                        },
                        selectArticle(index, articleId) {
                            const article = this.articles.find(a => a.id == articleId);
                            if (article) {
                                this.items[index].name = article.name;
                                this.items[index].description = article.description || '';
                                const price = article.prices?.[this.currency]?.price || article.prices?.[this.currency] || Object.values(article.prices || {})[0]?.price || Object.values(article.prices || {})[0] || 0;
                                this.items[index].unit_price = parseFloat(price) || 0;
                                this.updateTotal(index);
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
                    }" @currency-changed.window="currency = $event.detail; items.forEach((_, i) => updateTotal(i))">
                        <div class="space-y-3">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-200/60">
                                    <div class="grid grid-cols-12 gap-3">
                                        <div class="col-span-12 sm:col-span-4">
                                            <label class="block text-xs font-medium text-neutral-500 mb-1">Article</label>
                                            <select x-model="item.article_id" @change="selectArticle(index, item.article_id)" class="w-full h-10 px-3 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400">
                                                <option value="">Select or type custom...</option>
                                                @foreach($articles as $article)
                                                    <option value="{{ $article->id }}">{{ $article->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-12 sm:col-span-4">
                                            <label class="block text-xs font-medium text-neutral-500 mb-1">Item Name</label>
                                            <input type="text" x-model="item.name" :name="`items[${index}][name]`" required class="w-full h-10 px-3 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400">
                                            <input type="hidden" x-model="item.description" :name="`items[${index}][description]`">
                                        </div>
                                        <div class="col-span-4 sm:col-span-1">
                                            <label class="block text-xs font-medium text-neutral-500 mb-1">Qty</label>
                                            <input type="number" x-model="item.quantity" @input="updateTotal(index)" :name="`items[${index}][quantity]`" min="1" step="1" required class="w-full h-10 px-3 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400">
                                        </div>
                                        <div class="col-span-4 sm:col-span-2">
                                            <label class="block text-xs font-medium text-neutral-500 mb-1">Unit Price</label>
                                            <input type="number" x-model="item.unit_price" @input="updateTotal(index)" :name="`items[${index}][unit_price]`" min="0" step="0.01" required class="w-full h-10 px-3 text-sm bg-white border rounded-md border-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400">
                                        </div>
                                        <div class="col-span-3 sm:col-span-1">
                                            <label class="block text-xs font-medium text-neutral-500 mb-1">Total</label>
                                            <input type="text" x-model="item.total" readonly class="w-full h-10 px-3 text-sm bg-neutral-100 border rounded-md border-neutral-300 text-neutral-600">
                                        </div>
                                        <div class="col-span-1 flex items-end justify-end">
                                            <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="h-10 w-10 flex items-center justify-center text-red-500 hover:text-red-700 hover:bg-red-50 rounded-md transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-neutral-200">
                            <button type="button" @click="addItem()" class="inline-flex items-center px-3 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Add Item
                            </button>
                            <div class="text-right">
                                <span class="text-sm text-neutral-500">Grand Total:</span>
                                <span class="ml-2 text-xl font-bold text-neutral-900" x-text="grandTotal"></span>
                                <span class="text-sm text-neutral-500" x-text="currency"></span>
                            </div>
                        </div>
                    </div>
                </x-pines.form-section>

                <x-pines.form-section title="Notes">
                    <x-pines.textarea name="notes" placeholder="Additional notes for this invoice..." rows="3" />
                </x-pines.form-section>
            </div>

            <div class="lg:col-span-3 space-y-6">
                <x-pines.card title="Settings">
                    <div class="space-y-4">
                        <x-pines.select 
                            label="Currency" 
                            name="currency" 
                            :options="$currencies->pluck('code', 'code')->toArray()" 
                            :value="$defaults['currency']"
                            required
                            x-on:change="$dispatch('currency-changed', $event.target.value)"
                        />
                        
                        <x-pines.select 
                            label="Language" 
                            name="language" 
                            :options="[
                                'en' => 'English',
                                'sr-Latn' => 'Serbian (Latin)',
                                'sr-Cyrl' => 'Serbian (Cyrillic)',
                                'de' => 'German',
                                'fr' => 'French',
                            ]"
                            :value="$defaults['language']"
                            required 
                        />
                        
                        <x-pines.select 
                            label="Template" 
                            name="invoice_template" 
                            :options="[
                                'classic' => 'Classic',
                                'modern' => 'Modern',
                                'minimal' => 'Minimal',
                            ]"
                            :value="$defaults['template']"
                            required 
                        />
                        
                        <x-pines.select 
                            label="Bank Account" 
                            name="bank_account_ids[]" 
                            :options="$bankAccounts->pluck('bank_name', 'id')->toArray()" 
                            :value="$defaults['bank_account_id']"
                            placeholder="Select bank account..."
                        />
                    </div>
                </x-pines.card>

                <x-pines.card title="Actions" :padding="true">
                    <div class="space-y-3">
                        <x-pines.button type="submit" class="w-full justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Create Invoice
                        </x-pines.button>
                        <x-pines.button variant="secondary" href="{{ route('app.invoices.index', $company) }}" class="w-full justify-center">
                            Cancel
                        </x-pines.button>
                    </div>
                </x-pines.card>
            </div>
        </div>
    </form>
</x-app-layout>
