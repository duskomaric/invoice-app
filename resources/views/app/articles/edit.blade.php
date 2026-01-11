<x-app-layout>
    <x-slot name="title">Edit {{ $article->name }} - {{ $company->name }}</x-slot>

    <div class="mb-6"><h1 class="text-2xl font-semibold text-gray-900">Edit {{ $article->name }}</h1></div>

    <form action="{{ route('app.articles.update', [$company, $article]) }}" method="POST">
        @csrf @method('PUT')
        <div class="max-w-2xl space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ $article->name }}" required class="w-full h-10 px-3 text-sm border rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" required class="w-full h-10 px-3 text-sm border rounded-md">
                            <option value="product" {{ $article->type?->value === 'product' ? 'selected' : '' }}>Product</option>
                            <option value="service" {{ $article->type?->value === 'service' ? 'selected' : '' }}>Service</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                        <input type="text" name="unit" value="{{ $article->unit }}" class="w-full h-10 px-3 text-sm border rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tax Category</label>
                        <input type="text" name="tax_category" value="{{ $article->tax_category }}" class="w-full h-10 px-3 text-sm border rounded-md">
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ $article->is_active ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 text-sm border rounded-md">{{ $article->description }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('app.articles.show', [$company, $article]) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border rounded-md hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md hover:bg-neutral-900">Update Article</button>
            </div>
        </div>
    </form>
</x-app-layout>
