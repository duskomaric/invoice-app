@extends('layouts.app')

@section('title', 'Reports - App')
@section('page-title', 'Reports & Analytics')
@section('page-subtitle', 'Analyze your business performance and financial data')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    {{-- Financial Reports --}}
    <x-app.card class="hover:border-violet-200 transition-colors cursor-pointer group" onclick="window.location='{{ route('app.reports.income-book', $company) }}'">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-violet-50 dark:bg-violet-500/10 flex items-center justify-center text-violet-600 dark:text-violet-400 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <x-app.badge variant="secondary" size="xs">Taxation</x-app.badge>
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Income Book</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Record of issued invoices and earned income (Knjiga Prihoda)</p>
        <div class="flex items-center text-xs font-bold text-violet-600 dark:text-violet-400">
            <span>View Report</span>
            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </div>
    </x-app.card>

    {{-- Clients Report --}}
    <x-app.card class="opacity-75 relative overflow-hidden group">
        <div class="absolute inset-0 bg-slate-50/50 dark:bg-slate-900/50 backdrop-blur-[1px] z-10 flex items-center justify-center">
            <x-app.badge variant="secondary" size="sm" class="rotate-[-10deg]">Coming Soon</x-app.badge>
        </div>
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Client Ranking</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Analyze your most valuable clients based on revenue and volume</p>
    </x-app.card>

    {{-- Articles Report --}}
    <x-app.card class="opacity-75 relative overflow-hidden group">
        <div class="absolute inset-0 bg-slate-50/50 dark:bg-slate-900/50 backdrop-blur-[1px] z-10 flex items-center justify-center">
            <x-app.badge variant="secondary" size="sm" class="rotate-[-10deg]">Coming Soon</x-app.badge>
        </div>
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 dark:bg-orange-500/10 flex items-center justify-center text-orange-600 dark:text-orange-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Product Performance</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Identify top selling products and services and their margins</p>
    </x-app.card>
</div>
@endsection
