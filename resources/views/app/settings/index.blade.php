@extends('layouts.app')

@section('title', 'Settings - App')
@section('page-title', 'Settings')
@section('page-subtitle', 'Configure your business preferences and document settings')

@section('content')
<x-app.settings-layout :company="$company" active="general">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Summary --}}
        <x-app.card class="lg:col-span-1">
            <x-app.section-header title="Current Company" icon="building-office" variant="primary" />
            <div class="mt-4 flex flex-col items-center text-center p-4">
                <x-app.avatar :name="$company->name" size="xl" class="mb-4" />
                <h3 class="text-xl font-black text-slate-900 dark:text-white">{{ $company->name }}</h3>
                <p class="text-xs text-slate-500 font-medium italic mb-6">{{ $company->email ?? 'No email set' }}</p>
                
                <div class="w-full grid grid-cols-2 gap-2">
                    <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status</div>
                        <x-app.status-badge status="Active" variant="emerald" />
                    </div>
                    <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Plan</div>
                        <x-app.badge variant="primary" size="xs">PRO</x-app.badge>
                    </div>
                </div>
            </div>
        </x-app.card>

        {{-- Quick Configuration --}}
        <div class="lg:col-span-2 space-y-4">
            <x-app.card>
                <x-app.section-header title="General Preferences" subtitle="Global application behavior" icon="adjustments-horizontal" variant="secondary" />
                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-900/50 ring-1 ring-slate-100 dark:ring-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-900 dark:text-white">Dark Mode</div>
                                <div class="text-[10px] text-slate-500">Toggle between light and dark themes</div>
                            </div>
                        </div>
                        <button onclick="document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light')" class="w-12 h-6 rounded-full bg-slate-200 dark:bg-violet-600 relative transition-colors">
                            <div class="absolute top-1 left-1 dark:left-7 w-4 h-4 bg-white rounded-full transition-all"></div>
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-900/50 ring-1 ring-slate-100 dark:ring-slate-800 opacity-60">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-900 dark:text-white">Email Notifications</div>
                                <div class="text-[10px] text-slate-500">Enable automated client notifications</div>
                            </div>
                        </div>
                        <x-app.badge variant="secondary" size="xs">Coming Soon</x-app.badge>
                    </div>
                </div>
            </x-app.card>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-app.card class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer" onclick="window.location='{{ route('app.settings.company', $company) }}'">
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Company Profile</h4>
                    <p class="text-[10px] text-slate-500">Update logo, address, and VAT details</p>
                </x-app.card>
                <x-app.card class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer" onclick="window.location='{{ route('app.settings.invoice', $company) }}'">
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Invoicing Prefs</h4>
                    <p class="text-[10px] text-slate-500">Sequence resets, prefixes, and defaults</p>
                </x-app.card>
            </div>
        </div>
    </div>
</x-app.settings-layout>
@endsection
