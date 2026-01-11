<x-app-layout>
    <x-slot name="title">Currencies - {{ $company->name }}</x-slot>

    <x-pines.page-header 
        title="Currencies" 
        :breadcrumbs="[
            ['label' => 'Settings', 'url' => route('app.settings.index', $company)],
            ['label' => 'Currencies']
        ]"
    />

    <div class="max-w-3xl">
        <x-pines.table>
            <x-slot name="head">
                <tr>
                    <x-pines.th>Code</x-pines.th>
                    <x-pines.th>Name</x-pines.th>
                    <x-pines.th>Symbol</x-pines.th>
                </tr>
            </x-slot>

            @forelse($currencies as $currency)
                <tr class="hover:bg-neutral-50">
                    <x-pines.td class="font-medium">{{ $currency->code }}</x-pines.td>
                    <x-pines.td>{{ $currency->name }}</x-pines.td>
                    <x-pines.td>{{ $currency->symbol }}</x-pines.td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        <x-pines.empty-state title="No currencies" description="Currencies can be configured in Filament admin." />
                    </td>
                </tr>
            @endforelse
        </x-pines.table>
    </div>
</x-app-layout>
