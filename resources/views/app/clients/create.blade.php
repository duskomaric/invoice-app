<x-app-layout>
    <x-slot name="title">Create Client - {{ $company->name }}</x-slot>

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Create Client</h1>
    </div>

    <form action="{{ route('app.clients.store', $company) }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">Basic Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300 focus:ring-2 focus:ring-neutral-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">VAT Number</label>
                            <input type="text" name="vat_number" value="{{ old('vat_number') }}" class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">Address</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" name="address" value="{{ old('address') }}" class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" name="city" value="{{ old('city') }}" class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input type="text" name="country" value="{{ old('country') }}" class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">Notes</h3>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 text-sm border rounded-md border-neutral-300">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 h-fit sticky top-6">
                <button type="submit" class="w-full mb-3 px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md hover:bg-neutral-900">Create Client</button>
                <a href="{{ route('app.clients.index', $company) }}" class="w-full block text-center px-4 py-2 text-sm text-gray-700 border rounded-md hover:bg-gray-50">Cancel</a>
            </div>
        </div>
    </form>
</x-app-layout>
