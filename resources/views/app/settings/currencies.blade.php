@extends('layouts.app')

@section('title', 'Currencies - ' . $company->name)
@section('page-title', 'Currencies')
@section('page-subtitle', 'Manage currencies for your invoices and quotes')

@section('content')
<x-app.settings-layout :company="$company" active="currencies">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ showAdd: false }">
        {{-- List --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Active Currencies</h3>
                <x-app.button @click="showAdd = true" variant="primary" size="xs" x-show="!showAdd">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>                    
                    Add New
                </x-app.button>
            </div>

            <x-app.table>
                <x-slot name="head">
                    <x-app.table-th>Code</x-app.table-th>
                    <x-app.table-th>Name</x-app.table-th>
                    <x-app.table-th>Prefix</x-app.table-th>
                    <x-app.table-th class="text-right">Actions</x-app.table-th>
                </x-slot>

                @forelse($currencies as $currency)
                    <x-app.table-tr>
                        <x-app.table-td>
                            <span class="font-bold text-slate-900 dark:text-white">{{ $currency->code }}</span>
                        </x-app.table-td>
                        <x-app.table-td>
                            <span class="text-xs text-slate-600 dark:text-slate-400 font-medium">{{ $currency->name }}</span>
                        </x-app.table-td>
                        <x-app.table-td>
                            <span class="text-xs font-mono bg-slate-100 dark:bg-slate-700 px-1.5 py-0.5 rounded text-slate-600 dark:text-slate-400">{{ $currency->prefix }}</span>
                        </x-app.table-td>
                        <x-app.table-td class="text-right">
                            <form action="{{ route('app.settings.currencies.destroy', [$company, $currency]) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <x-app.button type="submit" variant="ghost" size="icon-xs" class="text-rose-500 hover:text-rose-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </x-app.button>
                            </form>
                        </x-app.table-td>
                    </x-app.table-tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-400 italic text-sm">No custom currencies added.</td>
                    </tr>
                @endforelse
            </x-app.table>
        </div>

        {{-- Add Form --}}
        <div class="lg:col-span-1">
            <div x-show="showAdd" x-transition x-cloak>
                <x-app.card class="border-violet-200 dark:border-violet-900/50 shadow-lg shadow-violet-500/5">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight">Add Currency</h3>
                        <button @click="showAdd = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form action="{{ route('app.settings.currencies.store', $company) }}" method="POST" class="space-y-4">
                        @csrf
                        <x-app.input label="Currency Code" name="code" placeholder="e.g. BAM, EUR, USD" required />
                        <x-app.input label="Currency Name" name="name" placeholder="e.g. Convertible Mark" required />
                        <x-app.input label="Prefix" name="prefix" placeholder="e.g. KM, €, $" />

                        <div class="pt-2">
                            <x-app.button type="submit" variant="primary" class="w-full">Create Currency</x-app.button>
                        </div>
                    </form>
                </x-app.card>
            </div>

            <div x-show="!showAdd" class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-900/50 border-2 border-dashed border-slate-200 dark:border-slate-800 flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-slate-400 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">New Currency</h4>
                <p class="text-xs text-slate-500 mb-4">Need to bill in another currency? Add it here to make it available in your invoices.</p>
                <x-app.button @click="showAdd = true" variant="secondary" size="sm">Get Started</x-app.button>
            </div>
        </div>
    </div>
</x-app.settings-layout>
@endsection
