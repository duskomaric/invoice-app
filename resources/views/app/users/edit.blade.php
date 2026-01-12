@extends('layouts.app')

@section('title', 'Edit Member - App')
@section('page-title', 'Edit Member')
@section('page-subtitle', 'Update team member information and password')
@section('page-badge', 'Edit')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.users.show', [$company, $user]) }}" variant="secondary" size="sm">
        Cancel
    </x-app.button>
    <x-app.button type="submit" form="user-form" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Save Changes
    </x-app.button>
</div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <form id="user-form" action="{{ route('app.users.update', [$company, $user]) }}" method="POST">
        @csrf
        @method('PUT')
        
        <x-app.card>
            <x-app.section-header title="Member Information" subtitle="Update details for {{ $user->name }}" icon="user-edit" variant="primary" />
            
            <div class="mt-6 space-y-4">
                <x-app.input label="Full Name" name="name" value="{{ old('name', $user->name) }}" required />
                <x-app.input label="Email Address" name="email" type="email" value="{{ old('email', $user->email) }}" required />
                
                <div class="pt-6 border-t border-slate-50 dark:border-slate-800">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-tight">Security & Password</span>
                    </div>
                    <p class="text-[10px] text-slate-500 mb-4 italic">Leave blank if you don't want to change the password.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-app.input label="New Password" name="password" type="password" placeholder="••••••••" />
                        <x-app.input label="Confirm New Password" name="password_confirmation" type="password" placeholder="••••••••" />
                    </div>
                </div>
            </div>
        </x-app.card>
    </form>
</div>
@endsection
