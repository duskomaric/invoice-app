<x-app-layout>
    <x-slot name="title">Add User - {{ $company->name }}</x-slot>

    <div class="mb-6"><h1 class="text-2xl font-semibold text-gray-900">Add User</h1></div>

    <form action="{{ route('app.users.store', $company) }}" method="POST">
        @csrf
        <div class="max-w-md space-y-6">
            <div class="bg-white shadow rounded-lg p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full h-10 px-3 text-sm border rounded-md border-neutral-300">
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('app.users.index', $company) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border rounded-md hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md hover:bg-neutral-900">Add User</button>
            </div>
        </div>
    </form>
</x-app-layout>
