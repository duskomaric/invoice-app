@extends('layouts.app')

@section('title', 'User Details - App')
@section('page-title', 'User Profile')
@section('page-subtitle', 'Member information and activity overview')
@section('page-badge', 'Member')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.users.index', $company) }}" variant="secondary" size="sm">
        Back to List
    </x-app.button>
    <x-app.button href="{{ route('app.users.edit', [$company, $user]) }}" variant="secondary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        Edit Member
    </x-app.button>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            <x-app.card class="text-center">
                <div class="relative inline-block mb-4">
                    <x-app.avatar :name="$user->name" size="xl" />
                    <div class="absolute bottom-1 right-1 w-4 h-4 bg-emerald-500 border-4 border-white dark:border-slate-800 rounded-full shadow-sm"></div>
                </div>
                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $user->name }}</h3>
                <p class="text-xs text-slate-500 font-medium italic mb-6 truncate">{{ $user->email }}</p>
                
                <div class="pt-6 border-t border-slate-50 dark:border-slate-800 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Project Role</span>
                        <x-app.badge variant="secondary" size="xs">Administrator</x-app.badge>
                    </div>
                </div>
            </x-app.card>

            <x-app.card>
                <x-app.section-header title="Security Info" icon="shield-check" variant="secondary" />
                <div class="mt-4 space-y-3">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Last Login</span>
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Today, 2:45 PM</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Login IP</span>
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300 font-mono">192.168.1.1</span>
                    </div>
                </div>
            </x-app.card>
        </div>

        {{-- Main Activity Content --}}
        <div class="lg:col-span-2 space-y-6">
            <x-app.card>
                <x-app.section-header title="Recent Activity" subtitle="Last actions performed by this user" icon="clock" variant="primary" />
                <div class="mt-6 space-y-4">
                    @forelse([] as $activity)
                        {{-- Activity rows --}}
                    @empty
                        <div class="py-8 text-center bg-slate-50/50 dark:bg-slate-900/50 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800">
                            <p class="text-xs text-slate-400 font-medium italic">No recent activity recorded for this member.</p>
                        </div>
                    @endforelse
                </div>
            </x-app.card>

            <x-app.card class="border-rose-100 dark:border-rose-900/20 bg-rose-50/20 dark:bg-rose-950/10">
                <x-app.section-header title="Danger Zone" subtitle="Critical actions for this account" icon="exclamation-triangle" variant="secondary" />
                <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="text-sm font-bold text-slate-900 dark:text-white">Remove Member</div>
                        <p class="text-[10px] text-slate-500 italic">This user will immediately lose access to this company workspace.</p>
                    </div>
                    <form action="{{ route('app.users.destroy', [$company, $user]) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this user from the company?')">
                        @csrf
                        @method('DELETE')
                        <x-app.button type="submit" variant="secondary" size="sm" class="text-rose-600 hover:text-rose-700 hover:bg-rose-100 border-rose-200">
                            Remove User
                        </x-app.button>
                    </form>
                </div>
            </x-app.card>
        </div>
    </div>
</div>
@endsection
