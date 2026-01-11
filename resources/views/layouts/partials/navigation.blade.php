<nav class="bg-white border-b border-neutral-200 sticky top-0 z-40">
    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ isset($currentCompany) ? route('app.dashboard', $currentCompany) : route('app.company.select') }}" class="text-xl font-bold text-neutral-900">
                        Invoice App
                    </a>
                </div>

                @isset($currentCompany)
                <div class="hidden lg:ml-8 lg:flex lg:space-x-1">
                    <a href="{{ route('app.dashboard', $currentCompany) }}" 
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('app.dashboard') ? 'text-neutral-900 bg-neutral-100' : 'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('app.invoices.index', $currentCompany) }}" 
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('app.invoices.*') ? 'text-neutral-900 bg-neutral-100' : 'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50' }}">
                        Invoices
                    </a>
                    <a href="{{ route('app.quotes.index', $currentCompany) }}" 
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('app.quotes.*') ? 'text-neutral-900 bg-neutral-100' : 'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50' }}">
                        Quotes
                    </a>
                    <a href="{{ route('app.proformas.index', $currentCompany) }}" 
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('app.proformas.*') ? 'text-neutral-900 bg-neutral-100' : 'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50' }}">
                        Proformas
                    </a>
                    <a href="{{ route('app.contracts.index', $currentCompany) }}" 
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('app.contracts.*') ? 'text-neutral-900 bg-neutral-100' : 'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50' }}">
                        Contracts
                    </a>
                    <a href="{{ route('app.clients.index', $currentCompany) }}" 
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('app.clients.*') ? 'text-neutral-900 bg-neutral-100' : 'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50' }}">
                        Clients
                    </a>
                    <a href="{{ route('app.articles.index', $currentCompany) }}" 
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('app.articles.*') ? 'text-neutral-900 bg-neutral-100' : 'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50' }}">
                        Articles
                    </a>
                    
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('app.payments.*') || request()->routeIs('app.reports.*') || request()->routeIs('app.users.*') ? 'text-neutral-900 bg-neutral-100' : 'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50' }}">
                            More
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute left-0 mt-1 w-48 bg-white rounded-md shadow-lg border border-neutral-200 z-50">
                            <div class="py-1">
                                <a href="{{ route('app.payments.index', $currentCompany) }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">Payments</a>
                                <a href="{{ route('app.reports.index', $currentCompany) }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">Reports</a>
                                <a href="{{ route('app.users.index', $currentCompany) }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100">Users</a>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('app.settings.index', $currentCompany) }}" 
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('app.settings.*') ? 'text-neutral-900 bg-neutral-100' : 'text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </a>
                </div>
                @endisset
            </div>

            <div class="flex items-center space-x-3">
                @isset($currentCompany)
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-neutral-700 bg-neutral-100 rounded-md hover:bg-neutral-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span class="hidden sm:inline max-w-[120px] truncate">{{ $currentCompany->name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg border border-neutral-200 z-50">
                        <div class="px-3 py-2 border-b border-neutral-100">
                            <p class="text-xs font-medium text-neutral-500 uppercase">Switch Company</p>
                        </div>
                        <div class="py-1 max-h-64 overflow-y-auto">
                            @foreach(auth()->user()->companies as $company)
                                <form action="{{ route('app.company.switch', $company) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-100 {{ $currentCompany->id === $company->id ? 'bg-neutral-50' : '' }}">
                                        @if($currentCompany->id === $company->id)
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @else
                                            <span class="w-4 h-4 mr-2"></span>
                                        @endif
                                        {{ $company->name }}
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endisset

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-full hover:bg-neutral-100 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-neutral-200 flex items-center justify-center text-sm font-medium text-neutral-600">
                            {{ substr(auth()->user()->first_name ?? auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg border border-neutral-200 z-50">
                        <div class="px-3 py-2 border-b border-neutral-100">
                            <p class="text-sm font-medium text-neutral-900">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                            <p class="text-xs text-neutral-500">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="py-1">
                            <form action="{{ route('app.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Log out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <button x-data @click="$dispatch('open-mobile-menu')" class="lg:hidden p-2 rounded-md text-neutral-600 hover:bg-neutral-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>
    
    @isset($currentCompany)
    <div x-data="{ open: false }" @open-mobile-menu.window="open = true" class="lg:hidden">
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 bg-black/20 z-40" x-cloak></div>
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed right-0 top-0 bottom-0 w-64 bg-white shadow-xl z-50 overflow-y-auto" x-cloak>
            <div class="p-4 border-b flex items-center justify-between">
                <span class="font-semibold text-neutral-900">Menu</span>
                <button @click="open = false" class="p-1 rounded hover:bg-neutral-100"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-2 space-y-1">
                <a href="{{ route('app.dashboard', $currentCompany) }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('app.dashboard') ? 'bg-neutral-100 text-neutral-900' : 'text-neutral-600' }}">Dashboard</a>
                <a href="{{ route('app.invoices.index', $currentCompany) }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('app.invoices.*') ? 'bg-neutral-100 text-neutral-900' : 'text-neutral-600' }}">Invoices</a>
                <a href="{{ route('app.quotes.index', $currentCompany) }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('app.quotes.*') ? 'bg-neutral-100 text-neutral-900' : 'text-neutral-600' }}">Quotes</a>
                <a href="{{ route('app.proformas.index', $currentCompany) }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('app.proformas.*') ? 'bg-neutral-100 text-neutral-900' : 'text-neutral-600' }}">Proformas</a>
                <a href="{{ route('app.contracts.index', $currentCompany) }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('app.contracts.*') ? 'bg-neutral-100 text-neutral-900' : 'text-neutral-600' }}">Contracts</a>
                <a href="{{ route('app.clients.index', $currentCompany) }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('app.clients.*') ? 'bg-neutral-100 text-neutral-900' : 'text-neutral-600' }}">Clients</a>
                <a href="{{ route('app.articles.index', $currentCompany) }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('app.articles.*') ? 'bg-neutral-100 text-neutral-900' : 'text-neutral-600' }}">Articles</a>
                <a href="{{ route('app.payments.index', $currentCompany) }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('app.payments.*') ? 'bg-neutral-100 text-neutral-900' : 'text-neutral-600' }}">Payments</a>
                <a href="{{ route('app.reports.index', $currentCompany) }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('app.reports.*') ? 'bg-neutral-100 text-neutral-900' : 'text-neutral-600' }}">Reports</a>
                <a href="{{ route('app.users.index', $currentCompany) }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('app.users.*') ? 'bg-neutral-100 text-neutral-900' : 'text-neutral-600' }}">Users</a>
                <a href="{{ route('app.settings.index', $currentCompany) }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('app.settings.*') ? 'bg-neutral-100 text-neutral-900' : 'text-neutral-600' }}">Settings</a>
            </div>
        </div>
    </div>
    @endisset
</nav>
