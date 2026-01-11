
 <!DOCTYPE html>
 <html lang="en" class="h-full">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Invoice - Premium</title>
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
     </style>
 </head>
 <body class="h-full mesh-bg text-sm" x-data="invoicePage()" x-on:keydown.escape="closeAllDropdowns()">
     <div class="min-h-full">
         @include('app.invoice3.partials.sidebar')
         @include('app.invoice3.partials.content')
     </div>
     @include('app.invoice3.partials.mobile-nav')
     <script>
         function invoicePage() {
             return {
                 sidebarCollapsed: false,
                 mobileMenuOpen: false,
                 notificationsOpen: false,
                 userMenuOpen: false,
                 companyMenuOpen: false,
                 currentCompany: 'Acme Corporation',
                 companies: ['Acme Corporation', 'TechStart Inc', 'Design Studio'],
                 menuOpen: {
                     invoices: true,
                     quotes: false,
                     clients: false,
                     settings: false,
                     email: false,
                 },
                 filters: {
                     status: 'pending',
                     currencies: ['EUR'],
                     hasAttachments: true,
                 },
                 notifications: [
                     { id: 1, title: 'Invoice paid', time: '2 min ago', read: false },
                     { id: 2, title: 'New client', time: '1 hour ago', read: false },
                     { id: 3, title: 'Quote accepted', time: '3 hours ago', read: true },
                 ],
                 toggleSidebar() {
                     this.sidebarCollapsed = !this.sidebarCollapsed;
                 },
                 toggleMenu(menu) {
                     this.menuOpen[menu] = !this.menuOpen[menu];
                 },
                 toggleCurrency(curr) {
                     const idx = this.filters.currencies.indexOf(curr);
                     if (idx > -1) this.filters.currencies.splice(idx, 1);
                     else this.filters.currencies.push(curr);
                 },
                 clearFilters() {
                     this.filters = { status: '', currencies: [], hasAttachments: false };
                 },
                 switchCompany(company) {
                     this.currentCompany = company;
                     this.companyMenuOpen = false;
                 },
                 markAllRead() {
                     this.notifications = this.notifications.map(n => ({ ...n, read: true }));
                 },
                 unreadCount() {
                     return this.notifications.filter(n => !n.read).length;
                 },
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

