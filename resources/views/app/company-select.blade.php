<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 dark:bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Select Company - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased text-slate-900 dark:text-slate-100">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        {{-- Background Decorations --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-violet-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-fuchsia-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10 text-center">
            <div class="flex justify-center mb-8">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-600 to-fuchsia-600 flex items-center justify-center shadow-xl shadow-violet-500/20 transform hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Select Company</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Choose a workspace to continue</p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-xl relative z-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-4 sm:px-0">
                @foreach($companies as $company)
                    <a href="{{ route('app.dashboard', $company) }}" class="group bg-white dark:bg-slate-900/50 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 hover:border-violet-500 dark:hover:border-violet-500 hover:shadow-2xl hover:shadow-violet-500/10 transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl font-black text-slate-400 dark:text-slate-600 group-hover:bg-violet-100 dark:group-hover:bg-violet-900/30 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">
                                {{ substr($company->name, 0, 1) }}
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-slate-900 dark:text-white group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">{{ $company->name }}</h3>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $company->city ?? 'Workspace' }}</p>
                        </div>
                    </a>
                @endforeach

                <a href="#" class="group bg-slate-50 dark:bg-slate-900/20 p-6 rounded-3xl border border-dashed border-slate-300 dark:border-slate-800 hover:border-violet-500 dark:hover:border-violet-500 transition-all duration-300">
                    <div class="flex flex-col items-center justify-center h-full text-center">
                        <div class="w-12 h-12 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center group-hover:bg-violet-600 group-hover:text-white transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <p class="mt-4 text-sm font-bold text-slate-600 dark:text-slate-400 group-hover:text-violet-600 dark:group-hover:text-violet-400">Add Company</p>
                    </div>
                </a>
            </div>

            <div class="mt-12 text-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 transition-colors flex items-center justify-center gap-2 mx-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
