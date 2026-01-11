<x-app-layout>
    <x-slot name="title">Invoice Settings - {{ $company->name }}</x-slot>

    <x-pines.page-header 
        title="Invoice Settings" 
        :breadcrumbs="[
            ['label' => 'Settings', 'url' => route('app.settings.index', $company)],
            ['label' => 'Invoice']
        ]"
    />

    <form action="{{ route('app.settings.invoice.update', $company) }}" method="POST">
        @csrf @method('PUT')
        <div class="max-w-2xl space-y-6">
            <x-pines.form-section title="Default Values" :columns="2">
                <x-pines.select 
                    label="Default Template" 
                    name="default_invoice_template" 
                    :options="collect($templates)->mapWithKeys(fn($t) => [$t->value => $t->name])->toArray()"
                    :value="$settings['default_invoice_template']"
                />
                <x-pines.select 
                    label="Default Language" 
                    name="default_invoice_language" 
                    :options="collect($languages)->mapWithKeys(fn($l) => [$l->value => $l->name])->toArray()"
                    :value="$settings['default_invoice_language']"
                />
                <x-pines.input 
                    type="number" 
                    label="Default Due Days" 
                    name="default_invoice_due_days" 
                    :value="$settings['default_invoice_due_days']" 
                    min="0" 
                />
                <x-pines.select 
                    label="Default Currency" 
                    name="default_invoice_currency" 
                    :options="$currencies->pluck('code', 'code')->toArray()"
                    :value="$settings['default_invoice_currency']"
                />
            </x-pines.form-section>

            <x-pines.form-section title="Invoice Numbering" :columns="2">
                <x-pines.select 
                    label="Numbering Prefix" 
                    name="invoice_numbering_prefix" 
                    :options="['none' => 'None', 'currency' => 'Currency Code']"
                    :value="$settings['invoice_numbering_prefix']"
                />
                <x-pines.input 
                    type="number" 
                    label="Starting Number" 
                    name="invoice_numbering_starting_number" 
                    :value="$settings['invoice_numbering_starting_number']" 
                    min="1" 
                />
                <x-pines.input 
                    type="number" 
                    label="Pad Zeros" 
                    name="invoice_numbering_pad_zeros" 
                    :value="$settings['invoice_numbering_pad_zeros']" 
                    min="1" 
                    max="10" 
                />
                <div class="flex items-center pt-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="hidden" name="invoice_numbering_reset_yearly" value="0">
                        <input type="checkbox" name="invoice_numbering_reset_yearly" value="1" {{ $settings['invoice_numbering_reset_yearly'] ? 'checked' : '' }} class="h-4 w-4 rounded border-neutral-300 text-neutral-900 focus:ring-neutral-500">
                        <span class="ml-2 text-sm text-neutral-700">Reset numbering yearly</span>
                    </label>
                </div>
            </x-pines.form-section>

            <div class="flex justify-end gap-3">
                <x-pines.button variant="secondary" href="{{ route('app.settings.index', $company) }}">Cancel</x-pines.button>
                <x-pines.button type="submit">Save Changes</x-pines.button>
            </div>
        </div>
    </form>
</x-app-layout>
