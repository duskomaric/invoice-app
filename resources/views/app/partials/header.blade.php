{{-- Header --}}
<header class="sticky top-0 z-30 h-14 flex items-center justify-between px-4 lg:px-6 no-print">
    <div class="flex items-center gap-3">
        <button @click="mobileMenuOpen = true" class="p-2 rounded-xl hover:bg-white/60 dark:hover:bg-slate-700/60 text-slate-500 dark:text-slate-400 lg:hidden transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <button @click="sidebarCollapsed = false" x-show="sidebarCollapsed" class="hidden lg:flex p-2 rounded-xl hover:bg-white/60 dark:hover:bg-slate-700/60 text-slate-500 dark:text-slate-400 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
        </button>
        <div>
            <h1 class="text-sm font-bold text-slate-800 dark:text-white">@yield('page-title', 'Invoices')</h1>
            <p class="text-[11px] text-slate-500 dark:text-slate-400">@yield('page-subtitle', 'Manage your invoices')</p>
        </div>
        @hasSection('page-badge')
            <x-app.badge variant="primary" size="xs">@yield('page-badge')</x-app.badge>
        @endif
    </div>
    
    <div class="flex items-center gap-2">
        {{-- Search --}}
        <div class="hidden md:block relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Search..." class="w-48 h-9 pl-9 pr-3 rounded-lg bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-xs text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm" />
        </div>
        
        {{-- Theme Toggle --}}
        <x-app.theme-toggle />
        
        {{-- Notifications --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="relative p-2 rounded-xl hover:bg-white/60 dark:hover:bg-slate-700/60 text-slate-500 dark:text-slate-400 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span class="absolute top-1 right-1 w-4 h-4 bg-gradient-to-r from-rose-500 to-pink-500 rounded-full text-[10px] text-white flex items-center justify-center font-bold shadow-lg shadow-rose-500/30">3</span>
            </button>
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 -translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden z-50" x-cloak>
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-white">Notifications</h4>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-violet-100 dark:bg-violet-900/50 text-violet-600 dark:text-violet-300">3 new</span>
                </div>
                <div class="max-h-72 overflow-y-auto">
                    <a href="#" class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 border-l-2 border-violet-500">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-slate-800 dark:text-white">Payment received</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">INV-2024-0042 - €1,250.00</p>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">2 min ago</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 border-l-2 border-amber-500">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-slate-800 dark:text-white">Invoice overdue</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">INV-2024-0038 is 3 days overdue</p>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">1 hour ago</p>
                        </div>
                    </a>
                    <a href="#" class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 border-l-2 border-transparent">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-sky-500 to-cyan-500 flex items-center justify-center text-white flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-slate-800 dark:text-white">Quote viewed</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">TechStart Inc viewed your quote</p>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">3 hours ago</p>
                        </div>
                    </a>
                </div>
                <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                    <a href="{{ route('app.settings.notifications', $company) }}" class="text-xs font-medium text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300">View all notifications →</a>
                </div>
            </div>
        </div>
        
        {{-- Header Actions --}}
        @hasSection('header-actions')
            @yield('header-actions')
        @else
            <x-app.button href="{{ route('app.invoices.create', $company) }}" variant="primary" size="sm" class="hidden sm:flex">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Invoice
            </x-app.button>
        @endif
    </div>
</header>
