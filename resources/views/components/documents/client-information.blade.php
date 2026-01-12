@props([
    'id' => 'client-selector',
])

<x-app.card>
    <x-app.section-header 
        title="Client Information" 
        subtitle="Select or add a client" 
        icon="user" 
        variant="primary" 
    />

    <div class="relative" @click.away="open = false">
        <div class="relative" x-show="!selectedClient">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" 
                x-model="search" 
                @focus="open = true" 
                @click="open = true" 
                placeholder="Search or select a client..." 
                class="w-full h-14 pl-11 pr-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 text-sm font-medium text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm">
        </div>

        <div x-show="open && !selectedClient" x-transition class="absolute left-0 right-0 mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 py-2 z-[100] max-h-72 overflow-y-auto" x-cloak>
            <a href="{{ route('app.clients.create', $company ?? request()->route('company')) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-violet-50 dark:hover:bg-violet-900/20 border-b border-slate-100 dark:border-slate-700 text-violet-600 dark:text-violet-400 group transition-colors">
                <div class="w-8 h-8 rounded-lg border-2 border-dashed border-violet-300 dark:border-violet-600 group-hover:border-violet-400 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <span class="font-bold text-sm">Create New Client</span>
            </a>
            <template x-for="client in filteredClients" :key="client.id">
                <button type="button" @click="selectClient(client); open = false; search = ''" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-left border-b border-slate-50 dark:border-slate-700/50 last:border-0 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300 flex-shrink-0" x-text="client.name.substring(0, 2).toUpperCase()"></div>
                    <div class="min-w-0">
                        <p class="font-bold text-slate-800 dark:text-white text-sm truncate" x-text="client.name"></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate" x-text="client.email"></p>
                    </div>
                </button>
            </template>
        </div>

        {{-- Selected Client Card --}}
        <div x-show="selectedClient" class="h-14 px-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between" x-cloak>
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center text-xs font-bold text-violet-600 dark:text-violet-400 flex-shrink-0" x-text="selectedClient?.name.substring(0, 2).toUpperCase()"></div>
                <div class="min-w-0">
                    <input type="hidden" name="client_id" :value="selectedClient?.id">
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate" x-text="selectedClient?.name"></h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate" x-text="selectedClient?.email"></p>
                </div>
            </div>
            <button type="button" @click="selectedClient = null; open = true" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-50 hover:bg-slate-100 dark:bg-slate-700 dark:hover:bg-slate-600 text-xs font-bold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 transition-all ml-3 flex-shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Change
            </button>
        </div>
    </div>
</x-app.card>
