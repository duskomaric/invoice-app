<x-app-layout>
    <x-slot name="title">Email Signatures - {{ $company->name }}</x-slot>

    <x-pines.page-header 
        title="Email Signatures" 
        :breadcrumbs="[
            ['label' => 'Settings', 'url' => route('app.settings.index', $company)],
            ['label' => 'Email Signatures']
        ]"
    />

    <div class="max-w-4xl">
        <x-pines.table>
            <x-slot name="head">
                <tr>
                    <x-pines.th>Name</x-pines.th>
                    <x-pines.th>Default</x-pines.th>
                    <x-pines.th align="right">Actions</x-pines.th>
                </tr>
            </x-slot>

            @forelse($signatures as $signature)
                <tr class="hover:bg-neutral-50">
                    <x-pines.td class="font-medium">{{ $signature->name }}</x-pines.td>
                    <x-pines.td>
                        @if($signature->is_default)
                            <x-pines.badge variant="success">Default</x-pines.badge>
                        @endif
                    </x-pines.td>
                    <x-pines.td align="right">
                        <span class="text-neutral-400 text-sm">-</span>
                    </x-pines.td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        <x-pines.empty-state title="No email signatures" description="Email signatures can be configured in Filament admin." />
                    </td>
                </tr>
            @endforelse
        </x-pines.table>
    </div>
</x-app-layout>
