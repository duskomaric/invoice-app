<x-app-layout>
    <x-slot name="title">Income Book - {{ $company->name }}</x-slot>

    <div class="mb-6">
        <nav class="flex mb-2 text-sm text-gray-500">
            <a href="{{ route('app.reports.index', $company) }}" class="hover:text-gray-700">Reports</a>
            <span class="mx-1">/</span>
            <span class="text-gray-900">Income Book</span>
        </nav>
        <h1 class="text-2xl font-semibold text-gray-900">Income Book (KPI)</h1>
    </div>

    <div class="mb-6 flex items-center space-x-4">
        <form action="" method="GET" class="flex items-center space-x-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Year</label>
                <select name="year" onchange="this.form.submit()" class="h-10 px-3 text-sm border rounded-md border-neutral-300">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Month</label>
                <select name="month" onchange="this.form.submit()" class="h-10 px-3 text-sm border rounded-md border-neutral-300">
                    <option value="">All months</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                    @endfor
                </select>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-sm text-gray-500">Cash</div>
            <div class="text-2xl font-semibold text-gray-900">{{ number_format($totals['cash'] / 100, 2) }}</div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-sm text-gray-500">Bank</div>
            <div class="text-2xl font-semibold text-gray-900">{{ number_format($totals['bank'] / 100, 2) }}</div>
        </div>
        <div class="bg-white shadow rounded-lg p-4">
            <div class="text-sm text-gray-500">Total</div>
            <div class="text-2xl font-semibold text-green-600">{{ number_format($totals['total'] / 100, 2) }}</div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Document</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($entries as $entry)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $entry->date?->format('d.m.Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $entry->document_number ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $entry->description ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $entry->payment_method ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-right text-green-600">{{ number_format($entry->amount / 100, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">No entries for this period.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($entries->hasPages())<div class="px-6 py-4 border-t">{{ $entries->links() }}</div>@endif
    </div>
</x-app-layout>
