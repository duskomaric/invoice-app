<x-app-layout>
    <x-slot name="title">Settings - {{ $company->name }}</x-slot>

    <x-pines.page-header title="Settings" description="Manage your company settings" />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('app.settings.company', $company) }}" class="block bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Company</h3>
                    <p class="text-sm text-gray-500">Edit company details</p>
                </div>
            </div>
        </a>

        <a href="{{ route('app.settings.invoice', $company) }}" class="block bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Invoice</h3>
                    <p class="text-sm text-gray-500">Invoice numbering & defaults</p>
                </div>
            </div>
        </a>

        <a href="{{ route('app.settings.currencies', $company) }}" class="block bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Currencies</h3>
                    <p class="text-sm text-gray-500">Manage currencies</p>
                </div>
            </div>
        </a>

        <a href="{{ route('app.settings.bank-accounts', $company) }}" class="block bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Bank Accounts</h3>
                    <p class="text-sm text-gray-500">Manage bank accounts</p>
                </div>
            </div>
        </a>

        <a href="{{ route('app.settings.email-templates', $company) }}" class="block bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Email Templates</h3>
                    <p class="text-sm text-gray-500">Manage email templates</p>
                </div>
            </div>
        </a>

        <a href="{{ route('app.settings.email-signatures', $company) }}" class="block bg-white shadow rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-pink-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Email Signatures</h3>
                    <p class="text-sm text-gray-500">Manage email signatures</p>
                </div>
            </div>
        </a>
    </div>
</x-app-layout>
