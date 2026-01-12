{{-- Desktop Sidebar --}}
<aside class="fixed inset-y-0 left-0 z-40 hidden lg:flex flex-col p-2 no-print transition-all duration-300" :class="[sidebarCollapsed ? 'w-[4.5rem]' : 'w-64', sidebarAnimating && !sidebarCollapsed ? 'sidebar-enter' : '']">
    <div class="flex-1 flex flex-col nav-box rounded-2xl backdrop-blur-xl overflow-hidden">
        {{-- Logo --}}
        <div class="h-12 flex items-center border-b border-slate-200/60 dark:border-slate-700/50" :class="sidebarCollapsed ? 'justify-center px-2' : 'justify-between px-3'">
            <a href="{{ route('app.dashboard', $company) }}" class="flex items-center gap-2 overflow-hidden" :class="sidebarCollapsed && 'justify-center'">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 via-purple-500 to-fuchsia-500 flex items-center justify-center shadow-lg shadow-violet-500/25 flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span x-show="!sidebarCollapsed" x-transition.opacity class="font-bold text-base bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 bg-clip-text text-transparent whitespace-nowrap">InvoicePro</span>
            </a>
            <button @click="sidebarCollapsed = !sidebarCollapsed" x-show="!sidebarCollapsed" class="p-1 rounded-lg hover:bg-slate-200/80 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
            </button>
        </div>

        {{-- Company Switcher --}}
        @php
            $userCompanies = auth()->user()->companies;
            $currentCompany = view()->shared('currentCompany');
        @endphp
        <div class="p-2 border-b border-slate-200/60 dark:border-slate-700/50" x-show="!sidebarCollapsed">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center gap-2 p-2 rounded-lg bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600/50 hover:border-violet-400 dark:hover:border-violet-500/40 transition-all shadow-sm">
                    <x-app.avatar :name="$currentCompany->name" size="xs" />
                    <div class="flex-1 text-left min-w-0">
                        <p class="text-[11px] font-bold text-slate-800 dark:text-slate-200 truncate">{{ $currentCompany->name }}</p>
                        <p class="text-[9px] text-slate-500 dark:text-slate-400">Switch company</p>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-slate-200 dark:border-slate-700 py-1 z-50" x-cloak>
                    @foreach($userCompanies as $comp)
                        <form action="{{ route('app.company.switch', $comp) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-2.5 py-1.5 text-left hover:bg-slate-100 dark:hover:bg-slate-700/50 flex items-center gap-2 transition-colors {{ $currentCompany->id === $comp->id ? 'bg-violet-50 dark:bg-violet-900/30' : '' }}">
                                <x-app.avatar :name="$comp->name" size="xs" class="{{ $currentCompany->id === $comp->id ? '' : 'bg-slate-300 dark:bg-slate-600' }}" />
                                <span class="text-[11px] {{ $currentCompany->id === $comp->id ? 'text-violet-700 dark:text-violet-300 font-semibold' : 'text-slate-600 dark:text-slate-300' }}">{{ $comp->name }}</span>
                                @if($currentCompany->id === $comp->id)
                                    <svg class="w-3.5 h-3.5 ml-auto text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </button>
                        </form>
                    @endforeach
                    <div class="border-t border-slate-200 dark:border-slate-700 mt-1 pt-1 px-1">
                        <a href="{{ route('app.company.select') }}" class="flex items-center gap-1.5 px-2 py-1.5 text-[11px] text-violet-600 dark:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/20 rounded-md transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Manage companies
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto custom-scrollbar py-2 px-1.5">
            <div class="mb-3">
                <p x-show="!sidebarCollapsed" class="px-2 mb-1.5 text-[9px] font-bold text-slate-500 dark:text-slate-500 uppercase tracking-wider">Main</p>
                <a href="{{ route('app.dashboard', $company) }}" class="group flex items-center rounded-lg transition-all duration-200 {{ request()->routeIs('app.dashboard') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}" :class="sidebarCollapsed ? 'justify-center p-2' : 'gap-2 px-2 py-1.5'" data-tip="Dashboard" :data-tip="sidebarCollapsed ? 'Dashboard' : ''">
                    <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center {{ request()->routeIs('app.dashboard') ? 'bg-violet-100 dark:bg-violet-800 text-violet-600 dark:text-violet-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400' }} group-hover:bg-slate-200 dark:group-hover:bg-slate-600 transition-colors" :class="sidebarCollapsed && 'tooltip'">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5z"/></svg>
                    </div>
                    <span x-show="!sidebarCollapsed" class="text-[11px] font-medium">Dashboard</span>
                </a>
            </div>

            <div class="mb-3">
                <p x-show="!sidebarCollapsed" class="px-2 mb-1.5 text-[9px] font-bold text-slate-500 dark:text-slate-500 uppercase tracking-wider">Documents</p>
                
                {{-- Invoices Menu --}}
                <div>
                    <button @click="sidebarCollapsed ? null : toggleMenu('invoices')" class="w-full group flex items-center rounded-lg transition-all duration-200 {{ request()->routeIs('app.invoices.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }}" :class="sidebarCollapsed ? 'justify-center p-2 tooltip' : 'gap-2 px-2 py-1.5'" data-tip="Invoices">
                        <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center {{ request()->routeIs('app.invoices.*') ? 'bg-gradient-to-br from-violet-500 to-fuchsia-600 text-white shadow-md shadow-violet-500/25' : 'bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }} group-hover:scale-105 transition-transform">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span x-show="!sidebarCollapsed" class="flex-1 text-left text-[11px] {{ request()->routeIs('app.invoices.*') ? 'font-bold' : 'font-medium' }}">Invoices</span>
                        <svg x-show="!sidebarCollapsed" class="w-3 h-3 transition-transform duration-200" :class="menuOpen.invoices && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="menuOpen.invoices && !sidebarCollapsed" x-transition x-collapse class="ml-9 mt-0.5 space-y-0.5" x-cloak>
                        <a href="{{ route('app.invoices.index', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.invoices.index') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">All Invoices</a>
                        <a href="{{ route('app.invoices.create', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.invoices.create') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">Create New</a>
                        <a href="#" class="block px-2 py-1 rounded-md text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50 text-[10px] transition-colors">Drafts</a>
                    </div>
                </div>

                {{-- Proformas --}}
                <div class="mt-0.5">
                    <button @click="sidebarCollapsed ? null : toggleMenu('proformas')" class="w-full group flex items-center rounded-lg transition-all duration-200 {{ request()->routeIs('app.proformas.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }}" :class="sidebarCollapsed ? 'justify-center p-2 tooltip' : 'gap-2 px-2 py-1.5'" data-tip="Proformas">
                        <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center {{ request()->routeIs('app.proformas.*') ? 'bg-violet-100 dark:bg-violet-800 text-violet-600 dark:text-violet-300' : 'bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }} group-hover:scale-105 transition-transform">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span x-show="!sidebarCollapsed" class="flex-1 text-left text-[11px] {{ request()->routeIs('app.proformas.*') ? 'font-bold' : 'font-medium' }}">Proformas</span>
                        <svg x-show="!sidebarCollapsed" class="w-3 h-3 transition-transform duration-200" :class="menuOpen.proformas && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="menuOpen.proformas && !sidebarCollapsed" x-transition x-collapse class="ml-9 mt-0.5 space-y-0.5" x-cloak>
                        <a href="{{ route('app.proformas.index', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.proformas.index') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">All Proformas</a>
                    </div>
                </div>

                {{-- Quotes --}}
                <div class="mt-0.5">
                    <button @click="sidebarCollapsed ? null : toggleMenu('quotes')" class="w-full group flex items-center rounded-lg transition-all duration-200 {{ request()->routeIs('app.quotes.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }}" :class="sidebarCollapsed ? 'justify-center p-2 tooltip' : 'gap-2 px-2 py-1.5'" data-tip="Quotes">
                        <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center {{ request()->routeIs('app.quotes.*') ? 'bg-violet-100 dark:bg-violet-800 text-violet-600 dark:text-violet-300' : 'bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }} group-hover:scale-105 transition-transform">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <span x-show="!sidebarCollapsed" class="flex-1 text-left text-[11px] {{ request()->routeIs('app.quotes.*') ? 'font-bold' : 'font-medium' }}">Quotes</span>
                        <svg x-show="!sidebarCollapsed" class="w-3 h-3 transition-transform duration-200" :class="menuOpen.quotes && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="menuOpen.quotes && !sidebarCollapsed" x-transition x-collapse class="ml-9 mt-0.5 space-y-0.5" x-cloak>
                        <a href="{{ route('app.quotes.index', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.quotes.index') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">All Quotes</a>
                    </div>
                </div>

                {{-- Contracts --}}
                <div class="mt-0.5">
                    <button @click="sidebarCollapsed ? null : toggleMenu('contracts')" class="w-full group flex items-center rounded-lg transition-all duration-200 {{ request()->routeIs('app.contracts.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }}" :class="sidebarCollapsed ? 'justify-center p-2 tooltip' : 'gap-2 px-2 py-1.5'" data-tip="Contracts">
                        <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center {{ request()->routeIs('app.contracts.*') ? 'bg-violet-100 dark:bg-violet-800 text-violet-600 dark:text-violet-300' : 'bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }} group-hover:scale-105 transition-transform">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span x-show="!sidebarCollapsed" class="flex-1 text-left text-[11px] {{ request()->routeIs('app.contracts.*') ? 'font-bold' : 'font-medium' }}">Contracts</span>
                        <svg x-show="!sidebarCollapsed" class="w-3 h-3 transition-transform duration-200" :class="menuOpen.contracts && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="menuOpen.contracts && !sidebarCollapsed" x-transition x-collapse class="ml-9 mt-0.5 space-y-0.5" x-cloak>
                        <a href="{{ route('app.contracts.index', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.contracts.index') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">All Contracts</a>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <p x-show="!sidebarCollapsed" class="px-2 mb-1.5 text-[9px] font-bold text-slate-500 dark:text-slate-500 uppercase tracking-wider">Management</p>
                <a href="{{ route('app.clients.index', $company) }}" class="group flex items-center rounded-lg transition-all duration-200 {{ request()->routeIs('app.clients.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}" :class="sidebarCollapsed ? 'justify-center p-2 tooltip' : 'gap-2 px-2 py-1.5'" data-tip="Clients">
                    <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center {{ request()->routeIs('app.clients.*') ? 'bg-violet-100 dark:bg-violet-800 text-violet-600 dark:text-violet-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400' }} group-hover:bg-slate-200 dark:group-hover:bg-slate-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span x-show="!sidebarCollapsed" class="text-[11px] {{ request()->routeIs('app.clients.*') ? 'font-bold' : 'font-medium' }}">Clients</span>
                </a>
                <div class="mt-0.5">
                    <a href="{{ route('app.articles.index', $company) }}" class="group flex items-center rounded-lg transition-all duration-200 {{ request()->routeIs('app.articles.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}" :class="sidebarCollapsed ? 'justify-center p-2 tooltip' : 'gap-2 px-2 py-1.5'" data-tip="Articles">
                        <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center {{ request()->routeIs('app.articles.*') ? 'bg-violet-100 dark:bg-violet-800 text-violet-600 dark:text-violet-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400' }} group-hover:bg-slate-200 dark:group-hover:bg-slate-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <span x-show="!sidebarCollapsed" class="text-[11px] {{ request()->routeIs('app.articles.*') ? 'font-bold' : 'font-medium' }}">Articles</span>
                    </a>
                </div>
                <div class="mt-0.5">
                    <a href="{{ route('app.payments.index', $company) }}" class="group flex items-center rounded-lg transition-all duration-200 {{ request()->routeIs('app.payments.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}" :class="sidebarCollapsed ? 'justify-center p-2 tooltip' : 'gap-2 px-2 py-1.5'" data-tip="Payments">
                        <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center {{ request()->routeIs('app.payments.*') ? 'bg-violet-100 dark:bg-violet-800 text-violet-600 dark:text-violet-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400' }} group-hover:bg-slate-200 dark:group-hover:bg-slate-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span x-show="!sidebarCollapsed" class="text-[11px] {{ request()->routeIs('app.payments.*') ? 'font-bold' : 'font-medium' }}">Payments</span>
                    </a>
                </div>
                <div class="mt-0.5">
                    <a href="{{ route('app.reports.index', $company) }}" class="group flex items-center rounded-lg transition-all duration-200 {{ request()->routeIs('app.reports.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}" :class="sidebarCollapsed ? 'justify-center p-2 tooltip' : 'gap-2 px-2 py-1.5'" data-tip="Reports">
                        <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center {{ request()->routeIs('app.reports.*') ? 'bg-violet-100 dark:bg-violet-800 text-violet-600 dark:text-violet-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400' }} group-hover:bg-slate-200 dark:group-hover:bg-slate-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <span x-show="!sidebarCollapsed" class="text-[11px] {{ request()->routeIs('app.reports.*') ? 'font-bold' : 'font-medium' }}">Reports</span>
                    </a>
                </div>
            </div>

            <div>
                <p x-show="!sidebarCollapsed" class="px-2 mb-1.5 text-[9px] font-bold text-slate-500 dark:text-slate-500 uppercase tracking-wider">System</p>
                {{-- Settings with 3 levels --}}
                <div>
                    <button @click="sidebarCollapsed ? null : toggleMenu('settings')" class="w-full group flex items-center rounded-lg transition-all duration-200 {{ request()->routeIs('app.settings.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}" :class="sidebarCollapsed ? 'justify-center p-2 tooltip' : 'gap-2 px-2 py-1.5'" data-tip="Settings">
                        <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center {{ request()->routeIs('app.settings.*') ? 'bg-violet-100 dark:bg-violet-800 text-violet-600 dark:text-violet-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400' }} group-hover:bg-slate-200 dark:group-hover:bg-slate-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span x-show="!sidebarCollapsed" class="flex-1 text-left text-[11px] {{ request()->routeIs('app.settings.*') ? 'font-bold' : 'font-medium' }}">Settings</span>
                        <svg x-show="!sidebarCollapsed" class="w-3 h-3 transition-transform duration-200" :class="menuOpen.settings && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="menuOpen.settings && !sidebarCollapsed" x-transition x-collapse class="ml-9 mt-0.5 space-y-0.5" x-cloak>
                        <a href="{{ route('app.settings.index', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.settings.index') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">General Info</a>
                        <a href="{{ route('app.settings.invoice', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.settings.invoice') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">Invoice Defaults</a>
                        <a href="{{ route('app.settings.fiscalization', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.settings.fiscalization') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">Fiscalization</a>
                        
                        <div class="h-px bg-slate-200 dark:bg-slate-700/50 my-1 mx-2"></div>
                        
                        <a href="{{ route('app.settings.currencies', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.settings.currencies') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">Currencies</a>
                        <a href="{{ route('app.settings.bank-accounts', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.settings.bank-accounts') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">Bank Accounts</a>
                        
                        <div class="h-px bg-slate-200 dark:bg-slate-700/50 my-1 mx-2"></div>
                        
                        <a href="{{ route('app.settings.email', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.settings.email') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">Email Config</a>
                        <a href="{{ route('app.settings.email-templates', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.settings.email-templates') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">Email Templates</a>
                        
                        <div class="h-px bg-slate-200 dark:bg-slate-700/50 my-1 mx-2"></div>
                        
                        <a href="{{ route('app.settings.appearance', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.settings.appearance') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">Appearance</a>
                        <a href="{{ route('app.settings.notifications', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.settings.notifications') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">Notifications</a>
                        <a href="{{ route('app.settings.roles', $company) }}" class="block px-2 py-1 rounded-md {{ request()->routeIs('app.settings.roles') ? 'text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium' : 'text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50' }} text-[10px] transition-colors">Roles & Perms</a>
                    </div>
                </div>
            </div>
        </nav>

        {{-- User Profile --}}
        <div class="p-2 border-t border-slate-200/60 dark:border-slate-700/50">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center gap-2 p-1.5 rounded-lg hover:bg-slate-200/80 dark:hover:bg-slate-700/50 transition-colors" :class="sidebarCollapsed ? 'justify-center tooltip' : ''" :data-tip="sidebarCollapsed ? '{{ auth()->user()->name }}' : ''">
                    <x-app.avatar :name="auth()->user()->name" size="xs" />
                    <div x-show="!sidebarCollapsed" class="flex-1 text-left min-w-0">
                        <p class="text-[11px] font-bold text-slate-700 dark:text-slate-200 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] text-slate-500 dark:text-slate-400">Administrator</p>
                    </div>
                    <svg x-show="!sidebarCollapsed" class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute bottom-full left-0 right-0 mb-1.5 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-slate-200 dark:border-slate-700 py-1 z-50" x-cloak>
                    <a href="#" class="flex items-center gap-2 px-2.5 py-1.5 text-[11px] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        My Profile
                    </a>
                    <div class="border-t border-slate-200 dark:border-slate-700 my-1"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-2.5 py-1.5 text-[11px] text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 text-left">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>

{{-- Mobile Sidebar Overlay --}}
<div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" @click="mobileMenuOpen = false" class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-sm z-40 lg:hidden no-print" x-cloak></div>

{{-- Mobile Sidebar --}}
<aside x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-80 p-3 lg:hidden no-print" x-cloak>
    <div class="h-full flex flex-col nav-box rounded-2xl backdrop-blur-xl overflow-hidden">
        <div class="h-14 flex items-center justify-between px-4 border-b border-slate-200/60 dark:border-slate-700/50">
            <a href="{{ route('app.dashboard', $company) }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-500 via-purple-500 to-fuchsia-500 flex items-center justify-center shadow-lg shadow-violet-500/30">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="font-bold text-lg bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 bg-clip-text text-transparent">InvoicePro</span>
            </a>
            <button @click="mobileMenuOpen = false" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto custom-scrollbar py-4 px-3 space-y-1">
            <a href="{{ route('app.dashboard', $company) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('app.dashboard') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-700/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5z"/></svg>
                <span class="text-sm font-bold">Dashboard</span>
            </a>

            <div class="h-px bg-slate-200 dark:bg-slate-700/50 my-2 mx-4"></div>

            <a href="{{ route('app.invoices.index', $company) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('app.invoices.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-700/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-sm font-bold">Invoices</span>
            </a>

            <a href="{{ route('app.proformas.index', $company) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('app.proformas.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-700/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-sm font-bold">Proformas</span>
            </a>

            <a href="{{ route('app.quotes.index', $company) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('app.quotes.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-700/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="text-sm font-bold">Quotes</span>
            </a>

            <a href="{{ route('app.contracts.index', $company) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('app.contracts.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-700/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-sm font-bold">Contracts</span>
            </a>

            <div class="h-px bg-slate-200 dark:bg-slate-700/50 my-2 mx-4"></div>

            <a href="{{ route('app.clients.index', $company) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('app.clients.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-700/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-sm font-bold">Clients</span>
            </a>

            <a href="{{ route('app.articles.index', $company) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('app.articles.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-700/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span class="text-sm font-bold">Articles</span>
            </a>

            <a href="{{ route('app.payments.index', $company) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('app.payments.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-700/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-bold">Payments</span>
            </a>

            <a href="{{ route('app.reports.index', $company) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('app.reports.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-700/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span class="text-sm font-bold">Reports</span>
            </a>

            <a href="{{ route('app.settings.index', $company) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('app.settings.*') ? 'bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-700/50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37"/></svg>
                <span class="text-sm font-bold">Settings</span>
            </a>
        </nav>
        <div class="p-4 border-t border-slate-200/60 dark:border-slate-700/50">
            <div class="flex items-center gap-3 p-2">
                <x-app.avatar :name="auth()->user()->name" size="md" />
                <div class="flex-1">
                    <p class="text-sm font-bold text-slate-800 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Administrator</p>
                </div>
            </div>
        </div>
    </div>
</aside>
