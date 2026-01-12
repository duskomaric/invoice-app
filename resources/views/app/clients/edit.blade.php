@extends('layouts.app')

@section('title', 'Edit ' . $client->name . ' - ' . $company->name)
@section('page-title', 'Edit Client')
@section('page-subtitle', $client->name)

@section('content')
<form action="{{ route('app.clients.update', [$company, $client]) }}" method="POST" class="max-w-4xl mx-auto">
    @csrf
    @method('PUT')
    
    <div class="space-y-6">
        <x-app.card>
            <x-app.section-header title="Basic Information" subtitle="Client contact details" icon="user" variant="primary" />
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                <div class="md:col-span-2">
                    <x-app.input label="Full Name / Company Name" name="name" placeholder="e.g. Acme Corp" required :value="old('name', $client->name)" />
                </div>
                
                <div>
                    <x-app.input label="Email Address" name="email" type="email" placeholder="billing@client.com" :value="old('email', $client->email)" />
                </div>

                <div>
                    <x-app.input label="Phone Number" name="phone" placeholder="+1 234 567 890" :value="old('phone', $client->phone)" />
                </div>

                <div class="md:col-span-2">
                    <x-app.input label="VAT / Tax Number" name="vat_number" placeholder="e.g. US123456789" :value="old('vat_number', $client->vat_number)" />
                </div>
            </div>
        </x-app.card>

        <x-app.card>
            <x-app.section-header title="Address & Location" subtitle="Physical billing address" icon="map-pin" variant="secondary" />
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                <div class="md:col-span-2">
                    <x-app.input label="Steet Address" name="address" placeholder="123 Main St" :value="old('address', $client->address)" />
                </div>

                <div>
                    <x-app.input label="City" name="city" placeholder="New York" :value="old('city', $client->city)" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <x-app.input label="Postal Code" name="postal_code" placeholder="10001" :value="old('postal_code', $client->postal_code)" />
                    <x-app.input label="Country" name="country" placeholder="USA" :value="old('country', $client->country)" />
                </div>
            </div>
        </x-app.card>

        <x-app.card>
            <x-app.section-header title="Additional Notes" subtitle="Internal notes about this client" icon="document-text" variant="warning" />
            <div class="mt-6">
                <textarea name="notes" rows="4" class="w-full rounded-2xl bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-sm text-slate-800 dark:text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 transition-all p-4" placeholder="Any private notes for your team...">{{ old('notes', $client->notes) }}</textarea>
            </div>
        </x-app.card>

        <div class="flex items-center justify-end gap-3 pb-12">
            <x-app.button :href="route('app.clients.index', $company)" variant="secondary">Cancel</x-app.button>
            <x-app.button type="submit" variant="primary">Update Client</x-app.button>
        </div>
    </div>
</form>
@endsection
