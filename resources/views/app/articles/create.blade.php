<x-app-layout>
    <x-slot name="title">Create Article - {{ $company->name }}</x-slot>

    <div class="mb-6"><h1 class="text-2xl font-semibold text-gray-900">Create Article</h1></div>

    <form action="{{ route('app.articles.store', $company) }}" method="POST">
        @csrf
        <div class="max-w-2xl space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                            <option value="product">Product</option>
                            <option value="service">Service</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                        <input type="text" name="unit" value="{{ old('unit') }}" placeholder="pcs, hrs, etc." class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tax Category</label>
                        <input type="text" name="tax_category" value="{{ old('tax_category') }}" class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 text-sm border rounded-md border-neutral-300">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('app.articles.index', $company) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border rounded-md hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md hover:bg-neutral-900">Create Article</button>
            </div>
        </div>
    </form>
</x-app-layout>
