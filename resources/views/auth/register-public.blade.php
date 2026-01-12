@extends('layouts.guest')

@section('title', 'Get Started')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Create your account</h2>
        <p class="mt-2 text-sm text-slate-500 font-medium italic">Join thousands of businesses managing invoices with PlusPlusI</p>
    </div>

    <form method="POST" action="{{ route('public.register') }}" class="space-y-4">
        @csrf

        <div>
            <x-app.input 
                label="Full Name" 
                name="name" 
                type="text" 
                :value="old('name')" 
                required 
                autofocus 
                placeholder="John Doe"
            />
            @error('name')
                <p class="mt-1.5 text-[11px] font-bold text-rose-500 uppercase tracking-tight">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-app.input 
                label="Email Address" 
                name="email" 
                type="email" 
                :value="old('email')" 
                required 
                placeholder="you@company.com"
                autocomplete="username"
            />
            @error('email')
                <p class="mt-1.5 text-[11px] font-bold text-rose-500 uppercase tracking-tight">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-app.input 
                label="Company Name" 
                name="company_name" 
                type="text" 
                :value="old('company_name')" 
                required 
                placeholder="Acme Corp"
            />
            @error('company_name')
                <p class="mt-1.5 text-[11px] font-bold text-rose-500 uppercase tracking-tight">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-app.input 
                    label="Password" 
                    name="password" 
                    type="password" 
                    required 
                    placeholder="••••••••"
                />
            </div>
            <div>
                <x-app.input 
                    label="Confirm" 
                    name="password_confirmation" 
                    type="password" 
                    required 
                    placeholder="••••••••"
                />
            </div>
        </div>
        @error('password')
            <p class="mt-1.5 text-[11px] font-bold text-rose-500 uppercase tracking-tight">{{ $message }}</p>
        @enderror

        <div class="pt-2">
            <x-app.button type="submit" variant="primary" size="lg" class="w-full h-12 text-sm font-bold">
                Create Account
            </x-app.button>
        </div>
    </form>

    <div class="pt-6 border-t border-slate-200 dark:border-slate-800/50 text-center">
        <p class="text-xs text-slate-500 font-medium">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-bold text-violet-600 dark:text-violet-400 hover:underline">Sign In</a>
        </p>
        <p class="mt-4 text-[10px] text-slate-400 leading-relaxed italic">
            By creating an account, you agree to our 
            <a href="#" class="underline">Terms of Service</a> and 
            <a href="#" class="underline">Privacy Policy</a>.
        </p>
    </div>
</div>
@endsection
