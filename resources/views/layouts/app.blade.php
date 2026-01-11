<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Invoice App') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .gradient-bg { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%); }
        .sidebar-glass { background: rgba(255,255,255,0.98); backdrop-filter: blur(20px); }
        .card-glass { background: rgba(255,255,255,0.9); backdrop-filter: blur(12px); }
        .mesh-bg { background: linear-gradient(135deg, #faf5ff 0%, #fdf2f8 50%, #f0f9ff 100%); }
        .sidebar-transition { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .content-transition { transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .menu-item { transition: all 0.15s ease; }
        .menu-item:hover { background: rgba(139, 92, 246, 0.08); }
        .text-gradient { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(139, 92, 246, 0.2); border-radius: 4px; }
        .dropdown-enter { animation: dropdownIn 0.15s ease-out; }
        @keyframes dropdownIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    </style>
</head>
<body class="h-full mesh-bg text-sm font-sans antialiased" x-data="appLayout()" x-on:keydown.escape="closeAllDropdowns()">
    <div class="min-h-full flex">
        {{-- Desktop Sidebar --}}
        <aside class="hidden lg:flex flex-col sidebar-glass border-r border-slate-200/60 shadow-xl sidebar-transition flex-shrink-0"
            :class="sidebarCollapsed ? 'w-[72px]' : 'w-[260px]'">
            
            {{-- Logo & Collapse --}}
            <div class="h-14 flex items-center justify-between px-4 border-b border-slate-200/60">
                <a href="{{ isset($currentCompany) ? route('app.dashboard', $currentCompany) : '#' }}" class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-8 h-8 rounded-lg gradient-bg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition class="font-bold text-base text-gradient whitespace-nowrap">InvoicePro</span>
                </a>
                <button x-on:click="sidebarCollapsed = !sidebarCollapsed" class="p-2 rounded-lg hover:bg-violet-50 text-slate-400 hover:text-violet-600 transition-colors" :class="sidebarCollapsed && 'mx-auto'">
                    <svg x-show="!sidebarCollapsed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                    <svg x-show="sidebarCollapsed" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                </button>
            </div>

            @isset($currentCompany)
            {{-- Company Switcher --}}
            <div class="px-3 py-3 border-b border-slate-200/60" x-show="!sidebarCollapsed">
                <div class="relative">
                    <button x-on:click="companyMenuOpen = !companyMenuOpen" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors">
                        <div class="w-7 h-7 rounded-md gradient-bg flex items-center justify-center text-white text-xs font-bold flex-shrink-0">{{ strtoupper(substr($currentCompany->name, 0, 2)) }}</div>
                        <div class="flex-1 text-left min-w-0">
                            <p class="text-xs font-semibold text-slate-700 truncate">{{ $currentCompany->name }}</p>
                            <p class="text-[10px] text-slate-400">Switch company</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="companyMenuOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="companyMenuOpen" x-on:click.away="companyMenuOpen = false" x-transition class="absolute left-0 right-0 mt-1 bg-white rounded-lg shadow-xl border border-slate-100 py-1 z-50 dropdown-enter" x-cloak>
                        @foreach(auth()->user()->companies as $company)
                            <form action="{{ route('app.company.switch', $company) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-3 py-2 text-left text-xs hover:bg-slate-50 flex items-center gap-2 {{ $currentCompany->id === $company->id ? 'bg-violet-50 text-violet-700' : '' }}">
                                    <div class="w-6 h-6 rounded-md flex items-center justify-center text-[10px] font-bold {{ $currentCompany->id === $company->id ? 'gradient-bg text-white' : 'bg-slate-200' }}">{{ strtoupper(substr($company->name, 0, 2)) }}</div>
                                    <span>{{ $company->name }}</span>
                                    @if($currentCompany->id === $company->id)
                                        <svg class="w-4 h-4 ml-auto text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
            @endisset

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto custom-scrollbar py-3 px-2">
                @isset($currentCompany)
                {{-- Main --}}
                <div class="mb-4">
                    <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Main</p>
                    <a href="{{ route('app.dashboard', $currentCompany) }}" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('app.dashboard') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:text-slate-900' }}" :class="sidebarCollapsed && 'justify-center'">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('app.dashboard') ? 'text-violet-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">Dashboard</span>
                    </a>
                </div>

                {{-- Documents --}}
                <div class="mb-4">
                    <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Documents</p>
                    
                    <a href="{{ route('app.invoices.index', $currentCompany) }}" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('app.invoices.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:text-slate-900' }}" :class="sidebarCollapsed && 'justify-center'">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('app.invoices.*') ? 'text-violet-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">Invoices</span>
                    </a>

                    <a href="{{ route('app.quotes.index', $currentCompany) }}" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg mt-1 {{ request()->routeIs('app.quotes.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:text-slate-900' }}" :class="sidebarCollapsed && 'justify-center'">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('app.quotes.*') ? 'text-violet-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">Quotes</span>
                    </a>

                    <a href="{{ route('app.proformas.index', $currentCompany) }}" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg mt-1 {{ request()->routeIs('app.proformas.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:text-slate-900' }}" :class="sidebarCollapsed && 'justify-center'">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('app.proformas.*') ? 'text-violet-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">Proformas</span>
                    </a>

                    <a href="{{ route('app.contracts.index', $currentCompany) }}" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg mt-1 {{ request()->routeIs('app.contracts.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:text-slate-900' }}" :class="sidebarCollapsed && 'justify-center'">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('app.contracts.*') ? 'text-violet-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">Contracts</span>
                    </a>
                </div>

                {{-- Management --}}
                <div class="mb-4">
                    <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Management</p>
                    
                    <a href="{{ route('app.clients.index', $currentCompany) }}" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('app.clients.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:text-slate-900' }}" :class="sidebarCollapsed && 'justify-center'">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('app.clients.*') ? 'text-violet-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">Clients</span>
                    </a>

                    <a href="{{ route('app.articles.index', $currentCompany) }}" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg mt-1 {{ request()->routeIs('app.articles.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:text-slate-900' }}" :class="sidebarCollapsed && 'justify-center'">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('app.articles.*') ? 'text-violet-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">Articles</span>
                    </a>

                    <a href="{{ route('app.payments.index', $currentCompany) }}" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg mt-1 {{ request()->routeIs('app.payments.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:text-slate-900' }}" :class="sidebarCollapsed && 'justify-center'">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('app.payments.*') ? 'text-violet-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">Payments</span>
                    </a>

                    <a href="{{ route('app.reports.index', $currentCompany) }}" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg mt-1 {{ request()->routeIs('app.reports.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:text-slate-900' }}" :class="sidebarCollapsed && 'justify-center'">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('app.reports.*') ? 'text-violet-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">Reports</span>
                    </a>

                    <a href="{{ route('app.users.index', $currentCompany) }}" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg mt-1 {{ request()->routeIs('app.users.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:text-slate-900' }}" :class="sidebarCollapsed && 'justify-center'">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('app.users.*') ? 'text-violet-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">Users</span>
                    </a>
                </div>

                {{-- Settings --}}
                <div>
                    <p x-show="!sidebarCollapsed" class="px-3 mb-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Settings</p>
                    <a href="{{ route('app.settings.index', $currentCompany) }}" class="menu-item flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('app.settings.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600 hover:text-slate-900' }}" :class="sidebarCollapsed && 'justify-center'">
                        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('app.settings.*') ? 'text-violet-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span x-show="!sidebarCollapsed" class="font-medium">Settings</span>
                    </a>
                </div>
                @endisset
            </nav>

            {{-- User Menu --}}
            <div class="p-3 border-t border-slate-200/60">
                <div class="relative">
                    <button x-on:click="userMenuOpen = !userMenuOpen" class="w-full flex items-center gap-2.5 p-2 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors" :class="sidebarCollapsed && 'justify-center'">
                        <div class="w-8 h-8 rounded-full gradient-bg flex items-center justify-center text-white text-xs font-bold flex-shrink-0">{{ strtoupper(substr(auth()->user()->first_name ?? auth()->user()->name ?? 'U', 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name ?? '', 0, 1)) }}</div>
                        <div x-show="!sidebarCollapsed" class="flex-1 min-w-0 text-left">
                            <p class="text-xs font-semibold text-slate-700 truncate">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                            <p class="text-[10px] text-slate-400">{{ auth()->user()->email }}</p>
                        </div>
                        <svg x-show="!sidebarCollapsed" class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                    </button>
                    <div x-show="userMenuOpen" x-on:click.away="userMenuOpen = false" x-transition class="absolute bottom-full left-0 right-0 mb-1 bg-white rounded-lg shadow-xl border border-slate-100 py-1 z-50 dropdown-enter" x-cloak>
                        <a href="#" class="flex items-center gap-2 px-3 py-2 text-xs text-slate-600 hover:bg-slate-50">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            My Profile
                        </a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <form action="{{ route('app.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-rose-600 hover:bg-rose-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-h-screen">
            {{-- Header --}}
            <header class="sticky top-0 z-40 h-14 card-glass border-b border-slate-200/60 flex items-center justify-between px-4 lg:px-6">
                <div class="flex items-center gap-3">
                    <button x-on:click="mobileMenuOpen = true" class="p-1.5 rounded-md hover:bg-slate-100 text-slate-500 lg:hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="text-base font-semibold text-slate-800">{{ $pageTitle ?? 'Dashboard' }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Notifications --}}
                    <div class="relative">
                        <button x-on:click="notificationsOpen = !notificationsOpen" class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 relative transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </button>
                        <div x-show="notificationsOpen" x-on:click.away="notificationsOpen = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden z-50 dropdown-enter" x-cloak>
                            <div class="px-4 py-3 border-b border-slate-100">
                                <h3 class="font-semibold text-slate-800 text-sm">Notifications</h3>
                            </div>
                            <div class="p-4 text-center text-xs text-slate-500">
                                No new notifications
                            </div>
                        </div>
                    </div>
                    
                    @isset($headerActions)
                        {{ $headerActions }}
                    @endisset
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-4 lg:p-6">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

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
        @isset($currentCompany)
        <nav class="flex-1 overflow-y-auto custom-scrollbar p-3">
            <a href="{{ route('app.dashboard', $currentCompany) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('app.dashboard') ? 'bg-violet-50 text-violet-700' : 'text-slate-600' }} font-medium mb-1">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('app.invoices.index', $currentCompany) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('app.invoices.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600' }} font-medium mb-1">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Invoices
            </a>
            <a href="{{ route('app.quotes.index', $currentCompany) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('app.quotes.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600' }} font-medium mb-1">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Quotes
            </a>
            <a href="{{ route('app.clients.index', $currentCompany) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('app.clients.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600' }} font-medium mb-1">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Clients
            </a>
            <a href="{{ route('app.settings.index', $currentCompany) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg {{ request()->routeIs('app.settings.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600' }} font-medium mb-1">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </a>
        </nav>
        @endisset
    </aside>

    {{-- Mobile Bottom Nav --}}
    @isset($currentCompany)
    <nav class="fixed bottom-0 left-0 right-0 z-30 lg:hidden card-glass border-t border-slate-200/60 safe-area-inset-bottom">
        <div class="flex items-center justify-around h-14">
            <a href="{{ route('app.dashboard', $currentCompany) }}" class="flex flex-col items-center gap-0.5 px-3 py-1 {{ request()->routeIs('app.dashboard') ? 'text-violet-600' : 'text-slate-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[10px] font-medium">Home</span>
            </a>
            <a href="{{ route('app.invoices.index', $currentCompany) }}" class="flex flex-col items-center gap-0.5 px-3 py-1 {{ request()->routeIs('app.invoices.*') ? 'text-violet-600' : 'text-slate-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-[10px] font-medium">Invoices</span>
            </a>
            <a href="{{ route('app.invoices.create', $currentCompany) }}" class="flex flex-col items-center gap-0.5 px-3 py-1">
                <div class="w-10 h-10 -mt-5 rounded-full gradient-bg flex items-center justify-center shadow-lg shadow-violet-500/30">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
            </a>
            <a href="{{ route('app.clients.index', $currentCompany) }}" class="flex flex-col items-center gap-0.5 px-3 py-1 {{ request()->routeIs('app.clients.*') ? 'text-violet-600' : 'text-slate-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-[10px] font-medium">Clients</span>
            </a>
            <a href="{{ route('app.settings.index', $currentCompany) }}" class="flex flex-col items-center gap-0.5 px-3 py-1 {{ request()->routeIs('app.settings.*') ? 'text-violet-600' : 'text-slate-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-[10px] font-medium">Settings</span>
            </a>
        </div>
    </nav>
    @endisset

    @include('components.pines.toast')

    <script>
        function appLayout() {
            return {
                sidebarCollapsed: false,
                mobileMenuOpen: false,
                notificationsOpen: false,
                userMenuOpen: false,
                companyMenuOpen: false,
                closeAllDropdowns() {
                    this.mobileMenuOpen = false;
                    this.notificationsOpen = false;
                    this.userMenuOpen = false;
                    this.companyMenuOpen = false;
                },
            }
        }
    </script>
</body>
</html>
