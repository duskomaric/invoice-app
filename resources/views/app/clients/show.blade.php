<x-app-layout>
    <x-slot name="title">{{ $client->name }} - {{ $company->name }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <nav class="flex mb-2 text-sm text-gray-500">
                <a href="{{ route('app.clients.index', $company) }}" class="hover:text-gray-700">Clients</a>
                <span class="mx-1">/</span>
                <span class="text-gray-900">{{ $client->name }}</span>
            </nav>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $client->name }}</h1>
        </div>
        <a href="{{ route('app.clients.edit', [$company, $client]) }}" class="px-4 py-2 text-sm font-medium text-white bg-neutral-950 rounded-md hover:bg-neutral-900">Edit</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Contact Information</h3>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-500">Email</dt><dd class="font-medium">{{ $client->email ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Phone</dt><dd class="font-medium">{{ $client->phone ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">VAT Number</dt><dd class="font-medium">{{ $client->vat_number ?? '-' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Address</h3>
                <div class="text-sm">
                    @if($client->address)<p>{{ $client->address }}</p>@endif
                    @if($client->postal_code || $client->city)<p>{{ $client->postal_code }} {{ $client->city }}</p>@endif
                    @if($client->country)<p>{{ $client->country }}</p>@endif
                    @if(!$client->address && !$client->city)<p class="text-gray-500">No address</p>@endif
                </div>
            </div>

            @if($client->notes)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Notes</h3>
                <p class="text-sm text-gray-600">{{ $client->notes }}</p>
            </div>
            @endif

            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b"><h3 class="text-lg font-medium">Recent Invoices</h3></div>
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="divide-y divide-gray-200">
                        @forelse($client->invoices as $invoice)
                            <tr>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('app.invoices.show', [$company, $invoice]) }}" class="text-indigo-600 hover:text-indigo-900">{{ $invoice->formatted_number }}</a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $invoice->date?->format('d.m.Y') }}</td>
                                <td class="px-6 py-4 text-sm text-right">{{ number_format($invoice->total / 100, 2) }} {{ $invoice->currency }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-6 py-4 text-sm text-gray-500 text-center">No invoices</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 h-fit">
            <h3 class="text-sm font-medium text-gray-500 uppercase mb-4">Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('app.invoices.create', $company) }}?client_id={{ $client->id }}" class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">New Invoice</a>
                <form action="{{ route('app.clients.destroy', [$company, $client]) }}" method="POST" onsubmit="return confirm('Delete this client?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100">Delete Client</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
