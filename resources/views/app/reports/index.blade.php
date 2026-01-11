<x-app-layout>
    <x-slot name="title">Reports - {{ $company->name }}</x-slot>

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Reports</h1>
        <p class="mt-1 text-sm text-gray-500">View financial reports</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('app.reports.income-book', $company) }}" class="block bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Income Book</h3>
                    <p class="text-sm text-gray-500">KPI - Knjiga prometa</p>
                </div>
            </div>
        </a>
    </div>
</x-app-layout>
