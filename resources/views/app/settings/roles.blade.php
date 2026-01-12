<x-app.settings-layout :company="$company" active="roles">
    <x-app.card>
        <div class="flex items-center justify-between mb-8">
            <x-app.section-header title="Roles & Permissions" subtitle="Control who can access what in your company" icon="shield-check" variant="primary" />
            <x-app.button type="button" variant="primary" size="sm">
                <x-heroicon-o-plus class="w-4 h-4 mr-1" />
                Add New Role
            </x-app.button>
        </div>
        
        <div class="space-y-4">
            {{-- Role Item --}}
            <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 transition-all hover:shadow-md group">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-violet-500">
                            <x-heroicon-o-user-group class="w-6 h-6" />
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-wider">Administrator</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Full access to all modules and configurations</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <x-app.button variant="secondary" size="xs">Edit</x-app.button>
                        <x-app.button variant="danger" size="xs" disabled>System Role</x-app.button>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-violet-100 dark:bg-violet-900/30 text-[10px] font-bold text-violet-700 dark:text-violet-300">all-access</span>
                    <span class="px-2.5 py-1 rounded-lg bg-violet-100 dark:bg-violet-900/30 text-[10px] font-bold text-violet-700 dark:text-violet-300">manage-settings</span>
                    <span class="px-2.5 py-1 rounded-lg bg-violet-100 dark:bg-violet-900/30 text-[10px] font-bold text-violet-700 dark:text-violet-300">manage-users</span>
                    <span class="px-2.5 py-1 rounded-lg bg-violet-100 dark:bg-violet-900/30 text-[10px] font-bold text-violet-700 dark:text-violet-300">+24 more</span>
                </div>
            </div>

            {{-- Role Item --}}
            <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 transition-all hover:shadow-md group">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-blue-500">
                            <x-heroicon-o-document-text class="w-6 h-6" />
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-wider">Accountant</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Access to invoices, payments and reports</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <x-app.button variant="secondary" size="xs">Edit</x-app.button>
                        <x-app.button variant="danger" size="xs">Delete</x-app.button>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-[10px] font-bold text-blue-700 dark:text-blue-300">view-reports</span>
                    <span class="px-2.5 py-1 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-[10px] font-bold text-blue-700 dark:text-blue-300">manage-invoices</span>
                    <span class="px-2.5 py-1 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-[10px] font-bold text-blue-700 dark:text-blue-300">manage-payments</span>
                </div>
            </div>

            {{-- Role Item --}}
            <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 transition-all hover:shadow-md group">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-emerald-500">
                            <x-heroicon-o-shopping-cart class="w-6 h-6" />
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-wider">Sales Representative</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Create quotes and proformas for clients</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <x-app.button variant="secondary" size="xs">Edit</x-app.button>
                        <x-app.button variant="danger" size="xs">Delete</x-app.button>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-[10px] font-bold text-emerald-700 dark:text-emerald-300">create-quotes</span>
                    <span class="px-2.5 py-1 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-[10px] font-bold text-emerald-700 dark:text-emerald-300">create-proformas</span>
                    <span class="px-2.5 py-1 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-[10px] font-bold text-emerald-700 dark:text-emerald-300">manage-clients</span>
                </div>
            </div>
        </div>
    </x-app.card>
</x-app.settings-layout>
