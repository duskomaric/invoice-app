<x-app-layout>
    <x-slot name="title">Email Templates - {{ $company->name }}</x-slot>

    <x-pines.page-header 
        title="Email Templates" 
        :breadcrumbs="[
            ['label' => 'Settings', 'url' => route('app.settings.index', $company)],
            ['label' => 'Email Templates']
        ]"
    />

    <div class="max-w-5xl">
        <x-pines.table>
            <x-slot name="head">
                <tr>
                    <x-pines.th>Name</x-pines.th>
                    <x-pines.th>Subject</x-pines.th>
                    <x-pines.th>Type</x-pines.th>
                    <x-pines.th>Default</x-pines.th>
                    <x-pines.th align="right">Actions</x-pines.th>
                </tr>
            </x-slot>

            @forelse($templates as $template)
                <tr class="hover:bg-neutral-50">
                    <x-pines.td class="font-medium">{{ $template->name }}</x-pines.td>
                    <x-pines.td>{{ $template->subject }}</x-pines.td>
                    <x-pines.td>
                        <x-pines.badge variant="info">{{ ucfirst($template->type ?? 'general') }}</x-pines.badge>
                    </x-pines.td>
                    <x-pines.td>
                        @if($template->is_default)
                            <x-pines.badge variant="success">Default</x-pines.badge>
                        @endif
                    </x-pines.td>
                    <x-pines.td align="right">
                        <span class="text-neutral-400 text-sm">-</span>
                    </x-pines.td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <x-pines.empty-state title="No email templates" description="Email templates can be configured in Filament admin." />
                    </td>
                </tr>
            @endforelse
        </x-pines.table>
    </div>
</x-app-layout>
