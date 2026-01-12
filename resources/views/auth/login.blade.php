@extends('layouts.guest')

@section('title', 'Sign In')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Welcome back</h2>
        <p class="mt-2 text-sm text-slate-500 font-medium italic">Please enter your credentials to access your account</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-app.input 
                label="Email Address" 
                name="email" 
                type="email" 
                :value="old('email')" 
                required 
                autofocus 
                placeholder="you@company.com"
                autocomplete="username"
            />
            @error('email')
                <p class="mt-1.5 text-[11px] font-bold text-rose-500 uppercase tracking-tight">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300">Password</label>
                <a href="#" class="text-[10px] font-bold text-violet-600 dark:text-violet-400 hover:text-violet-500 uppercase tracking-tight">Forgot password?</a>
            </div>
            <div class="relative group">
                <input 
                    id="password" 
                    name="password" 
                    type="password" 
                    required 
                    autocomplete="current-password"
                    class="w-full h-11 px-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-violet-500/10 focus:border-violet-400 dark:focus:border-violet-500 transition-all shadow-sm group-hover:border-slate-300 dark:group-hover:border-slate-600"
                    placeholder="••••••••"
                >
            </div>
            @error('password')
                <p class="mt-1.5 text-[11px] font-bold text-rose-500 uppercase tracking-tight">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-slate-300 dark:border-slate-600 dark:bg-slate-800 rounded transition-all cursor-pointer">
            <label for="remember_me" class="ml-2 block text-[11px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-tight cursor-pointer">Remember me for 30 days</label>
        </div>

        <div>
            <x-app.button type="submit" variant="primary" size="lg" class="w-full h-12 text-sm font-bold">
                Sign In
            </x-app.button>
        </div>
    </form>

    <div class="pt-6 border-t border-slate-200 dark:border-slate-800/50">
        <p class="text-center text-xs text-slate-500 font-medium">
            Don't have an account? 
            <a href="{{ route('public.register') }}" class="font-bold text-violet-600 dark:text-violet-400 hover:underline">Create one for free</a>
        </p>
    </div>
</div>
@endsection
