{{-- Mobile Bottom Navigation --}}
<nav class="fixed bottom-0 left-0 right-0 z-30 lg:hidden no-print">
    <div class="mx-3 mb-3">
        <div class="nav-box rounded-2xl backdrop-blur-xl px-2 py-2 flex items-center justify-around">
            <a href="/templates/invoice5" class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl text-violet-600 dark:text-violet-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-[10px] font-medium">Invoices</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-[10px] font-medium">Clients</span>
            </a>
            
            {{-- FAB --}}
            <a href="/templates/invoice5/create" class="relative -mt-8">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-500 via-purple-500 to-fuchsia-500 flex items-center justify-center shadow-xl shadow-violet-500/40 hover:shadow-2xl hover:scale-105 transition-all">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
            </a>
            
            <a href="#" class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span class="text-[10px] font-medium">Reports</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35"/></svg>
                <span class="text-[10px] font-medium">Settings</span>
            </a>
        </div>
    </div>
</nav>
