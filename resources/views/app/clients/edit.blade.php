<x-app-layout>
    <x-slot name="title">Edit {{ $client->name }} - {{ $company->name }}</x-slot>

    <div class="mb-6"><h1 class="text-2xl font-semibold text-gray-900">Edit {{ $client->name }}</h1></div>

    <form action="{{ route('app.clients.update', [$company, $client]) }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">Basic Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" value="{{ $client->name }}" required class="w-full h-10 px-3 text-sm border rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ $client->email }}" class="w-full h-10 px-3 text-sm border rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" value="{{ $client->phone }}" class="w-full h-10 px-3 text-sm border rounded-md">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">VAT Number</label>
                            <input type="text" name="vat_number" value="{{ $client->vat_number }}" class="w-full h-10 px-3 text-sm border rounded-md">
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">Address</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2"><input type="text" name="address" value="{{ $client->address }}" placeholder="Address" class="w-full h-10 px-3 text-sm border rounded-md"></div>
                        <div><input type="text" name="city" value="{{ $client->city }}" placeholder="City" class="w-full h-10 px-3 text-sm border rounded-md"></div>
                        <div><input type="text" name="postal_code" value="{{ $client->postal_code }}" placeholder="Postal Code" class="w-full h-10 px-3 text-sm border rounded-md"></div>
                        <div class="col-span-2"><input type="text" name="country" value="{{ $client->country }}" placeholder="Country" class="w-full h-10 px-3 text-sm border rounded-md"></div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">Notes</h3>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 text-sm border rounded-md">{{ $client->notes }}</textarea>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 h-fit sticky top-6">
                <button type="submit" class="w-full mb-3 px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md">Update Client</button>
                <a href="{{ route('app.clients.show', [$company, $client]) }}" class="w-full block text-center px-4 py-2 text-sm text-gray-700 border rounded-md">Cancel</a>
            </div>
        </div>
    </form>
</x-app-layout>
