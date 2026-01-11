{{-- Desktop Sidebar --}}
<aside class="fixed inset-y-0 left-0 z-50 hidden lg:flex flex-col sidebar-glass border-r border-slate-200/60 shadow-xl sidebar-transition"
    :class="sidebarCollapsed ? 'w-[72px]' : 'w-[260px]'">
    
    {{-- Logo & Collapse --}}
    <div class="h-14 flex items-center justify-between px-4 border-b border-slate-200/60">
        <a href="#" class="flex items-center gap-2.5 overflow-hidden">
            <div class="w-8 h-8 rounded-lg gradient-bg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <span x-show="!sidebarCollapsed" x-transition:enter="transition delay-100 duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="font-bold text-base text-gradient whitespace-nowrap">InvoicePro</span>
        </a>
        <button x-on:click="toggleSidebar()" class="p-2 rounded-lg hover:bg-violet-50 text-slate-400 hover:text-violet-600 transition-colors" :class="sidebarCollapsed && 'mx-auto'" title="Toggle sidebar">
            <svg x-show="!sidebarCollapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
            <svg x-show="sidebarCollapsed" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
        </button>
    </div>

    {{-- Company Switcher --}}
    <div class="px-3 py-3 border-b border-slate-200/60" x-show="!sidebarCollapsed">
        <div class="relative">
            <button x-on:click="companyMenuOpen = !companyMenuOpen" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors">
                <div class="w-7 h-7 rounded-md gradient-bg flex items-center justify-center text-white text-xs font-bold flex-shrink-0" x-text="currentCompany.substring(0,2).toUpperCase()"></div>
                <div class="flex-1 text-left min-w-0">
                    <p class="text-xs font-semibold text-slate-700 truncate" x-text="currentCompany"></p>
                    <p class="text-[10px] text-slate-400">Switch company</p>
                </div>
                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="companyMenuOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="companyMenuOpen" x-on:click.away="companyMenuOpen = false" x-transition class="absolute left-0 right-0 mt-1 bg-white rounded-lg shadow-xl border border-slate-100 py-1 z-50 dropdown-enter" x-cloak>
                <template x-for="company in companies" :key="company">
                    <button x-on:click="switchCompany(company)" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 flex items-center gap-2" :class="currentCompany === company && 'bg-violet-50 text-violet-700'">
                        <div class="w-6 h-6 rounded-md bg-slate-200 flex items-center justify-center text-[10px] font-bold" :class="currentCompany === company && 'gradient-bg text-white'" x-text="company.substring(0,2).toUpperCase()"></div>
                        <span x-text="company"></span>
                        <svg x-show="currentCompany === company" class="w-4 h-4 ml-auto text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </template>
                <div class="border-t border-slate-100 mt-1 pt-1">
                    <a href="#" class="block px-3 py-2 text-xs text-slate-500 hover:bg-slate-50 hover:text-slate-700">+ Add new company</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto custom-scrollbar py-3 px-2">
        {{-- Section: Main --}}
        <div class="mb-4">
            <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Main</p>
            <a href="#" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:text-slate-900" :class="sidebarCollapsed && 'justify-center'" :title="sidebarCollapsed ? 'Dashboard' : ''">
                <svg class="w-[18px] h-[18px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span x-show="!sidebarCollapsed" class="font-medium">Dashboard</span>
            </a>
        </div>

        {{-- Section: Documents --}}
        <div class="mb-4">
            <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Documents</p>
            
            {{-- Invoices --}}
            <div>
                <button x-on:click="toggleMenu('invoices')" class="menu-item w-full flex items-center gap-2.5 px-3 py-2 rounded-lg bg-violet-50 text-violet-700" :class="sidebarCollapsed && 'justify-center'">
                    <svg class="w-[18px] h-[18px] text-violet-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span x-show="!sidebarCollapsed" class="flex-1 text-left font-medium">Invoices</span>
                    <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform duration-200" :class="menuOpen.invoices && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="menuOpen.invoices && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="ml-7 mt-1 space-y-0.5 submenu-enter" x-cloak>
                    <a href="/templates/invoice3" class="block px-3 py-1.5 rounded-md text-violet-600 bg-violet-50/50 font-medium text-xs">All Invoices</a>
                    <a href="/templates/invoice3/create" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-xs">Create New</a>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-xs">Drafts</a>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-xs">Recurring</a>
                </div>
            </div>

            {{-- Quotes --}}
            <div class="mt-1">
                <button x-on:click="toggleMenu('quotes')" class="menu-item w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:text-slate-900" :class="sidebarCollapsed && 'justify-center'">
                    <svg class="w-[18px] h-[18px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span x-show="!sidebarCollapsed" class="flex-1 text-left font-medium">Quotes</span>
                    <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform duration-200" :class="menuOpen.quotes && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="menuOpen.quotes && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="ml-7 mt-1 space-y-0.5" x-cloak>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-xs">All Quotes</a>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-xs">Pending</a>
                </div>
            </div>

            <a href="#" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:text-slate-900 mt-1" :class="sidebarCollapsed && 'justify-center'">
                <svg class="w-[18px] h-[18px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span x-show="!sidebarCollapsed" class="font-medium">Proformas</span>
            </a>
        </div>

        {{-- Section: Management --}}
        <div class="mb-4">
            <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Management</p>
            
            {{-- Clients --}}
            <div>
                <button x-on:click="toggleMenu('clients')" class="menu-item w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:text-slate-900" :class="sidebarCollapsed && 'justify-center'">
                    <svg class="w-[18px] h-[18px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-show="!sidebarCollapsed" class="flex-1 text-left font-medium">Clients</span>
                    <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform duration-200" :class="menuOpen.clients && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="menuOpen.clients && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="ml-7 mt-1 space-y-0.5" x-cloak>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-xs">All Clients</a>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-xs">Companies</a>
                </div>
            </div>

            <a href="#" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:text-slate-900 mt-1" :class="sidebarCollapsed && 'justify-center'">
                <svg class="w-[18px] h-[18px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span x-show="!sidebarCollapsed" class="font-medium">Articles</span>
            </a>

            <a href="#" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:text-slate-900 mt-1" :class="sidebarCollapsed && 'justify-center'">
                <svg class="w-[18px] h-[18px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span x-show="!sidebarCollapsed" class="font-medium">Payments</span>
            </a>
        </div>

        {{-- Section: Settings with 3rd level --}}
        <div>
            <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Settings</p>
            <div>
                <button x-on:click="toggleMenu('settings')" class="menu-item w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:text-slate-900" :class="sidebarCollapsed && 'justify-center'">
                    <svg class="w-[18px] h-[18px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-show="!sidebarCollapsed" class="flex-1 text-left font-medium">Settings</span>
                    <svg x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform duration-200" :class="menuOpen.settings && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="menuOpen.settings && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="ml-7 mt-1 space-y-0.5" x-cloak>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-xs">Company</a>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-xs">Invoice Settings</a>
                    {{-- 3rd level menu --}}
                    <div>
                        <button x-on:click="toggleMenu('email')" class="w-full flex items-center justify-between px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-xs">
                            <span>Email</span>
                            <svg class="w-3 h-3 transition-transform duration-200" :class="menuOpen.email && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="menuOpen.email" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="ml-3 mt-0.5 space-y-0.5 border-l-2 border-slate-200 pl-2" x-cloak>
                            <a href="#" class="block px-2 py-1 rounded text-[11px] text-slate-400 hover:text-slate-600 hover:bg-slate-50">Templates</a>
                            <a href="#" class="block px-2 py-1 rounded text-[11px] text-slate-400 hover:text-slate-600 hover:bg-slate-50">Signatures</a>
                            <a href="#" class="block px-2 py-1 rounded text-[11px] text-slate-400 hover:text-slate-600 hover:bg-slate-50">SMTP</a>
                        </div>
                    </div>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-xs">Currencies</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- User Menu --}}
    <div class="p-3 border-t border-slate-200/60">
        <div class="relative">
            <button x-on:click="userMenuOpen = !userMenuOpen" class="w-full flex items-center gap-2.5 p-2 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors" :class="sidebarCollapsed && 'justify-center'">
                <div class="w-8 h-8 rounded-full gradient-bg flex items-center justify-center text-white text-xs font-bold flex-shrink-0">JD</div>
                <div x-show="!sidebarCollapsed" class="flex-1 min-w-0 text-left">
                    <p class="text-xs font-semibold text-slate-700 truncate">John Doe</p>
                    <p class="text-[10px] text-slate-400">Admin</p>
                </div>
                <svg x-show="!sidebarCollapsed" class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
            </button>
            <div x-show="userMenuOpen" x-on:click.away="userMenuOpen = false" x-transition class="absolute bottom-full left-0 right-0 mb-1 bg-white rounded-lg shadow-xl border border-slate-100 py-1 z-50 dropdown-enter" x-cloak>
                <a href="#" class="flex items-center gap-2 px-3 py-2 text-xs text-slate-600 hover:bg-slate-50">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    My Profile
                </a>
                <a href="#" class="flex items-center gap-2 px-3 py-2 text-xs text-slate-600 hover:bg-slate-50">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Account Settings
                </a>
                <div class="border-t border-slate-100 my-1"></div>
                <a href="#" class="flex items-center gap-2 px-3 py-2 text-xs text-rose-600 hover:bg-rose-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </a>
            </div>
        </div>
    </div>
