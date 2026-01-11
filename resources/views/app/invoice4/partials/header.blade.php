{{-- Header --}}
<header class="sticky top-0 z-40 h-14 card-glass border-b border-slate-200/60 flex items-center justify-between px-4 lg:px-6 no-print">
    <div class="flex items-center gap-3">
        <button @click="mobileMenuOpen = true" class="p-1.5 rounded-md hover:bg-slate-100 text-slate-500 lg:hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h1 class="text-base font-semibold text-slate-800">@yield('page-title', 'Invoices')</h1>
        <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-violet-100 text-violet-700">@yield('page-badge', '')</span>
    </div>
    <div class="flex items-center gap-2">
        {{-- Notifications --}}
        <div class="relative">
            <button @click="notificationsOpen = !notificationsOpen" class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 relative transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span class="absolute top-1 right-1 w-4 h-4 bg-rose-500 rounded-full text-[10px] text-white flex items-center justify-center font-medium">3</span>
            </button>
            <div x-show="notificationsOpen" @click.away="notificationsOpen = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden z-50 dropdown-enter" x-cloak>
                <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800 text-sm">Notifications</h3>
                    <button class="text-xs text-violet-600 hover:text-violet-700">Mark all read</button>
                </div>
                <div class="max-h-64 overflow-y-auto">
                    <div class="px-4 py-3 hover:bg-slate-50 border-b border-slate-50 flex items-start gap-3 bg-violet-50/50">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-slate-700">Invoice paid</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">2 min ago</p>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-violet-500 flex-shrink-0 mt-1"></span>
                    </div>
                    <div class="px-4 py-3 hover:bg-slate-50 border-b border-slate-50 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-slate-700">Invoice due tomorrow</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">1 hour ago</p>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-2 border-t border-slate-100">
                    <a href="#" class="text-xs text-violet-600 hover:text-violet-700 font-medium">View all notifications</a>
                </div>
            </div>
        </div>
        
        @hasSection('header-actions')
            @yield('header-actions')
        @else
            <a href="/templates/invoice4/create" class="hidden sm:flex items-center gap-1.5 px-4 py-2 rounded-lg gradient-bg text-white text-xs font-semibold shadow-md shadow-violet-500/25 hover:shadow-lg hover:scale-[1.02] transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Invoice
            </a>
        @endif
    </div>
</header>
