<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices - Template 1</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .content-transition { transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .fade-slide-enter { animation: fadeSlideIn 0.3s ease-out; }
        @keyframes fadeSlideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .glass-effect { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
    </style>
</head>
<body class="h-full bg-slate-50" x-data="{ sidebarOpen: window.innerWidth >= 1024, sidebarCollapsed: false, mobileMenuOpen: false }" @resize.window="sidebarOpen = window.innerWidth >= 1024">
    @php
        $invoices = [
            ['id' => 'INV-2024-001', 'client' => 'Acme Corporation', 'amount' => 2450.00, 'currency' => 'EUR', 'status' => 'paid', 'date' => '2024-01-15'],
            ['id' => 'INV-2024-002', 'client' => 'TechStart Inc', 'amount' => 1890.50, 'currency' => 'USD', 'status' => 'pending', 'date' => '2024-01-18'],
            ['id' => 'INV-2024-003', 'client' => 'Global Services', 'amount' => 5200.00, 'currency' => 'EUR', 'status' => 'overdue', 'date' => '2024-01-10'],
            ['id' => 'INV-2024-004', 'client' => 'Design Studio', 'amount' => 750.00, 'currency' => 'USD', 'status' => 'draft', 'date' => '2024-01-20'],
            ['id' => 'INV-2024-005', 'client' => 'Marketing Pro', 'amount' => 3100.00, 'currency' => 'EUR', 'status' => 'paid', 'date' => '2024-01-12'],
        ];
        $statusColors = [
            'paid' => 'bg-emerald-100 text-emerald-700',
            'pending' => 'bg-amber-100 text-amber-700',
            'overdue' => 'bg-red-100 text-red-700',
            'draft' => 'bg-slate-100 text-slate-600',
        ];
        $navItems = [
            ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard', 'route' => '#'],
            ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Invoices', 'route' => '#', 'active' => true],
            ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Quotes', 'route' => '#'],
            ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Clients', 'route' => '#'],
            ['icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'label' => 'Articles', 'route' => '#'],
            ['icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Payments', 'route' => '#'],
        ];
    @endphp

    <div class="min-h-full flex">
        {{-- Desktop Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 hidden lg:flex flex-col sidebar-transition glass-effect border-r border-slate-200/60 shadow-xl shadow-slate-200/20" :class="sidebarCollapsed ? 'w-20' : 'w-72'">
            <div class="h-16 flex items-center justify-between px-4 border-b border-slate-200/60">
                <a href="#" class="flex items-center gap-3" :class="sidebarCollapsed && 'justify-center w-full'">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition class="font-bold text-xl bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">InvoiceX</span>
                </a>
                <button @click="sidebarCollapsed = !sidebarCollapsed" class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors" x-show="!sidebarCollapsed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto py-6 px-3">
                <div class="space-y-1">
                    @foreach($navItems as $item)
                        <a href="{{ $item['route'] }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ isset($item['active']) ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}" :class="sidebarCollapsed && 'justify-center'">
                            <svg class="w-5 h-5 flex-shrink-0 {{ isset($item['active']) ? '' : 'group-hover:scale-110' }} transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                            <span x-show="!sidebarCollapsed" x-transition class="font-medium">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </nav>
            <div class="p-4 border-t border-slate-200/60">
                <div class="flex items-center gap-3" :class="sidebarCollapsed && 'justify-center'">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-cyan-500 flex items-center justify-center text-white font-semibold shadow-lg">JD</div>
                    <div x-show="!sidebarCollapsed" x-transition>
                        <p class="font-medium text-slate-900">John Doe</p>
                        <p class="text-sm text-slate-500">Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Mobile Sidebar Overlay --}}
        <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden" x-cloak></div>

        {{-- Mobile Sidebar --}}
        <aside x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-72 glass-effect border-r border-slate-200/60 shadow-2xl lg:hidden flex flex-col" x-cloak>
            <div class="h-16 flex items-center justify-between px-4 border-b border-slate-200/60">
                <a href="#" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="font-bold text-xl bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">InvoiceX</span>
                </a>
                <button @click="mobileMenuOpen = false" class="p-2 rounded-lg hover:bg-slate-100 text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto py-6 px-3">
                <div class="space-y-1">
                    @foreach($navItems as $item)
                        <a href="{{ $item['route'] }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ isset($item['active']) ? 'bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                            <span class="font-medium">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </nav>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 content-transition pb-24 lg:pb-0" :class="sidebarCollapsed ? 'lg:ml-20' : 'lg:ml-72'">
            <header class="sticky top-0 z-30 h-16 glass-effect border-b border-slate-200/60 flex items-center justify-between px-4 lg:px-8">
                <div class="flex items-center gap-4">
                    <button @click="mobileMenuOpen = true" class="p-2 rounded-lg hover:bg-slate-100 text-slate-600 lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:block p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors" x-show="sidebarCollapsed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                    </button>
                    <h1 class="text-lg font-semibold text-slate-900">Invoices</h1>
                </div>
                <div class="flex items-center gap-3">
                    <button class="p-2 rounded-lg hover:bg-slate-100 text-slate-500 relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>
            </header>

            <div class="p-4 lg:p-8 fade-slide-enter">
                {{-- Stats Cards --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-2xl p-5 shadow-sm shadow-slate-200/50 border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">+12%</span>
                        </div>
                        <p class="text-2xl font-bold text-slate-900">24</p>
                        <p class="text-sm text-slate-500">Total Invoices</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-sm shadow-slate-200/50 border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-2xl font-bold text-slate-900">€12,450</p>
                        <p class="text-sm text-slate-500">Paid</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-sm shadow-slate-200/50 border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-2xl font-bold text-slate-900">€4,890</p>
                        <p class="text-sm text-slate-500">Pending</p>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-sm shadow-slate-200/50 border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <p class="text-2xl font-bold text-slate-900">€5,200</p>
                        <p class="text-sm text-slate-500">Overdue</p>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="bg-white rounded-2xl shadow-sm shadow-slate-200/50 border border-slate-100 mb-6">
                    <div class="p-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" placeholder="Search invoices..." class="w-full sm:w-64 h-10 pl-10 pr-4 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm">
                            </div>
                            <select class="h-10 px-4 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-sm text-slate-600 bg-white">
                                <option>All Status</option>
                                <option>Paid</option>
                                <option>Pending</option>
                                <option>Overdue</option>
                                <option>Draft</option>
                            </select>
                        </div>
                        <a href="#" class="hidden lg:inline-flex items-center justify-center gap-2 h-10 px-5 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-medium shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            New Invoice
                        </a>
                    </div>
                </div>

                {{-- Invoice Table --}}
                <div class="bg-white rounded-2xl shadow-sm shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-100">
                                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Invoice</th>
                                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Client</th>
                                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Amount</th>
                                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Status</th>
                                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Date</th>
                                    <th class="text-right text-xs font-semibold text-slate-500 uppercase tracking-wider px-6 py-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($invoices as $invoice)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-6 py-4"><span class="font-semibold text-slate-900">{{ $invoice['id'] }}</span></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-xs font-semibold text-slate-600">{{ substr($invoice['client'], 0, 2) }}</div>
                                                <span class="text-slate-700">{{ $invoice['client'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4"><span class="font-semibold text-slate-900">{{ $invoice['currency'] === 'EUR' ? '€' : '$' }}{{ number_format($invoice['amount'], 2) }}</span></td>
                                        <td class="px-6 py-4"><span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$invoice['status']] }}">{{ ucfirst($invoice['status']) }}</span></td>
                                        <td class="px-6 py-4 text-slate-500">{{ \Carbon\Carbon::parse($invoice['date'])->format('M d, Y') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
                                                <button class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                <button class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Mobile Cards --}}
                    <div class="lg:hidden divide-y divide-slate-100">
                        @foreach($invoices as $invoice)
                            <div class="p-4 hover:bg-slate-50/50 transition-colors">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $invoice['id'] }}</p>
                                        <p class="text-sm text-slate-500">{{ $invoice['client'] }}</p>
                                    </div>
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$invoice['status']] }}">{{ ucfirst($invoice['status']) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-lg font-bold text-slate-900">{{ $invoice['currency'] === 'EUR' ? '€' : '$' }}{{ number_format($invoice['amount'], 2) }}</p>
                                        <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($invoice['date'])->format('M d, Y') }}</p>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button class="p-2 rounded-lg hover:bg-slate-100 text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
                                        <button class="p-2 rounded-lg hover:bg-slate-100 text-slate-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Mobile Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 z-50 lg:hidden glass-effect border-t border-slate-200/60">
        <div class="flex items-center justify-around h-16 px-2">
            <a href="#" class="flex flex-col items-center justify-center flex-1 py-2 text-slate-400 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-xs mt-1">Home</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center flex-1 py-2 text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-xs mt-1 font-medium">Invoices</span>
            </a>
            <div class="relative flex items-center justify-center flex-1">
                <a href="#" class="absolute -top-6 w-14 h-14 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/40 hover:shadow-xl hover:scale-105 transition-all">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </a>
            </div>
            <a href="#" class="flex flex-col items-center justify-center flex-1 py-2 text-slate-400 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-xs mt-1">Clients</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center flex-1 py-2 text-slate-400 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span class="text-xs mt-1">Articles</span>
            </a>
        </div>
    </nav>
</body>
</html>