</aside>

{{-- Mobile Overlay --}}
<div x-show="mobileMenuOpen" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-150" x-on:click="mobileMenuOpen = false" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 lg:hidden" x-cloak></div>

{{-- Mobile Sidebar --}}
<aside x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-[280px] sidebar-glass border-r border-slate-200/60 shadow-2xl lg:hidden flex flex-col" x-cloak>
    <div class="h-14 flex items-center justify-between px-4 border-b border-slate-200/60">
        <span class="font-bold text-base text-gradient">InvoicePro</span>
        <button x-on:click="mobileMenuOpen = false" class="p-1.5 rounded-md hover:bg-slate-100 text-slate-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <nav class="flex-1 overflow-y-auto custom-scrollbar p-3">
        <p class="px-3 mb-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Documents</p>
        <a href="/templates/invoice3" class="flex items-center gap-2.5 px-3 py-2 rounded-lg bg-violet-50 text-violet-700 font-medium mb-1">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Invoices
        </a>
        <a href="#" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-50 mb-1">
            <svg class="w-[18px] h-[18px] text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Quotes
        </a>
        <p class="px-3 mt-4 mb-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Management</p>
        <a href="#" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-50 mb-1">
            <svg class="w-[18px] h-[18px] text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Clients
        </a>
        <a href="#" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:bg-slate-50 mb-1">
            <svg class="w-[18px] h-[18px] text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Articles
        </a>
    </nav>
</aside>
