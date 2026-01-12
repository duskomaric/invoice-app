@extends('layouts.app')

@section('title', 'Email Settings - ' . $company->name)
@section('page-title', 'Email Settings')
@section('page-subtitle', 'Configure how the application sends emails')

@section('content')
<x-app.settings-layout :company="$company" active="email">
    <div class="space-y-6">
        <x-app.card>
            <x-app.section-header title="Email Configuration" subtitle="Configure how the application sends emails" icon="envelope" variant="primary" />
            
            <form action="#" method="POST" class="mt-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-app.input label="Mail Driver" value="smtp" disabled />
                    <x-app.input label="SMTP Host" placeholder="smtp.mailtrap.io" />
                    <x-app.input label="SMTP Port" placeholder="2525" />
                    <x-app.input label="Encryption" placeholder="tls" />
                    <x-app.input label="Username" />
                    <x-app.input label="Password" type="password" />
                </div>

                <div class="h-px bg-slate-100 dark:bg-slate-800"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-app.input label="From Name" placeholder="My Company" />
                    <x-app.input label="From Address" placeholder="hello@company.com" />
                </div>

                <div class="flex justify-between items-center bg-slate-50 dark:bg-slate-900/50 p-6 rounded-3xl border border-slate-100 dark:border-slate-800">
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Test Connection</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Send a test email to verify your settings.</p>
                    </div>
                    <x-app.button type="button" variant="secondary" size="sm">Send Test Email</x-app.button>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <x-app.button type="submit" variant="primary" size="md">Save Email Settings</x-app.button>
                </div>
            </form>
        </x-app.card>

        <x-app.card>
            <div class="flex items-center justify-between mb-6">
                <x-app.section-header title="Email Templates" subtitle="Manage your automated document emails" icon="document-text" variant="secondary" />
                <x-app.button href="{{ route('app.settings.email-templates', $company) }}" variant="secondary" size="sm">Manage Templates</x-app.button>
            </div>
            
            <div class="p-4 rounded-2xl bg-violet-50 dark:bg-violet-900/10 border border-violet-100 dark:border-violet-800/30 flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-800/50 flex items-center justify-center text-violet-600 dark:text-violet-400 flex-shrink-0">
                    <x-heroicon-o-sparkles class="w-6 h-6" />
                </div>
                <div>
                    <h4 class="text-sm font-bold text-violet-900 dark:text-violet-200">Personalized Communication</h4>
                    <p class="text-xs text-violet-700 dark:text-violet-400 mt-1">You can customize the content of emails sent when you send invoices, quotes or proformas to your clients.</p>
                </div>
            </div>
        </x-app.card>
    </div>
</x-app.settings-layout>
@endsection
