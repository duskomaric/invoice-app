<x-app-layout>
    <x-slot name="title">Select Company</x-slot>

    <div class="max-w-lg mx-auto mt-10">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-semibold text-gray-900">Select a Company</h1>
            <p class="mt-2 text-sm text-gray-500">Choose which company you want to work with.</p>
        </div>

        <div class="bg-white shadow rounded-lg divide-y divide-gray-200">
            @foreach($companies as $company)
                <form action="{{ route('app.company.switch', $company) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-semibold text-sm">{{ substr($company->name, 0, 2) }}</span>
                            </div>
                            <div class="ml-4 text-left">
                                <p class="text-sm font-medium text-gray-900">{{ $company->name }}</p>
                                <p class="text-sm text-gray-500">{{ $company->email ?? 'No email' }}</p>
                            </div>
                        </div>
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </form>
            @endforeach
        </div>
    </div>
</x-app-layout>
