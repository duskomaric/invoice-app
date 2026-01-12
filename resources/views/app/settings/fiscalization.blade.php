@extends('layouts.app')

@section('title', 'Fiscalization - ' . $company->name)
@section('page-title', 'Fiscalization')
@section('page-subtitle', 'Configure digital certificates and fiscal parameters')

@section('content')
<x-app.settings-layout :company="$company" active="fiscal">
    <x-app.card>
        <x-app.section-header title="Fiscal Settings" subtitle="Configure your digital certificates and fiscal parameters" icon="ticket" variant="primary" />
        
        <div class="mt-6 space-y-6">
            <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/30 flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-800/50 flex items-center justify-center text-amber-600 dark:text-amber-400 flex-shrink-0">
                    <x-heroicon-o-information-circle class="w-6 h-6" />
                </div>
                <div>
                    <h4 class="text-sm font-bold text-amber-900 dark:text-amber-200">Fiscalization Status</h4>
                    <p class="text-xs text-amber-700 dark:text-amber-400 mt-1">Fiscalization is currently not configured for this company. You need to upload a digital certificate and define at least one business premise to start issuing fiscalized invoices.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest px-1">Certificate</h3>
                    <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 border-dashed flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-slate-300 mb-3">
                            <x-heroicon-o-key class="w-6 h-6" />
                        </div>
                        <p class="text-[11px] font-bold text-slate-600 dark:text-slate-400">No certificate uploaded</p>
                        <x-app.button variant="secondary" size="xs" class="mt-4">Upload .pfx or .p12</x-app.button>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest px-1">Business Premises</h3>
                    <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 border-dashed flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-slate-300 mb-3">
                            <x-heroicon-o-building-storefront class="w-6 h-6" />
                        </div>
                        <p class="text-[11px] font-bold text-slate-600 dark:text-slate-400">No premises defined</p>
                        <x-app.button variant="secondary" size="xs" class="mt-4">Add Premise</x-app.button>
                    </div>
                </div>
            </div>
        </div>
    </x-app.card>
</x-app.settings-layout>
@endsection
