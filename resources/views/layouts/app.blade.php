<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="darkMode && 'dark'">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'InvoicePro - Modern Invoice Management')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        
        .bg-mesh {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(139, 92, 246, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(236, 72, 153, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(34, 211, 238, 0.06) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(168, 85, 247, 0.06) 0px, transparent 50%);
        }
        .dark .bg-mesh {
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(236, 72, 153, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(34, 211, 238, 0.08) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(168, 85, 247, 0.08) 0px, transparent 50%);
        }
        
        .bg-grid {
            background-size: 60px 60px;
            background-image: 
                linear-gradient(to right, rgba(148, 163, 184, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(148, 163, 184, 0.05) 1px, transparent 1px);
        }
        .dark .bg-grid {
            background-image: 
                linear-gradient(to right, rgba(148, 163, 184, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(148, 163, 184, 0.03) 1px, transparent 1px);
        }
        
        .floating-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            pointer-events: none;
            animation: float 20s ease-in-out infinite;
        }
        .floating-orb-1 {
            width: 400px; height: 400px;
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            top: -100px; right: -100px;
            animation-delay: 0s;
        }
        .floating-orb-2 {
            width: 300px; height: 300px;
            background: linear-gradient(135deg, #06b6d4, #8b5cf6);
            bottom: -50px; left: -50px;
            animation-delay: -7s;
        }
        .floating-orb-3 {
            width: 250px; height: 250px;
            background: linear-gradient(135deg, #f59e0b, #ec4899);
            top: 50%; right: 20%;
            animation-delay: -14s;
        }
        .dark .floating-orb { opacity: 0.2; }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -30px) scale(1.05); }
            50% { transform: translate(-20px, 20px) scale(0.95); }
            75% { transform: translate(-30px, -20px) scale(1.02); }
        }
        
        .nav-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        .dark .nav-box {
            background: #1e293b;
            border: 1px solid #334155;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }
        
        .sidebar-enter { animation: sidebarEnter 0.2s ease-out forwards; }
        @keyframes sidebarEnter {
            from { width: 4.5rem; }
            to { width: 16rem; }
        }
        
        .tooltip { position: relative; }
        .tooltip::after {
            content: attr(data-tip);
            position: absolute;
            left: calc(100% + 8px);
            top: 50%;
            transform: translateY(-50%);
            background: #1e293b;
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            z-index: 100;
        }
        .dark .tooltip::after { background: #f1f5f9; color: #1e293b; }
        .tooltip:hover::after { opacity: 1; }
        
        .page-enter { animation: pageEnter 0.25s ease-out forwards; }
        @keyframes pageEnter {
            from { opacity: 0; transform: translateX(10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .stagger-1 { animation-delay: 0.05s; }
        .stagger-2 { animation-delay: 0.1s; }
        .stagger-3 { animation-delay: 0.15s; }
        .stagger-4 { animation-delay: 0.2s; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { 
            background: linear-gradient(180deg, #a78bfa, #c084fc);
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: linear-gradient(180deg, #8b5cf6, #a855f7); }
        
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: white !important; }
        }
    </style>
</head>
<body class="bg-mesh bg-grid min-h-screen antialiased" x-data="appState()">
    <div class="floating-orb floating-orb-1 no-print"></div>
    <div class="floating-orb floating-orb-2 no-print"></div>
    <div class="floating-orb floating-orb-3 no-print"></div>
    
    <div class="relative min-h-screen flex">
        @include('app.partials.sidebar')
        
        <div class="flex-1 flex flex-col min-h-screen transition-all duration-300" :class="sidebarCollapsed ? 'lg:ml-[4.5rem]' : 'lg:ml-64'">
            @include('app.partials.header')
            
            <main class="flex-1 p-3 lg:p-4">
                <div class="page-enter">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    @include('app.partials.mobile-nav')
    
    <x-app.toast />

    @stack('scripts')
    <script>
    function appState() {
        return {
            sidebarCollapsed: false,
            sidebarAnimating: true,
            mobileMenuOpen: false,
            notificationsOpen: false,
            menuOpen: { 
                invoices: true, 
                quotes: false, 
                clients: false, 
                settings: false,
                email: false 
            },
            filters: {
                status: '',
                currencies: [],
                hasAttachments: false
            },
            currentCompany: 'Acme Corp',
            companies: [
                { id: 1, name: 'Acme Corp' },
                { id: 2, name: 'TechStart Inc' },
                { id: 3, name: 'Design Studio' }
            ],
            init() {
                this.$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val));
                const savedState = localStorage.getItem('sidebarCollapsed') === 'true';
                if (savedState) {
                    setTimeout(() => { this.sidebarCollapsed = true; this.sidebarAnimating = false; }, 400);
                } else {
                    setTimeout(() => { this.sidebarAnimating = false; }, 400);
                }
            },
            toggleMenu(menu) {
                this.menuOpen[menu] = !this.menuOpen[menu];
            },
            switchCompany(company) {
                this.currentCompany = company.name;
            },
            toggleCurrency(curr) {
                const idx = this.filters.currencies.indexOf(curr);
                if (idx > -1) this.filters.currencies.splice(idx, 1);
                else this.filters.currencies.push(curr);
            },
            clearFilters() {
                this.filters = { status: '', currencies: [], hasAttachments: false };
            }
        }
    }
    </script>
</body>
</html>
