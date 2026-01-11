<x-app-layout>
    <x-slot name="title">Edit {{ $user->name }} - {{ $company->name }}</x-slot>

    <div class="mb-6"><h1 class="text-2xl font-semibold text-gray-900">Edit {{ $user->name }}</h1></div>

    <form action="{{ route('app.users.update', [$company, $user]) }}" method="POST">
        @csrf @method('PUT')
        <div class="max-w-md space-y-6">
            <div class="bg-white shadow rounded-lg p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" required class="w-full h-10 px-3 text-sm border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" required class="w-full h-10 px-3 text-sm border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" class="w-full h-10 px-3 text-sm border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="w-full h-10 px-3 text-sm border rounded-md">
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('app.users.show', [$company, $user]) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border rounded-md">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md">Update User</button>
            </div>
        </div>
    </form>
</x-app-layout>
