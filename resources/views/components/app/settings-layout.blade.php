@props([
    'company',
    'active' => 'general'
])

@php
    $tabs = [
        ['id' => 'general', 'label' => 'General', 'icon' => 'cog-6-tooth', 'route' => 'app.settings.index'],
        ['id' => 'company', 'label' => 'Company', 'icon' => 'building-office', 'route' => 'app.settings.company'],
        ['id' => 'invoice', 'label' => 'Invoicing', 'icon' => 'document-text', 'route' => 'app.settings.invoice'],
        ['id' => 'fiscal', 'label' => 'Fiscal', 'icon' => 'ticket', 'route' => 'app.settings.fiscalization'],
        ['id' => 'currencies', 'label' => 'Currencies', 'icon' => 'banknotes', 'route' => 'app.settings.currencies'],
        ['id' => 'bank', 'label' => 'Bank', 'icon' => 'credit-card', 'route' => 'app.settings.bank-accounts'],
        ['id' => 'email', 'label' => 'Email', 'icon' => 'envelope', 'route' => 'app.settings.email'],
        ['id' => 'templates', 'label' => 'Email Templates', 'icon' => 'document-duplicate', 'route' => 'app.settings.email-templates'],
        ['id' => 'signatures', 'label' => 'Signatures', 'icon' => 'pencil-square', 'route' => 'app.settings.email-signatures'],
        ['id' => 'appearance', 'label' => 'Theme', 'icon' => 'swatch', 'route' => 'app.settings.appearance'],
        ['id' => 'notifications', 'label' => 'Notifications', 'icon' => 'bell', 'route' => 'app.settings.notifications'],
        ['id' => 'roles', 'label' => 'Roles', 'icon' => 'shield-check', 'route' => 'app.settings.roles'],
        ['id' => 'team', 'label' => 'Team', 'icon' => 'users', 'route' => 'app.users.index'],
    ];
@endphp

<div class="space-y-6">
    <div class="flex flex-wrap items-center gap-1 bg-slate-100 dark:bg-slate-800/50 p-1 rounded-2xl w-full lg:w-fit">
        @foreach($tabs as $tab)
            <a href="{{ route($tab['route'], $company) }}" 
               class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all {{ $active === $tab['id'] ? 'bg-white dark:bg-slate-800 text-violet-600 dark:text-violet-400 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                <x-dynamic-component :component="'heroicon-o-' . $tab['icon']" class="w-3.5 h-3.5" />
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>

    <div>
        {{ $slot }}
    </div>
</div>
