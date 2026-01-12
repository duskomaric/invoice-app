@extends('layouts.app')

@section('title', 'Team - App')
@section('page-title', 'Team Management')
@section('page-subtitle', 'Manage users and permissions for this company')
@section('page-badge', $users->total())

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.users.create', $company) }}" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add User
    </x-app.button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Tabs Navigation --}}
    <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800/50 p-1 rounded-2xl w-fit">
        @php
            $tabs = [
                ['id' => 'general', 'label' => 'General', 'icon' => 'cog-6-tooth', 'route' => 'app.settings.index'],
                ['id' => 'company', 'label' => 'Company', 'icon' => 'building-office', 'route' => 'app.settings.company'],
                ['id' => 'invoice', 'label' => 'Invoicing', 'icon' => 'document-text', 'route' => 'app.settings.invoice'],
                ['id' => 'bank', 'label' => 'Bank Accounts', 'icon' => 'credit-card', 'route' => 'app.settings.bank-accounts'],
                ['id' => 'team', 'label' => 'Team', 'icon' => 'users', 'route' => 'app.users.index'],
            ];
            $currentRoute = request()->route()->getName();
        @endphp

        @foreach($tabs as $tab)
            <a href="{{ route($tab['route'], $company) }}" 
               class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $currentRoute === $tab['route'] ? 'bg-white dark:bg-slate-800 text-violet-600 dark:text-violet-400 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $currentRoute === $tab['route'] ? 'bg-violet-500' : 'bg-transparent' }}"></span>
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>

    {{-- Users Table --}}
    <x-app.card class="overflow-hidden no-padding relative z-10">
        <x-app.table>
            <x-slot name="head">
                <x-app.table-th>User</x-app.table-th>
                <x-app.table-th>Role</x-app.table-th>
                <x-app.table-th>Status</x-app.table-th>
                <x-app.table-th>Joined Date</x-app.table-th>
                <x-app.table-th class="text-right">Actions</x-app.table-th>
            </x-slot>

            @foreach($users as $user)
                <x-app.table-tr hover>
                    <x-app.table-td>
                        <div class="flex items-center gap-3">
                            <x-app.avatar :name="$user->name" size="sm" />
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900 dark:text-white text-xs">{{ $user->name }}</span>
                                <span class="text-[10px] text-slate-500 font-medium italic">{{ $user->email }}</span>
                            </div>
                        </div>
                    </x-app.table-td>
                    <x-app.table-td>
                        <x-app.badge variant="secondary" size="xs">Admin</x-app.badge>
                    </x-app.table-td>
                    <x-app.table-td>
                        <x-app.status-badge status="Active" variant="emerald" />
                    </x-app.table-td>
                    <x-app.table-td>
                        <span class="text-xs text-slate-700 dark:text-slate-300">{{ $user->created_at->format('M d, Y') }}</span>
                    </x-app.table-td>
                    <x-app.table-td class="text-right">
                        <div class="flex items-center justify-end gap-1">
                            <x-app.button href="{{ route('app.users.show', [$company, $user]) }}" variant="secondary" size="icon-sm" tooltip="View Profile">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </x-app.button>
                            <x-app.button href="{{ route('app.users.edit', [$company, $user]) }}" variant="secondary" size="icon-sm" tooltip="Edit Permissions">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </x-app.button>
                        </div>
                    </x-app.table-td>
                </x-app.table-tr>
            @endforeach
        </x-app.table>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $users->links() }}
            </div>
        @endif
    </x-app.card>
</div>
@endsection
