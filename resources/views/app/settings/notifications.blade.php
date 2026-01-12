<x-app.settings-layout :company="$company" active="notifications">
    <x-app.card>
        <x-app.section-header title="Notification Preferences" subtitle="Stay updated with important document events" icon="bell" variant="primary" />
        
        <form action="#" method="POST" class="mt-8 space-y-8">
            <div class="space-y-6">
                {{-- Document Events --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest px-1">Document Events</h3>
                    
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                        <div>
                            <p class="text-[11px] font-bold text-slate-700 dark:text-slate-200">Invoice Viewed</p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400">Receive an email when a client views an invoice</p>
                        </div>
                        <x-app.toggle name="notify_invoice_viewed" checked />
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                        <div>
                            <p class="text-[11px] font-bold text-slate-700 dark:text-slate-200">Payment Received</p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400">Notification when a payment is logged or confirmed</p>
                        </div>
                        <x-app.toggle name="notify_payment_received" checked />
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                        <div>
                            <p class="text-[11px] font-bold text-slate-700 dark:text-slate-200">Quote Accepted</p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400">Get notified when a client accepts your quote</p>
                        </div>
                        <x-app.toggle name="notify_quote_accepted" checked />
                    </div>
                </div>

                {{-- System Alerts --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest px-1">System Alerts</h3>
                    
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                        <div>
                            <p class="text-[11px] font-bold text-slate-700 dark:text-slate-200">Low Balance Warnings</p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400">Receive alerts when credits or balances are low</p>
                        </div>
                        <x-app.toggle name="notify_low_balance" />
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                        <div>
                            <p class="text-[11px] font-bold text-slate-700 dark:text-slate-200">Daily Digest</p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400">A summary of your business activity sent every morning</p>
                        </div>
                        <x-app.toggle name="daily_digest" />
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <x-app.button type="submit" variant="primary" size="md">Save Preferences</x-app.button>
            </div>
        </form>
    </x-app.card>
</x-app.settings-layout>
