{{-- Desktop Sidebar --}}
<aside class="fixed inset-y-0 left-0 z-50 hidden lg:flex flex-col glass sidebar-shadow transition-all duration-300" :class="sidebarCollapsed ? 'w-20' : 'w-72'">
    {{-- Logo --}}
    <div class="h-16 flex items-center justify-between px-5 border-b border-slate-200/60">
        <a href="/templates/invoice4" class="flex items-center gap-3 overflow-hidden">
            <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center shadow-lg shadow-indigo-500/30 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <span x-show="!sidebarCollapsed" x-transition.opacity class="font-bold text-xl bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent whitespace-nowrap">InvoicePro</span>
        </a>
        <button @click="sidebarCollapsed = !sidebarCollapsed" class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors" x-show="!sidebarCollapsed">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
        </button>
    </div>

    {{-- Company Switcher --}}
    <div class="px-4 py-4 border-b border-slate-200/60" x-show="!sidebarCollapsed">
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="w-full flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-slate-50 to-slate-100 hover:from-slate-100 hover:to-slate-150 border border-slate-200/60 transition-all">
                <div class="w-10 h-10 rounded-lg gradient-primary flex items-center justify-center text-white text-sm font-bold shadow-md" x-text="currentCompany.substring(0,2).toUpperCase()"></div>
                <div class="flex-1 text-left min-w-0">
                    <p class="text-sm font-semibold text-slate-800 truncate" x-text="currentCompany"></p>
                    <p class="text-xs text-slate-500">Switch company</p>
                </div>
                <svg class="w-5 h-5 text-slate-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-slate-200/60 py-2 z-50 dropdown-enter" x-cloak>
                <template x-for="company in companies" :key="company.id">
                    <button @click="switchCompany(company); open = false" class="w-full px-4 py-2.5 text-left hover:bg-slate-50 flex items-center gap-3 transition-colors" :class="currentCompany === company.name && 'bg-indigo-50'">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold" :class="currentCompany === company.name ? 'gradient-primary text-white' : 'bg-slate-200 text-slate-600'" x-text="company.name.substring(0,2).toUpperCase()"></div>
                        <span class="text-sm" :class="currentCompany === company.name ? 'text-indigo-600 font-semibold' : 'text-slate-700'" x-text="company.name"></span>
                        <svg x-show="currentCompany === company.name" class="w-5 h-5 ml-auto text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </template>
                <div class="border-t border-slate-100 mt-2 pt-2 px-2">
                    <a href="#" class="flex items-center gap-2 px-3 py-2 text-sm text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add new company
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto custom-scrollbar py-3 px-2">
        {{-- Dashboard --}}
        <div class="mb-3">
            <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Main</p>
            <a href="#" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:text-slate-900" :class="sidebarCollapsed && 'justify-center'">
                <svg class="w-[18px] h-[18px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span x-show="!sidebarCollapsed" class="font-medium text-xs">Dashboard</span>
            </a>
        </div>

        {{-- Documents with submenus --}}
        <div class="mb-3">
            <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Documents</p>
            
            {{-- Invoices (Level 1 -> Level 2) --}}
            <div>
                <button @click="toggleMenu('invoices')" class="menu-item w-full flex items-center gap-2.5 px-3 py-2 rounded-lg bg-violet-50 text-violet-700" :class="sidebarCollapsed && 'justify-center'">
                    <svg class="w-[18px] h-[18px] text-violet-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span x-show="!sidebarCollapsed" class="flex-1 text-left font-medium text-xs">Invoices</span>
                    <svg x-show="!sidebarCollapsed" class="w-3.5 h-3.5 transition-transform duration-200" :class="menuOpen.invoices && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="menuOpen.invoices && !sidebarCollapsed" x-transition class="ml-6 mt-1 space-y-0.5 submenu-enter" x-cloak>
                    <a href="/templates/invoice4" class="block px-3 py-1.5 rounded-md text-violet-600 bg-violet-50/50 font-medium text-[11px]">All Invoices</a>
                    <a href="/templates/invoice4/create" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-[11px]">Create New</a>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-[11px]">Drafts</a>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-[11px]">Recurring</a>
                </div>
            </div>

            {{-- Quotes --}}
            <div class="mt-1">
                <button @click="toggleMenu('quotes')" class="menu-item w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:text-slate-900" :class="sidebarCollapsed && 'justify-center'">
                    <svg class="w-[18px] h-[18px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span x-show="!sidebarCollapsed" class="flex-1 text-left font-medium text-xs">Quotes</span>
                    <svg x-show="!sidebarCollapsed" class="w-3.5 h-3.5 transition-transform duration-200" :class="menuOpen.quotes && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="menuOpen.quotes && !sidebarCollapsed" x-transition class="ml-6 mt-1 space-y-0.5" x-cloak>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-[11px]">All Quotes</a>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-[11px]">Pending</a>
                </div>
            </div>
        </div>

        {{-- Management --}}
        <div class="mb-3">
            <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Management</p>
            
            {{-- Clients --}}
            <div>
                <button @click="toggleMenu('clients')" class="menu-item w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:text-slate-900" :class="sidebarCollapsed && 'justify-center'">
                    <svg class="w-[18px] h-[18px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-show="!sidebarCollapsed" class="flex-1 text-left font-medium text-xs">Clients</span>
                    <svg x-show="!sidebarCollapsed" class="w-3.5 h-3.5 transition-transform duration-200" :class="menuOpen.clients && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="menuOpen.clients && !sidebarCollapsed" x-transition class="ml-6 mt-1 space-y-0.5" x-cloak>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-[11px]">All Clients</a>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-[11px]">Companies</a>
                </div>
            </div>

            <a href="#" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:text-slate-900 mt-1" :class="sidebarCollapsed && 'justify-center'">
                <svg class="w-[18px] h-[18px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span x-show="!sidebarCollapsed" class="font-medium text-xs">Articles</span>
            </a>
        </div>

        {{-- Settings with 3 levels --}}
        <div>
            <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">System</p>
            <div>
                <button @click="toggleMenu('settings')" class="menu-item w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-slate-600 hover:text-slate-900" :class="sidebarCollapsed && 'justify-center'">
                    <svg class="w-[18px] h-[18px] text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-show="!sidebarCollapsed" class="flex-1 text-left font-medium text-xs">Settings</span>
                    <svg x-show="!sidebarCollapsed" class="w-3.5 h-3.5 transition-transform duration-200" :class="menuOpen.settings && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                {{-- Level 2 --}}
                <div x-show="menuOpen.settings && !sidebarCollapsed" x-transition class="ml-6 mt-1 space-y-0.5" x-cloak>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-[11px]">Company</a>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-[11px]">Invoice Settings</a>
                    {{-- Level 3 (Email submenu) --}}
                    <div>
                        <button @click="toggleMenu('email')" class="w-full flex items-center justify-between px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-[11px]">
                            <span>Email</span>
                            <svg class="w-3 h-3 transition-transform duration-200" :class="menuOpen.email && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        {{-- Level 3 items --}}
                        <div x-show="menuOpen.email" x-transition class="ml-3 mt-0.5 space-y-0.5 border-l-2 border-slate-200 pl-2" x-cloak>
                            <a href="#" class="block px-2 py-1 rounded text-[10px] text-slate-400 hover:text-slate-600 hover:bg-slate-50">Templates</a>
                            <a href="#" class="block px-2 py-1 rounded text-[10px] text-slate-400 hover:text-slate-600 hover:bg-slate-50">Signatures</a>
                            <a href="#" class="block px-2 py-1 rounded text-[10px] text-slate-400 hover:text-slate-600 hover:bg-slate-50">SMTP Config</a>
                        </div>
                    </div>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-[11px]">Currencies</a>
                    <a href="#" class="block px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-50 text-[11px]">Tax Rates</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- User Profile --}}
    <div class="p-4 border-t border-slate-200/60">
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="w-full flex items-center gap-3 p-2 rounded-xl hover:bg-slate-100 transition-colors" :class="sidebarCollapsed && 'justify-center'">
                <div class="w-10 h-10 rounded-full gradient-primary flex items-center justify-center text-white text-sm font-bold shadow-md flex-shrink-0">JD</div>
                <div x-show="!sidebarCollapsed" class="flex-1 text-left min-w-0">
                    <p class="text-sm font-semibold text-slate-800 truncate">John Doe</p>
                    <p class="text-xs text-slate-500">Administrator</p>
                </div>
            </button>
            <div x-show="open" @click.away="open = false" x-transition class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-xl shadow-xl border border-slate-200/60 py-2 z-50 dropdown-enter" x-cloak>
                <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    My Profile
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35"/></svg>
                    Settings
                </a>
                <div class="border-t border-slate-100 my-2"></div>
                <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </a>
            </div>
        </div>
    </div>
