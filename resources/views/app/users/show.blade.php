<x-app-layout>
    <x-slot name="title">{{ $user->name }} - {{ $company->name }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <nav class="flex mb-2 text-sm text-gray-500">
                <a href="{{ route('app.users.index', $company) }}" class="hover:text-gray-700">Users</a>
                <span class="mx-1">/</span>
                <span class="text-gray-900">{{ $user->name }}</span>
            </nav>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $user->name }}</h1>
        </div>
        <a href="{{ route('app.users.edit', [$company, $user]) }}" class="px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md hover:bg-neutral-900">Edit</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white shadow rounded-lg p-6">
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Name</dt><dd class="font-medium">{{ $user->name }}</dd></div>
                <div><dt class="text-gray-500">Email</dt><dd class="font-medium">{{ $user->email }}</dd></div>
                <div><dt class="text-gray-500">Last Seen</dt><dd class="font-medium">{{ $user->last_seen_at?->diffForHumans() ?? 'Never' }}</dd></div>
                <div><dt class="text-gray-500">Created</dt><dd class="font-medium">{{ $user->created_at?->format('d.m.Y') }}</dd></div>
            </dl>
        </div>

        <div class="bg-white shadow rounded-lg p-6 h-fit">
            <form action="{{ route('app.users.destroy', [$company, $user]) }}" method="POST" onsubmit="return confirm('Remove user from company?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100">Remove from Company</button>
            </form>
        </div>
    </div>
</x-app-layout>
