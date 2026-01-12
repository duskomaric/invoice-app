@extends('layouts.app')

@section('title', 'Add Team Member - App')
@section('page-title', 'Add Team Member')
@section('page-subtitle', 'Invite a new member to your team')
@section('page-badge', 'New')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.users.index', $company) }}" variant="secondary" size="sm">
        Cancel
    </x-app.button>
    <x-app.button type="submit" form="user-form" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Save User
    </x-app.button>
</div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <form id="user-form" action="{{ route('app.users.store', $company) }}" method="POST">
        @csrf
        
        <x-app.card>
            <x-app.section-header title="Member Information" subtitle="Provide the basic credentials for the new user" icon="user-plus" variant="primary" />
            
            <div class="mt-6 space-y-4">
                <x-app.input label="Full Name" name="name" placeholder="John Doe" required />
                <x-app.input label="Email Address" name="email" type="email" placeholder="john.doe@company.com" required />
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-app.input label="Password" name="password" type="password" placeholder="••••••••" required />
                    <x-app.input label="Confirm Password" name="password_confirmation" type="password" placeholder="••••••••" required />
                </div>

                <div class="p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-900/50 ring-1 ring-slate-100 dark:ring-slate-800 mt-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-tight">Security Note</span>
                    </div>
                    <p class="text-[10px] text-slate-500 leading-relaxed font-medium">
                        Passwords must be at least 8 characters long and should include a mix of letters, numbers, and symbols for maximum security.
                    </p>
                </div>
            </div>
        </x-app.card>
    </form>
</div>
@endsection