</aside>

{{-- Mobile Sidebar Overlay --}}
<div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" @click="mobileMenuOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden" x-cloak></div>

{{-- Mobile Sidebar --}}
<aside x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-80 glass sidebar-shadow lg:hidden flex flex-col" x-cloak>
    <div class="h-16 flex items-center justify-between px-5 border-b border-slate-200/60">
        <a href="/templates/invoice4" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <span class="font-bold text-xl bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">InvoicePro</span>
        </a>
        <button @click="mobileMenuOpen = false" class="p-2 rounded-lg hover:bg-slate-100 text-slate-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <div class="px-4 py-4 border-b border-slate-200/60">
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="w-full flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-slate-50 to-slate-100 border border-slate-200/60">
                <div class="w-10 h-10 rounded-lg gradient-primary flex items-center justify-center text-white text-sm font-bold shadow-md" x-text="currentCompany.substring(0,2).toUpperCase()"></div>
                <div class="flex-1 text-left min-w-0">
                    <p class="text-sm font-semibold text-slate-800 truncate" x-text="currentCompany"></p>
                    <p class="text-xs text-slate-500">Switch company</p>
                </div>
                <svg class="w-5 h-5 text-slate-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-slate-200/60 py-2 z-50" x-cloak>
                <template x-for="company in companies" :key="company.id">
                    <button @click="switchCompany(company); open = false" class="w-full px-4 py-2.5 text-left hover:bg-slate-50 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold" :class="currentCompany === company.name ? 'gradient-primary text-white' : 'bg-slate-200 text-slate-600'" x-text="company.name.substring(0,2).toUpperCase()"></div>
                        <span class="text-sm" :class="currentCompany === company.name ? 'text-indigo-600 font-semibold' : 'text-slate-700'" x-text="company.name"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto scrollbar-thin py-4 px-3">
        <a href="/templates/invoice4" class="sidebar-item active flex items-center gap-3 px-4 py-3 rounded-xl text-indigo-700 font-medium mb-2">
            <div class="w-10 h-10 rounded-lg gradient-primary flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <span>Invoices</span>
        </a>
        <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:text-slate-900 mb-2">
            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span>Quotes</span>
        </a>
        <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:text-slate-900 mb-2">
            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span>Clients</span>
        </a>
        <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:text-slate-900 mb-2">
            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35"/></svg>
            <span>Settings</span>
        </a>
    </nav>

    <div class="p-4 border-t border-slate-200/60">
        <div class="flex items-center gap-3 p-2">
            <div class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center text-white font-bold shadow-md">JD</div>
            <div class="flex-1">
                <p class="font-semibold text-slate-800">John Doe</p>
                <p class="text-sm text-slate-500">Administrator</p>
            </div>
        </div>
    </div>
</aside>
