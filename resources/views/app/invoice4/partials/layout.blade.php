<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'InvoicePro')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
        .toggle-switch { transition: background 0.2s ease; }
        .toggle-dot { transition: transform 0.2s ease; }
        .dropdown-enter { animation: dropdownIn 0.15s ease-out; }
        @keyframes dropdownIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .submenu-enter { animation: submenuIn 0.2s ease-out; }
        @keyframes submenuIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        .fab-shadow { box-shadow: 0 8px 24px -4px rgba(139, 92, 246, 0.5); }
        .mobile-nav-shadow { box-shadow: 0 -4px 24px -2px rgba(0, 0, 0, 0.08); }
        @media print { .no-print { display: none !important; } body { background: white !important; } }
    </style>
    @stack('styles')
</head>
<body class="h-full mesh-bg text-sm" x-data="invoiceApp()" @keydown.escape="closeAllDropdowns()">
    <div class="min-h-full">
        @include('app.invoice4.partials.sidebar')

        <main class="min-h-screen content-transition pb-20 lg:pb-0" :class="sidebarCollapsed ? 'lg:ml-[72px]' : 'lg:ml-[260px]'">
            @include('app.invoice4.partials.header')
            <div class="p-4 lg:p-6">
                @yield('content')
            </div>
        </main>
    </div>

    @include('app.invoice4.partials.mobile-nav')

    <script>
        function invoiceApp() {
            return {
                sidebarCollapsed: false,
                mobileMenuOpen: false,
                notificationsOpen: false,
                userMenuOpen: false,
                companyMenuOpen: false,
                currentCompany: 'Acme Corporation',
                companies: ['Acme Corporation', 'TechStart Inc', 'Design Studio'],
                menuOpen: { invoices: true, quotes: false, clients: false, settings: false, email: false },
                filters: { status: '', currencies: [], hasAttachments: false },
                toggleMenu(menu) { this.menuOpen[menu] = !this.menuOpen[menu]; },
                toggleCurrency(curr) {
                    const idx = this.filters.currencies.indexOf(curr);
                    if (idx > -1) this.filters.currencies.splice(idx, 1);
                    else this.filters.currencies.push(curr);
                },
                clearFilters() { this.filters = { status: '', currencies: [], hasAttachments: false }; },
                switchCompany(company) { this.currentCompany = company; this.companyMenuOpen = false; },
                closeAllDropdowns() { this.mobileMenuOpen = false; this.notificationsOpen = false; this.userMenuOpen = false; this.companyMenuOpen = false; }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
