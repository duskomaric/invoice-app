@extends('layouts.app')

@section('title', 'Appearance - ' . $company->name)
@section('page-title', 'Appearance')
@section('page-subtitle', 'Customize how your application looks')

@section('content')
<x-app.settings-layout :company="$company" active="appearance">
    <x-app.card>
        <x-app.section-header title="Appearance & Branding" subtitle="Customize how your application looks" icon="swatch" variant="secondary" />
        
        <form action="#" method="POST" class="mt-8 space-y-10">
            {{-- Logo Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Company Logo</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">This logo will appear on your invoices and in the application sidebar.</p>
                </div>
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 rounded-3xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center border-2 border-dashed border-slate-200 dark:border-slate-700">
                            <x-heroicon-o-photo class="w-8 h-8 text-slate-300" />
                        </div>
                        <div class="space-y-2">
                            <x-app.button type="button" variant="secondary" size="sm">Change Logo</x-app.button>
                            <p class="text-[10px] text-slate-400">PNG, JPG or SVG. Max 2MB.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="h-px bg-slate-100 dark:bg-slate-800"></div>

            {{-- Theme Selection --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Interface Theme</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Choose your preferred light or dark experience.</p>
                </div>
                <div class="lg:col-span-2 grid grid-cols-2 gap-4">
                    <button type="button" @click="darkMode = false" class="relative p-4 rounded-2xl border-2 transition-all text-left group" :class="!darkMode ? 'border-violet-500 bg-violet-50/50 dark:bg-violet-900/20' : 'border-slate-200 dark:border-slate-800 hover:border-slate-300'">
                        <div class="w-full aspect-video bg-white border border-slate-200 rounded-lg mb-3 shadow-sm overflow-hidden p-2">
                            <div class="w-full h-2 bg-slate-100 rounded mb-1"></div>
                            <div class="w-2/3 h-2 bg-slate-100 rounded"></div>
                        </div>
                        <p class="text-xs font-bold" :class="!darkMode ? 'text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-400'">Light Mode</p>
                        <div x-show="!darkMode" class="absolute top-3 right-3 text-violet-500">
                            <x-heroicon-s-check-circle class="w-5 h-5" />
                        </div>
                    </button>

                    <button type="button" @click="darkMode = true" class="relative p-4 rounded-2xl border-2 transition-all text-left group" :class="darkMode ? 'border-violet-500 bg-violet-50/50 dark:bg-violet-900/20' : 'border-slate-200 dark:border-slate-800 hover:border-slate-300'">
                        <div class="w-full aspect-video bg-slate-900 border border-slate-800 rounded-lg mb-3 shadow-sm overflow-hidden p-2">
                            <div class="w-full h-2 bg-slate-800 rounded mb-1"></div>
                            <div class="w-2/3 h-2 bg-slate-800 rounded"></div>
                        </div>
                        <p class="text-xs font-bold" :class="darkMode ? 'text-violet-700 dark:text-violet-300' : 'text-slate-600 dark:text-slate-400'">Dark Mode</p>
                        <div x-show="darkMode" class="absolute top-3 right-3 text-violet-500">
                            <x-heroicon-s-check-circle class="w-5 h-5" />
                        </div>
                    </button>
                </div>
            </div>

            <div class="h-px bg-slate-100 dark:bg-slate-800"></div>

            <div class="flex justify-end gap-3">
                <x-app.button type="button" variant="secondary" size="md">Discard Changes</x-app.button>
                <x-app.button type="submit" variant="primary" size="md">Save Appearance</x-app.button>
            </div>
        </form>
    </x-app.card>
</x-app.settings-layout>
@endsection
