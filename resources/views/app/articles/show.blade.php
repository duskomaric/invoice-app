@extends('layouts.app')

@section('title', 'Article Details - App')
@section('page-title', 'Article Overview')
@section('page-subtitle', 'Detailed article specifications and usage')
@section('page-badge', 'Article')

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button href="{{ route('app.articles.index', $company) }}" variant="secondary" size="sm" class="hidden sm:flex">
        Back to List
    </x-app.button>
    <x-app.button href="{{ route('app.articles.edit', [$company, $article]) }}" variant="secondary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        Edit Article
    </x-app.button>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <x-app.card>
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 p-4">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-500/10 to-fuchsia-500/10 border border-violet-100 dark:border-violet-500/20 flex items-center justify-center flex-shrink-0 text-violet-600 dark:text-violet-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $article->name }}</h2>
                    <div class="flex flex-wrap items-center gap-2 mt-1">
                        <x-app.badge variant="secondary" size="sm">{{ ucfirst($article->type->value) }}</x-app.badge>
                        <span class="text-slate-300 dark:text-slate-600">•</span>
                        <span class="text-sm text-slate-500 dark:text-slate-400">Unit: {{ $article->unit }}</span>
                        <span class="text-slate-300 dark:text-slate-600">•</span>
                        @if($article->is_active)
                            <span class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Active
                            </span>
                        @else
                            <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="text-left md:text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Standard Rate</p>
                <p class="text-3xl font-bold text-slate-900 dark:text-white">€{{ number_format($article->prices_meta['unit_price'] ?? 0, 2) }}</p>
                <p class="text-xs text-slate-500 mt-1">Tax: {{ ucfirst($article->tax_category ?? 'Standard') }}</p>
            </div>
        </div>

        @if($article->description)
        <div class="border-t border-slate-100 dark:border-slate-800 mt-6 pt-6 px-4 pb-4">
            <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Description</h4>
            <div class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                {{ $article->description }}
            </div>
        </div>
        @endif
    </x-app.card>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-app.card>
            <x-app.section-header title="Usage Statistics" subtitle="Article performance overview" icon="chart-bar" variant="primary" />
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 text-center">Invoiced Qty</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-white text-center">0</p>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 text-center">Revenue</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-white text-center">€0.00</p>
                </div>
            </div>
        </x-app.card>

        <x-app.card>
            <x-app.section-header title="Article Settings" subtitle="System configuration" icon="cog-6-tooth" variant="secondary" />
            <div class="space-y-4 mt-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Tax Class</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ ucfirst($article->tax_category ?? 'Standard') }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Created At</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ $article->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Last Updated</span>
                    <span class="font-medium text-slate-900 dark:text-white">{{ $article->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </x-app.card>
    </div>
</div>
@endsection
