{{-- Main Content --}}
<main class="min-h-screen content-transition pb-20 lg:pb-0" :class="sidebarCollapsed ? 'lg:ml-[72px]' : 'lg:ml-[260px]'">
    {{-- Header --}}
    <header class="sticky top-0 z-40 h-14 card-glass border-b border-slate-200/60 flex items-center justify-between px-4 lg:px-6">
        <div class="flex items-center gap-3">
            <button x-on:click="mobileMenuOpen = true" class="p-1.5 rounded-md hover:bg-slate-100 text-slate-500 lg:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="text-base font-semibold text-slate-800">Invoices</h1>
            <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-violet-100 text-violet-700">24 total</span>
        </div>
        <div class="flex items-center gap-2">
            {{-- Notifications --}}
            <div class="relative">
                <button x-on:click="notificationsOpen = !notificationsOpen" class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 relative transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span x-show="unreadCount() > 0" class="absolute top-1 right-1 w-4 h-4 bg-rose-500 rounded-full text-[10px] text-white flex items-center justify-center font-medium" x-text="unreadCount()"></span>
                </button>
                <div x-show="notificationsOpen" x-on:click.away="notificationsOpen = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden z-50 dropdown-enter" x-cloak>
                    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="font-semibold text-slate-800 text-sm">Notifications</h3>
                        <button x-show="unreadCount() > 0" x-on:click="markAllRead()" class="text-xs text-violet-600 hover:text-violet-700">Mark all read</button>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        <template x-for="notif in notifications" :key="notif.id">
                            <div class="px-4 py-3 hover:bg-slate-50 border-b border-slate-50 flex items-start gap-3" :class="!notif.read && 'bg-violet-50/50'">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" :class="notif.read ? 'bg-slate-100' : 'bg-violet-100'">
                                    <svg class="w-4 h-4" :class="notif.read ? 'text-slate-400' : 'text-violet-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-slate-700" :class="!notif.read && 'font-medium'" x-text="notif.title"></p>
                                    <p class="text-[10px] text-slate-400 mt-0.5" x-text="notif.time"></p>
                                </div>
                                <span x-show="!notif.read" class="w-2 h-2 rounded-full bg-violet-500 flex-shrink-0 mt-1"></span>
                            </div>
                        </template>
                        <div class="p-4 text-center text-xs text-slate-500" x-show="notifications.length === 0">No new notifications</div>
                    </div>
                    <div class="px-4 py-2 border-t border-slate-100">
                        <a href="#" class="text-xs text-violet-600 hover:text-violet-700 font-medium">View all notifications</a>
                    </div>
                </div>
            </div>
            
            <a href="/templates/invoice3/create" class="hidden sm:flex items-center gap-1.5 px-4 py-2 rounded-lg gradient-bg text-white text-xs font-semibold shadow-md shadow-violet-500/25 hover:shadow-lg hover:scale-[1.02] transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Invoice
            </a>
        </div>
    </header>

    <div class="p-4 lg:p-6">
        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
            <div class="card-glass rounded-xl p-4 border border-white/60 hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-violet-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800">24</p>
                        <p class="text-[11px] text-slate-500">Total</p>
                    </div>
                </div>
            </div>
            <div class="card-glass rounded-xl p-4 border border-white/60 hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800">12.4k</p>
                        <p class="text-[11px] text-slate-500">Paid</p>
                    </div>
                </div>
            </div>
            <div class="card-glass rounded-xl p-4 border border-white/60 hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800">4.9k</p>
                        <p class="text-[11px] text-slate-500">Pending</p>
                    </div>
                </div>
            </div>
            <div class="card-glass rounded-xl p-4 border border-white/60 hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-rose-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800">5.2k</p>
                        <p class="text-[11px] text-slate-500">Overdue</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters Bar --}}
        <div class="card-glass rounded-xl border border-white/60 p-3 mb-4">
            <div class="flex flex-wrap items-center gap-2">
                {{-- Search --}}
                <div class="relative flex-1 min-w-[200px]">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Search invoices..." class="w-full h-9 pl-9 pr-3 rounded-lg bg-white/60 border border-slate-200/60 text-slate-700 placeholder-slate-400 text-xs focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 transition-all">
                </div>

                {{-- Status Select Filter --}}
                <div class="relative" x-data="{ open: false }">
                    <button x-on:click="open = !open" class="h-9 px-3 rounded-lg bg-white/60 border border-slate-200/60 text-xs text-slate-600 flex items-center gap-2 hover:border-violet-300 transition-colors" :class="filters.status && 'border-violet-300 bg-violet-50'">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span x-text="filters.status ? filters.status.charAt(0).toUpperCase() + filters.status.slice(1) : 'Status'"></span>
                        <span x-show="filters.status" class="w-5 h-5 rounded-full bg-violet-500 text-white text-[10px] flex items-center justify-center">1</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-on:click.away="open = false" x-transition class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-xl border border-slate-100 py-1 z-30 dropdown-enter" x-cloak>
                        <button x-on:click="filters.status = ''; open = false" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 flex items-center gap-2" :class="!filters.status && 'text-violet-600 bg-violet-50'">
                            <span class="w-4 h-4 rounded border flex items-center justify-center" :class="!filters.status ? 'border-violet-500 bg-violet-500' : 'border-slate-300'">
                                <svg x-show="!filters.status" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            All Statuses
                        </button>
                        <button x-on:click="filters.status = 'paid'; open = false" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 flex items-center gap-2" :class="filters.status === 'paid' && 'text-violet-600 bg-violet-50'">
                            <span class="w-4 h-4 rounded border flex items-center justify-center" :class="filters.status === 'paid' ? 'border-violet-500 bg-violet-500' : 'border-slate-300'">
                                <svg x-show="filters.status === 'paid'" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Paid
                        </button>
                        <button x-on:click="filters.status = 'pending'; open = false" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 flex items-center gap-2" :class="filters.status === 'pending' && 'text-violet-600 bg-violet-50'">
                            <span class="w-4 h-4 rounded border flex items-center justify-center" :class="filters.status === 'pending' ? 'border-violet-500 bg-violet-500' : 'border-slate-300'">
                                <svg x-show="filters.status === 'pending'" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Pending
                        </button>
                        <button x-on:click="filters.status = 'overdue'; open = false" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 flex items-center gap-2" :class="filters.status === 'overdue' && 'text-violet-600 bg-violet-50'">
                            <span class="w-4 h-4 rounded border flex items-center justify-center" :class="filters.status === 'overdue' ? 'border-violet-500 bg-violet-500' : 'border-slate-300'">
                                <svg x-show="filters.status === 'overdue'" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            Overdue
                        </button>
                    </div>
                </div>

                {{-- Currency Multiselect --}}
                <div class="relative" x-data="{ open: false }">
                    <button x-on:click="open = !open" class="h-9 px-3 rounded-lg bg-white/60 border border-slate-200/60 text-xs text-slate-600 flex items-center gap-2 hover:border-violet-300 transition-colors" :class="filters.currencies.length && 'border-violet-300 bg-violet-50'">
                        <span>Currency</span>
                        <span x-show="filters.currencies.length" class="w-5 h-5 rounded-full bg-violet-500 text-white text-[10px] flex items-center justify-center" x-text="filters.currencies.length"></span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-on:click.away="open = false" x-transition class="absolute top-full left-0 mt-1 w-48 bg-white rounded-lg shadow-xl border border-slate-100 py-1 z-30 dropdown-enter" x-cloak>
                        <template x-for="curr in ['EUR', 'USD', 'GBP', 'BAM']" :key="curr">
                            <button x-on:click="toggleCurrency(curr)" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 flex items-center gap-2" :class="filters.currencies.includes(curr) && 'text-violet-600 bg-violet-50'">
                                <span class="w-4 h-4 rounded border flex items-center justify-center" :class="filters.currencies.includes(curr) ? 'border-violet-500 bg-violet-500' : 'border-slate-300'">
                                    <svg x-show="filters.currencies.includes(curr)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span x-text="curr"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Boolean Toggle --}}
                <div class="flex items-center gap-2 h-9 px-3 rounded-lg bg-white/60 border border-slate-200/60" :class="filters.hasAttachments && 'border-violet-300 bg-violet-50'">
                    <span class="text-xs text-slate-600">Attachments</span>
                    <button x-on:click="filters.hasAttachments = !filters.hasAttachments" class="relative w-9 h-5 rounded-full toggle-switch" :class="filters.hasAttachments ? 'bg-violet-500' : 'bg-slate-200'">
                        <span class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow toggle-dot" :class="filters.hasAttachments && 'translate-x-4'"></span>
                    </button>
                </div>

                {{-- Active Filters --}}
                <template x-if="filters.status || filters.currencies.length || filters.hasAttachments">
                    <div class="flex items-center gap-1.5 ml-auto">
                        <template x-if="filters.status">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-violet-100 text-violet-700 text-[11px] font-medium">
                                <span x-text="filters.status"></span>
                                <button x-on:click="filters.status = ''" class="hover:text-violet-900"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                            </span>
                        </template>
                        <template x-for="curr in filters.currencies" :key="curr">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-violet-100 text-violet-700 text-[11px] font-medium">
                                <span x-text="curr"></span>
                                <button x-on:click="toggleCurrency(curr)" class="hover:text-violet-900"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                            </span>
                        </template>
                        <template x-if="filters.hasAttachments">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-violet-100 text-violet-700 text-[11px] font-medium">
                                <span>Has files</span>
                                <button x-on:click="filters.hasAttachments = false" class="hover:text-violet-900"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                            </span>
                        </template>
                        <button x-on:click="clearFilters()" class="text-[11px] text-slate-500 hover:text-slate-700 underline ml-1">Clear all</button>
                    </div>
                </template>
            </div>
        </div>

        {{-- Table --}}
        <div class="card-glass rounded-xl border border-white/60 overflow-hidden">
            @include('app.invoice3.partials.table')
        </div>
    </div>
</main>
