@props([
    'items' => 'items',
    'currency' => 'currency',
    'showTax' => true,
    'title' => 'Items',
])

<x-app.card>
    <x-app.section-header
        :title="$title"
        subtitle="Add products or services"
        icon="clipboard-document-list"
        variant="info"
    />

    {{-- Items Header --}}
    <div class="hidden lg:grid lg:grid-cols-12 gap-3 mb-2 px-1">
        <div class="col-span-5 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Article
        </div>
        <div class="col-span-2 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Qty</div>
        <div class="col-span-2 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Price</div>
        <div class="col-span-2 text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Total</div>
        <div class="col-span-1"></div>
    </div>

    {{-- Items --}}
    <div class="space-y-2">
        <template x-for="(item, index) in {{ $items }}" :key="index">
            <div class="p-3 rounded-lg bg-slate-50 dark:bg-slate-700/30 border border-slate-200 dark:border-slate-600/40 relative" x-data="{ articleSearchOpen: false }">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-center">
                    {{-- Article / Name --}}
                    <div class="lg:col-span-5 relative" @click.away="articleSearchOpen = false">
                        <label class="lg:hidden text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Article</label>
                        <div class="relative">
                            <input type="text"
                                x-model="item.name"
                                @focus="articleSearchOpen = true"
                                @input="item.article = null"
                                placeholder="Select article or type name..."
                                class="w-full h-9 px-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm">

                            {{-- Dropdown --}}
                            <div x-show="articleSearchOpen && filteredArticles(item.name).length > 0"
                                 class="absolute left-0 right-0 top-full mt-1 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-slate-200 dark:border-slate-700 max-h-48 overflow-y-auto z-50"
                                 x-cloak>
                                <template x-for="article in filteredArticles(item.name)" :key="article.id">
                                    <button type="button"
                                            @click="selectArticle(index, article)"
                                            class="w-full text-left px-3 py-2 text-xs hover:bg-violet-50 dark:hover:bg-violet-900/20 transition-colors flex justify-between items-center group">
                                        <span class="font-medium text-slate-700 dark:text-slate-200 group-hover:text-violet-700 dark:group-hover:text-violet-300" x-text="article.name"></span>
                                        <span class="text-slate-400 group-hover:text-violet-500" x-text="formatPrice(article.prices_meta[{{ $currency }}] || 0)"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <input type="hidden" :name="'items['+index+'][article_id]'" x-model="item.article">
                        <input type="hidden" :name="'items['+index+'][name]'" x-model="item.name">
                    </div>

                    {{-- Qty --}}
                    <div class="lg:col-span-2">
                        <label class="lg:hidden text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Qty</label>
                        <input type="number" :name="'items['+index+'][quantity]'" x-model="item.quantity" min="0.01" step="0.01" class="w-full h-9 px-2 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all text-center">
                    </div>

                    {{-- Price --}}
                    <div class="lg:col-span-2">
                        <label class="lg:hidden text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Price</label>
                        <div class="relative">
                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-xs" x-text="currencySymbol"></span>
                            <input type="number" step="0.01" :name="'items['+index+'][unit_price]'" x-model="item.price" class="w-full h-9 pl-7 pr-2 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all">
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="lg:col-span-2 flex items-center lg:justify-end h-9">
                        <label class="lg:hidden text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mr-auto">Total</label>
                        <span class="text-sm font-bold text-slate-900 dark:text-white" x-text="formatMoney(item.quantity * item.price)"></span>
                    </div>

                    {{-- Actions --}}
                    <div class="lg:col-span-1 flex justify-end">
                        <button type="button" @click="removeItem(index)" class="p-1.5 rounded-lg hover:bg-rose-100 dark:hover:bg-rose-900/30 text-slate-400 dark:text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 transition-colors" :disabled="items.length === 1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Add Item Button --}}
    <button type="button" @click="addItem()" class="w-full mt-3 h-10 rounded-xl cursor-pointer border-2 border-dashed border-slate-300 dark:border-slate-600/60 hover:border-violet-400 dark:hover:border-violet-500 hover:bg-violet-50/50 dark:hover:bg-violet-900/20 text-slate-500 dark:text-slate-400 hover:text-violet-600 dark:hover:text-violet-400 text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Item
    </button>
</x-app.card>
