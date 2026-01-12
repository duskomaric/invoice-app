<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 dark:bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'InvoiceApp') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        
        .auth-gradient {
            background: radial-gradient(circle at 0% 0%, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 100% 100%, rgba(236, 72, 153, 0.15) 0%, transparent 50%);
        }
    </style>
</head>
<body class="h-full antialiased dark:text-slate-200">
    <div class="h-full flex flex-col md:flex-row min-h-screen auth-gradient">
        <!-- Left Side: Auth Form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div>
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-violet-500/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">PlusPlus<span class="text-violet-600">I</span></h1>
                    </div>
                </div>

                @yield('content')
            </div>
        </div>

        <!-- Right Side: Decorative/Branding -->
        <div class="hidden lg:block relative flex-1 bg-slate-900 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-600/20 to-indigo-900/40 mix-blend-multiply"></div>
            <img class="absolute inset-0 h-full w-full object-cover opacity-50" src="https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1567&q=80" alt="Workspace">
            
            <div class="absolute inset-0 flex flex-col justify-center p-12 text-white">
                <div class="max-w-md">
                    <h2 class="text-4xl font-black mb-6 leading-tight">Professional Invoicing <br/>for Modern Teams</h2>
                    <p class="text-lg text-slate-300 mb-10 leading-relaxed font-medium">Streamline your business with automated tracking, professional templates, and seamless client management.</p>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="p-4 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10">
                            <div class="text-2xl font-bold mb-1">99.9%</div>
                            <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Reliability</div>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10">
                            <div class="text-2xl font-bold mb-1">24/7</div>
                            <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Expert Support</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Animated decorative circles -->
            <div class="absolute top-1/4 -right-20 w-64 h-64 bg-violet-500/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 -left-20 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s"></div>
        </div>
    </div>
</body>
</html>
