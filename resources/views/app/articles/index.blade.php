@extends('layouts.app')

@section('title', 'Articles - App')
@section('page-title', 'Articles')
@section('page-subtitle', 'Manage your products and services')
@section('page-badge', $articles->total())

@section('header-actions')
<div class="flex items-center gap-2">
    <x-app.button variant="secondary" size="sm" class="hidden sm:flex">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
        Export
    </x-app.button>
    <x-app.button href="{{ route('app.articles.create', $company) }}" variant="primary" size="sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Article
    </x-app.button>
</div>
@endsection

@section('content')
<div class="space-y-5">
    <x-app.stats-grid>
        <x-app.stats-card
            title="Total Articles"
            value="{{ $articles->total() }}"
            icon="cube"
            variant="primary"
        />
        <x-app.stats-card
            title="Active Items"
            value="{{ $company->articles()->where('is_active', true)->count() }}"
            icon="check-circle"
            variant="success"
        />
        <x-app.stats-card
            title="Service Items"
            value="{{ $company->articles()->where('type', 'services')->count() }}"
            icon="sparkles"
            variant="violet"
        />
        <x-app.stats-card
            title="Product Items"
            value="{{ $company->articles()->where('type', 'products')->count() }}"
            icon="archive-box"
            variant="amber"
        />
    </x-app.stats-grid>

    <x-app.filter-bar placeholder="Search articles...">
        <x-app.dropdown label="Category" icon="tag" variant="secondary" size="sm" class="shadow-sm">
            <a href="#" class="block px-3 py-2 text-xs text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-left">Hardware</a>
            <a href="#" class="block px-3 py-2 text-xs text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-left">Software</a>
            <a href="#" class="block px-3 py-2 text-xs text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-left">Services</a>
        </x-app.dropdown>
    </x-app.filter-bar>

    <x-app.data-list :collection="$articles">
        <x-slot:desktop>
            <x-app.table>
                <x-slot name="head">
                    <x-app.table-th icon="cube">Article / Service</x-app.table-th>
                    <x-app.table-th icon="tag">Type</x-app.table-th>
                    <x-app.table-th icon="banknotes">Price ({{ $company->currency }})</x-app.table-th>
                    <x-app.table-th icon="check-badge">Status</x-app.table-th>
                    <x-app.table-th class="text-right">Actions</x-app.table-th>
                </x-slot:head>

                @forelse($articles as $article)
                    <x-app.table-tr hover>
                        <x-app.table-td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center flex-shrink-0">
                                    @if($article->type->value === 'services')
                                        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    @else
                                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white text-sm">{{ $article->name }}</div>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 line-clamp-1 max-w-[200px]">{{ $article->description }}</p>
                                </div>
                            </div>
                        </x-app.table-td>
                        <x-app.table-td>
                            <x-app.status-badge :status="$article->type->getLabel()" variant="slate" size="sm" />
                        </x-app.table-td>
                        <x-app.table-td>
                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $company->currency === 'EUR' ? '€' : ($company->currency === 'USD' ? '$' : $company->currency) }}{{ $article->getFormattedPrice($company->currency) }}</span>
                        </x-app.table-td>
                        <x-app.table-td>
                            @if($article->is_active)
                                <x-app.status-badge status="Active" variant="success" size="sm" dot />
                            @else
                                <x-app.status-badge status="Inactive" variant="slate" size="sm" dot />
                            @endif
                        </x-app.table-td>
                        <x-app.table-td class="text-right">
                            <x-app.action-buttons 
                                :edit-route="route('app.articles.edit', [$company, $article])"
                                :delete-route="route('app.articles.destroy', [$company, $article])"
                            />
                        </x-app.table-td>
                    </x-app.table-tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400 italic text-xs">No articles found</td>
                    </tr>
                @endforelse
            </x-app.table>
        </x-slot:desktop>

        <x-slot:mobile>
            @foreach($articles as $article)
                <x-app.card hover padding="p-4">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center flex-shrink-0">
                            @if($article->type->value === 'services')
                                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            @else
                                <svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-900 dark:text-white truncate">{{ $article->name }}</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1">{{ $article->type->getLabel() }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-violet-600 dark:text-violet-400">{{ $company->currency === 'EUR' ? '€' : ($company->currency === 'USD' ? '$' : $company->currency) }}{{ $article->getFormattedPrice($company->currency) }}</p>
                            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ $article->unit }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-700/50">
                        <x-app.button href="{{ route('app.articles.edit', [$company, $article]) }}" variant="ghost" size="sm" class="text-xs">
                            Edit Article
                        </x-app.button>
                    </div>
                </x-app.card>
            @endforeach
        </x-slot:mobile>
    </x-app.data-list>
</div>
@endsection
