<x-app-layout>
    <x-slot name="title">Bank Accounts - {{ $company->name }}</x-slot>

    <x-pines.page-header 
        title="Bank Accounts" 
        :breadcrumbs="[
            ['label' => 'Settings', 'url' => route('app.settings.index', $company)],
            ['label' => 'Bank Accounts']
        ]"
    />

    <div class="max-w-5xl">
        <x-pines.table>
            <x-slot name="head">
                <tr>
                    <x-pines.th>Bank Name</x-pines.th>
                    <x-pines.th>Account Number</x-pines.th>
                    <x-pines.th>IBAN</x-pines.th>
                    <x-pines.th>Currency</x-pines.th>
                </tr>
            </x-slot>

            @forelse($bankAccounts as $account)
                <tr class="hover:bg-neutral-50">
                    <x-pines.td class="font-medium">{{ $account->bank_name }}</x-pines.td>
                    <x-pines.td>{{ $account->account_number }}</x-pines.td>
                    <x-pines.td class="font-mono text-xs">{{ $account->iban }}</x-pines.td>
                    <x-pines.td>
                        <x-pines.badge variant="default">{{ $account->currency }}</x-pines.badge>
                    </x-pines.td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">
                        <x-pines.empty-state title="No bank accounts" description="Bank accounts can be configured in Filament admin." />
                    </td>
                </tr>
            @endforelse
        </x-pines.table>
    </div>
</x-app-layout>
