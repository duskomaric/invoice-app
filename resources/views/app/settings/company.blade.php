<x-app-layout>
    <x-slot name="title">Company Settings - {{ $company->name }}</x-slot>

    <x-pines.page-header 
        title="Company Settings" 
        :breadcrumbs="[
            ['label' => 'Settings', 'url' => route('app.settings.index', $company)],
            ['label' => 'Company']
        ]"
    />

    <form action="{{ route('app.settings.company.update', $company) }}" method="POST">
        @csrf @method('PUT')
        <div class="max-w-2xl space-y-6">
            <x-pines.form-section title="Basic Information" :columns="2">
                <div class="col-span-2">
                    <x-pines.input label="Company Name" name="name" :value="$company->name" required />
                </div>
                <x-pines.input type="email" label="Email" name="email" :value="$company->email" />
                <x-pines.input label="Phone" name="phone" :value="$company->phone" />
                <div class="col-span-2">
                    <x-pines.input label="VAT Number" name="vat_number" :value="$company->vat_number" />
                </div>
            </x-pines.form-section>

            <x-pines.form-section title="Address" :columns="2">
                <div class="col-span-2">
                    <x-pines.input label="Address" name="address" :value="$company->address" />
                </div>
                <x-pines.input label="City" name="city" :value="$company->city" />
                <x-pines.input label="Postal Code" name="postal_code" :value="$company->postal_code" />
                <div class="col-span-2">
                    <x-pines.input label="Country" name="country" :value="$company->country" />
                </div>
            </x-pines.form-section>

            <div class="flex justify-end gap-3">
                <x-pines.button variant="secondary" href="{{ route('app.settings.index', $company) }}">Cancel</x-pines.button>
                <x-pines.button type="submit">Save Changes</x-pines.button>
            </div>
        </div>
    </form>
</x-app-layout>
