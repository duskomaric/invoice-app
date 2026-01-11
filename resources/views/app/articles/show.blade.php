<x-app-layout>
    <x-slot name="title">{{ $article->name }} - {{ $company->name }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <nav class="flex mb-2 text-sm text-gray-500">
                <a href="{{ route('app.articles.index', $company) }}" class="hover:text-gray-700">Articles</a>
                <span class="mx-1">/</span>
                <span class="text-gray-900">{{ $article->name }}</span>
            </nav>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $article->name }}</h1>
        </div>
        <a href="{{ route('app.articles.edit', [$company, $article]) }}" class="px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md hover:bg-neutral-900">Edit</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium mb-4">Details</h3>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Type</dt><dd class="font-medium">{{ $article->type?->value ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Unit</dt><dd class="font-medium">{{ $article->unit ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Tax Category</dt><dd class="font-medium">{{ $article->tax_category ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Status</dt><dd><span class="px-2 py-1 text-xs rounded-full {{ $article->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100' }}">{{ $article->is_active ? 'Active' : 'Inactive' }}</span></dd></div>
            </dl>
            @if($article->description)
                <div class="mt-4 pt-4 border-t">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Description</h4>
                    <p class="text-sm">{{ $article->description }}</p>
                </div>
            @endif
        </div>

        <div class="bg-white shadow rounded-lg p-6 h-fit">
            <form action="{{ route('app.articles.destroy', [$company, $article]) }}" method="POST" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100">Delete Article</button>
            </form>
        </div>
    </div>
</x-app-layout>
