{{-- Desktop Sidebar --}}
<aside class="fixed inset-y-0 left-0 z-40 hidden lg:flex flex-col p-2 no-print transition-all duration-300" :class="[sidebarCollapsed ? 'w-[4.5rem]' : 'w-64', sidebarAnimating && !sidebarCollapsed ? 'sidebar-enter' : '']">
    <div class="flex-1 flex flex-col nav-box rounded-2xl backdrop-blur-xl overflow-hidden">
        {{-- Logo --}}
        <div class="h-12 flex items-center border-b border-slate-200/60 dark:border-slate-700/50" :class="sidebarCollapsed ? 'justify-center px-2' : 'justify-between px-3'">
            <a href="/templates/invoice5" class="flex items-center gap-2 overflow-hidden" :class="sidebarCollapsed && 'justify-center'">
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
        <div class="p-2 border-b border-slate-200/60 dark:border-slate-700/50" x-show="!sidebarCollapsed">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center gap-2 p-2 rounded-lg bg-slate-100/80 dark:bg-slate-700/50 border border-slate-200/80 dark:border-slate-600/50 hover:border-violet-400/60 dark:hover:border-violet-500/40 transition-all">
                    <x-invoice5.avatar :name="'Acme Corp'" size="xs" />
                    <div class="flex-1 text-left min-w-0">
                        <p class="text-[11px] font-semibold text-slate-700 dark:text-slate-200 truncate" x-text="currentCompany"></p>
                        <p class="text-[9px] text-slate-500 dark:text-slate-400">Switch company</p>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-slate-200 dark:border-slate-700 py-1 z-50" x-cloak>
                    <template x-for="company in companies" :key="company.id">
                        <button @click="switchCompany(company); open = false" class="w-full px-2.5 py-1.5 text-left hover:bg-slate-100 dark:hover:bg-slate-700/50 flex items-center gap-2 transition-colors" :class="currentCompany === company.name && 'bg-violet-50 dark:bg-violet-900/30'">
                            <x-invoice5.avatar :name="''" size="xs" x-bind:class="currentCompany === company.name ? '' : 'bg-slate-300 dark:bg-slate-600'" />
                            <span class="text-[11px]" :class="currentCompany === company.name ? 'text-violet-700 dark:text-violet-300 font-semibold' : 'text-slate-600 dark:text-slate-300'" x-text="company.name"></span>
                            <svg x-show="currentCompany === company.name" class="w-3.5 h-3.5 ml-auto text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </template>
                    <div class="border-t border-slate-200 dark:border-slate-700 mt-1 pt-1 px-1">
                        <a href="#" class="flex items-center gap-1.5 px-2 py-1.5 text-[11px] text-violet-600 dark:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/20 rounded-md transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add company
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto custom-scrollbar py-2 px-1.5">
            <div class="mb-3">
                <p x-show="!sidebarCollapsed" class="px-2 mb-1.5 text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Main</p>
                <a href="#" class="group flex items-center rounded-lg transition-all duration-200 text-slate-600 dark:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700/50" :class="sidebarCollapsed ? 'justify-center p-2' : 'gap-2 px-2 py-1.5'" data-tip="Dashboard" :data-tip="sidebarCollapsed ? 'Dashboard' : ''">
                    <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 group-hover:bg-slate-300 dark:group-hover:bg-slate-600 transition-colors" :class="sidebarCollapsed && 'tooltip'">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5z"/></svg>
                    </div>
                    <span x-show="!sidebarCollapsed" class="text-[11px] font-medium">Dashboard</span>
                </a>
            </div>

            <div class="mb-3">
                <p x-show="!sidebarCollapsed" class="px-2 mb-1.5 text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Documents</p>
                
                {{-- Invoices Menu --}}
                <div>
                    <button @click="sidebarCollapsed ? null : toggleMenu('invoices')" class="w-full group flex items-center rounded-lg transition-all duration-200 bg-violet-100/80 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300" :class="sidebarCollapsed ? 'justify-center p-2 tooltip' : 'gap-2 px-2 py-1.5'" data-tip="Invoices">
                        <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center bg-gradient-to-br from-violet-500 to-fuchsia-600 text-white shadow-md shadow-violet-500/25 group-hover:scale-105 transition-transform">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span x-show="!sidebarCollapsed" class="flex-1 text-left text-[11px] font-medium">Invoices</span>
                        <svg x-show="!sidebarCollapsed" class="w-3 h-3 transition-transform duration-200" :class="menuOpen.invoices && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="menuOpen.invoices && !sidebarCollapsed" x-transition x-collapse class="ml-9 mt-0.5 space-y-0.5" x-cloak>
                        <a href="/templates/invoice5" class="block px-2 py-1 rounded-md text-violet-700 dark:text-violet-300 bg-violet-100/60 dark:bg-violet-900/20 font-medium text-[10px]">All Invoices</a>
                        <a href="/templates/invoice5/create" class="block px-2 py-1 rounded-md text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50 text-[10px] transition-colors">Create New</a>
                        <a href="#" class="block px-2 py-1 rounded-md text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50 text-[10px] transition-colors">Drafts</a>
                        <a href="#" class="block px-2 py-1 rounded-md text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50 text-[10px] transition-colors">Recurring</a>
                    </div>
                </div>

                {{-- Quotes --}}
                <div class="mt-0.5">
                    <button @click="sidebarCollapsed ? null : toggleMenu('quotes')" class="w-full group flex items-center rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700/50 transition-all duration-200" :class="sidebarCollapsed ? 'justify-center p-2 tooltip' : 'gap-2 px-2 py-1.5'" data-tip="Quotes">
                        <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 group-hover:bg-slate-300 dark:group-hover:bg-slate-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <span x-show="!sidebarCollapsed" class="flex-1 text-left text-[11px] font-medium">Quotes</span>
                        <svg x-show="!sidebarCollapsed" class="w-3 h-3 transition-transform duration-200" :class="menuOpen.quotes && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="menuOpen.quotes && !sidebarCollapsed" x-transition x-collapse class="ml-9 mt-0.5 space-y-0.5" x-cloak>
                        <a href="#" class="block px-2 py-1 rounded-md text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50 text-[10px] transition-colors">All Quotes</a>
                        <a href="#" class="block px-2 py-1 rounded-md text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50 text-[10px] transition-colors">Pending</a>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <p x-show="!sidebarCollapsed" class="px-2 mb-1.5 text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Management</p>
                <a href="#" class="group flex items-center rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700/50 transition-all duration-200" :class="sidebarCollapsed ? 'justify-center p-2 tooltip' : 'gap-2 px-2 py-1.5'" data-tip="Clients">
                    <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 group-hover:bg-slate-300 dark:group-hover:bg-slate-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span x-show="!sidebarCollapsed" class="text-[11px] font-medium">Clients</span>
                </a>
                <div class="mt-0.5">
                    <a href="#" class="group flex items-center rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700/50 transition-all duration-200" :class="sidebarCollapsed ? 'justify-center p-2 tooltip' : 'gap-2 px-2 py-1.5'" data-tip="Articles">
                        <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 group-hover:bg-slate-300 dark:group-hover:bg-slate-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <span x-show="!sidebarCollapsed" class="text-[11px] font-medium">Articles</span>
                    </a>
                </div>
            </div>

            <div>
                <p x-show="!sidebarCollapsed" class="px-2 mb-1.5 text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">System</p>
                {{-- Settings with 3 levels --}}
                <div>
                    <button @click="sidebarCollapsed ? null : toggleMenu('settings')" class="w-full group flex items-center rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700/50 transition-all duration-200" :class="sidebarCollapsed ? 'justify-center p-2 tooltip' : 'gap-2 px-2 py-1.5'" data-tip="Settings">
                        <div class="flex-shrink-0 w-7 h-7 rounded-md flex items-center justify-center bg-slate-200/80 dark:bg-slate-700 text-slate-600 dark:text-slate-300 group-hover:bg-slate-300 dark:group-hover:bg-slate-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span x-show="!sidebarCollapsed" class="flex-1 text-left text-[11px] font-medium">Settings</span>
                        <svg x-show="!sidebarCollapsed" class="w-3 h-3 transition-transform duration-200" :class="menuOpen.settings && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="menuOpen.settings && !sidebarCollapsed" x-transition x-collapse class="ml-9 mt-0.5 space-y-0.5" x-cloak>
                        <a href="#" class="block px-2 py-1 rounded-md text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50 text-[10px] transition-colors">Company</a>
                        <a href="#" class="block px-2 py-1 rounded-md text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50 text-[10px] transition-colors">Invoice Settings</a>
                        {{-- Level 3 --}}
                        <div>
                            <button @click="toggleMenu('email')" class="w-full flex items-center justify-between px-2 py-1 rounded-md text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50 text-[10px] transition-colors">
                                <span>Email</span>
                                <svg class="w-2.5 h-2.5 transition-transform duration-200" :class="menuOpen.email && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="menuOpen.email" x-transition x-collapse class="ml-2 mt-0.5 space-y-0.5 border-l border-slate-300 dark:border-slate-600 pl-2" x-cloak>
                                <a href="#" class="block px-1.5 py-0.5 rounded text-[9px] text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700/50 transition-colors">Templates</a>
                                <a href="#" class="block px-1.5 py-0.5 rounded text-[9px] text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700/50 transition-colors">Signatures</a>
                                <a href="#" class="block px-1.5 py-0.5 rounded text-[9px] text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-200/80 dark:hover:bg-slate-700/50 transition-colors">SMTP Config</a>
                            </div>
                        </div>
                        <a href="#" class="block px-2 py-1 rounded-md text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200/80 dark:hover:bg-slate-700/50 text-[10px] transition-colors">Currencies</a>
                    </div>
                </div>
            </div>
        </nav>

        {{-- User Profile --}}
        <div class="p-2 border-t border-slate-200/60 dark:border-slate-700/50">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center gap-2 p-1.5 rounded-lg hover:bg-slate-200/80 dark:hover:bg-slate-700/50 transition-colors" :class="sidebarCollapsed ? 'justify-center tooltip' : ''" data-tip="John Doe">
                    <x-invoice5.avatar name="John Doe" size="xs" />
                    <div x-show="!sidebarCollapsed" class="flex-1 text-left min-w-0">
                        <p class="text-[11px] font-semibold text-slate-700 dark:text-slate-200 truncate">John Doe</p>
                        <p class="text-[9px] text-slate-500 dark:text-slate-400">Administrator</p>
                    </div>
                    <svg x-show="!sidebarCollapsed" class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute bottom-full left-0 right-0 mb-1.5 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-slate-200 dark:border-slate-700 py-1 z-50" x-cloak>
                    <a href="#" class="flex items-center gap-2 px-2.5 py-1.5 text-[11px] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        My Profile
                    </a>
                    <a href="#" class="flex items-center gap-2 px-2.5 py-1.5 text-[11px] text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37"/></svg>
                        Settings
                    </a>
                    <div class="border-t border-slate-200 dark:border-slate-700 my-1"></div>
                    <a href="#" class="flex items-center gap-2 px-2.5 py-1.5 text-[11px] text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sign Out
                    </a>
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
            <a href="/templates/invoice5" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-500 via-purple-500 to-fuchsia-500 flex items-center justify-center shadow-lg shadow-violet-500/30">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="font-bold text-lg bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 bg-clip-text text-transparent">InvoicePro</span>
            </a>
            <button @click="mobileMenuOpen = false" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto custom-scrollbar py-4 px-3">
            <a href="/templates/invoice5" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-violet-500/10 to-fuchsia-500/10 text-violet-700 dark:text-violet-300 font-medium mb-2">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-fuchsia-600 flex items-center justify-center text-white shadow-lg shadow-violet-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span class="text-sm">Invoices</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-700/50 mb-2">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-sm">Clients</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100/80 dark:hover:bg-slate-700/50 mb-2">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37"/></svg>
                <span class="text-sm">Settings</span>
            </a>
        </nav>
        <div class="p-4 border-t border-slate-200/60 dark:border-slate-700/50">
            <div class="flex items-center gap-3 p-2">
                <x-invoice5.avatar name="John Doe" size="md" />
                <div class="flex-1">
                    <p class="text-sm font-semibold text-slate-800 dark:text-white">John Doe</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Administrator</p>
                </div>
            </div>
        </div>
    </div>
</aside>
