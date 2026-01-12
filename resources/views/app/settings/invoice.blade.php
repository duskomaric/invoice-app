@extends('layouts.app')

@section('title', 'Invoice Settings - ' . $company->name)
@section('page-title', 'Invoicing Preferences')
@section('page-subtitle', 'Configure sequence resets, prefixes and document defaults')

@section('content')
<x-app.settings-layout :company="$company" active="invoice">
    <form action="{{ route('app.settings.invoice.update', $company) }}" method="POST" class="max-w-5xl space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-app.card>
                <x-app.section-header title="Document Basics" subtitle="Defaults for new invoices" icon="document-text" variant="primary" />
                
                <div class="space-y-4 mt-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Default Template</label>
                        <select name="default_invoice_template" class="w-full h-11 px-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-violet-500/10 focus:border-violet-400 transition-all shadow-sm">
                            @foreach($templates as $template)
                                <option value="{{ $template->value }}" {{ $settings['default_invoice_template'] === $template->value ? 'selected' : '' }}>{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Default Language</label>
                        <select name="default_invoice_language" class="w-full h-11 px-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-violet-500/10 focus:border-violet-400 transition-all shadow-sm">
                            @foreach($languages as $lang)
                                <option value="{{ $lang->value }}" {{ $settings['default_invoice_language'] === $lang->value ? 'selected' : '' }}>{{ $lang->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <x-app.input label="Due Days" name="default_invoice_due_days" type="number" value="{{ $settings['default_invoice_due_days'] }}" required />
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Default Currency</label>
                            <select name="default_invoice_currency" class="w-full h-11 px-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-violet-500/10 focus:border-violet-400 transition-all shadow-sm">
                                @foreach($currencies as $c)
                                    <option value="{{ $c->code }}" {{ $settings['default_invoice_currency'] === $c->code ? 'selected' : '' }}>{{ $c->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </x-app.card>

            <x-app.card>
                <x-app.section-header title="Numbering Sequence" subtitle="Control how documents are numbered" icon="hashtag" variant="secondary" />
                
                <div class="space-y-4 mt-6">
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 dark:bg-slate-900/50">
                        <div>
                            <div class="text-sm font-bold text-slate-900 dark:text-white">Reset Yearly</div>
                            <div class="text-[10px] text-slate-500 font-medium">Reset numbering at start of each year</div>
                        </div>
                        <input type="checkbox" name="invoice_numbering_reset_yearly" value="1" {{ $settings['invoice_numbering_reset_yearly'] ? 'checked' : '' }} class="h-5 w-5 rounded-lg border-slate-300 text-violet-600 focus:ring-violet-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <x-app.input label="Pad Zeros" name="invoice_numbering_pad_zeros" type="number" value="{{ $settings['invoice_numbering_pad_zeros'] }}" required />
                        <x-app.input label="Starting Number" name="invoice_numbering_starting_number" type="number" value="{{ $settings['invoice_numbering_starting_number'] }}" required />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Prefix Format</label>
                        <select name="invoice_numbering_prefix" class="w-full h-11 px-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-violet-500/10 focus:border-violet-400 transition-all shadow-sm">
                            <option value="year" {{ $settings['invoice_numbering_prefix'] === 'year' ? 'selected' : '' }}>Year (e.g. 2024-)</option>
                            <option value="currency" {{ $settings['invoice_numbering_prefix'] === 'currency' ? 'selected' : '' }}>Currency (e.g. BAM-)</option>
                            <option value="custom" {{ $settings['invoice_numbering_prefix'] === 'custom' ? 'selected' : '' }}>Custom Text</option>
                        </select>
                    </div>
                </div>
            </x-app.card>
        </div>

        <div class="flex items-center justify-end">
            <x-app.button type="submit" variant="primary">
                Save Invoicing Settings
            </x-app.button>
        </div>
    </form>
</x-app.settings-layout>
@endsection
