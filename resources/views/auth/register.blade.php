@extends('layouts.guest')

@section('title', 'Complete Registration')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Create Account</h2>
        <p class="mt-2 text-sm text-slate-500 font-medium italic">Complete your profile to join the team</p>
    </div>

    <form method="POST" action="{{ route('register.store', $invitedUser->invitation_code) }}" class="space-y-4">
        @csrf

        {{-- Name Fields --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required autofocus
                    class="w-full h-11 px-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border-none ring-1 ring-slate-200 dark:ring-slate-800 focus:ring-2 focus:ring-violet-500 transition-all text-sm font-bold text-slate-700 dark:text-slate-200 placeholder-slate-400"
                    placeholder="John">
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                    class="w-full h-11 px-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border-none ring-1 ring-slate-200 dark:ring-slate-800 focus:ring-2 focus:ring-violet-500 transition-all text-sm font-bold text-slate-700 dark:text-slate-200 placeholder-slate-400"
                    placeholder="Doe">
            </div>
        </div>

        {{-- Email Field (Read Only) --}}
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Email Address</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-violet-500 transition-colors">
                    <x-heroicon-o-envelope class="w-4.5 h-4.5" />
                </div>
                <input type="email" name="email" value="{{ $invitedUser->email }}" readonly
                    class="w-full h-11 pl-11 pr-4 rounded-xl bg-slate-100 dark:bg-slate-800/50 border-none ring-1 ring-slate-200 dark:ring-slate-800 text-sm font-bold text-slate-500 dark:text-slate-400 cursor-not-allowed">
            </div>
            <p class="mt-1 text-[10px] text-slate-400 font-medium italic">Email is fixed per invitation</p>
        </div>

        <div>
            <x-app.input 
                label="Password" 
                name="password" 
                type="password" 
                required 
                placeholder="••••••••"
            />
            @error('password')
                <p class="mt-1.5 text-[11px] font-bold text-rose-500 uppercase tracking-tight">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-app.input 
                label="Confirm Password" 
                name="password_confirmation" 
                type="password" 
                required 
                placeholder="••••••••"
            />
        </div>

        <div class="pt-2">
            <x-app.button type="submit" variant="primary" size="lg" class="w-full h-12 text-sm font-bold">
                Create Account
            </x-app.button>
        </div>
    </form>

    <div class="pt-6 border-t border-slate-200 dark:border-slate-800/50">
        <p class="text-center text-xs text-slate-500 font-medium">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-bold text-violet-600 dark:text-violet-400 hover:underline">Sign In Instead</a>
        </p>
    </div>
</div>
@endsection
