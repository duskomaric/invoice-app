@props([
    'currency' => 'currency',
])

<div class="rounded-2xl bg-gradient-to-br from-violet-600 via-purple-600 to-fuchsia-600 p-5 text-white shadow-xl shadow-violet-500/30">
    <h3 class="text-sm font-bold mb-4 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m-8 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        Summary
    </h3>
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <span class="text-violet-200 text-sm">Subtotal</span>
            <span class="font-semibold" x-text="currencySymbol + subtotal.toFixed(2)"></span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-violet-200 text-sm">Tax (17%)</span>
            <span class="font-semibold" x-text="currencySymbol + tax.toFixed(2)"></span>
        </div>
        <div class="border-t border-white/20 pt-3 mt-3">
            <div class="flex items-center justify-between">
                <span class="text-base font-bold">Total</span>
                <span class="text-2xl font-bold" x-text="currencySymbol + total.toFixed(2)"></span>
            </div>
        </div>
    </div>
</div>
