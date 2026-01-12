@extends('layouts.app')

@section('title', 'Company Settings - ' . $company->name)
@section('page-title', 'Company Profile')
@section('page-subtitle', 'Manage your business identity and contact information')

@section('content')
<x-app.settings-layout :company="$company" active="company">
    <form action="{{ route('app.settings.company.update', $company) }}" method="POST" class="max-w-4xl">
        @csrf
        @method('PUT')
        
        <x-app.card>
            <div class="flex items-center justify-between mb-6">
                <x-app.section-header title="Business Information" subtitle="Publicly shared details on your documents" icon="building-office-2" variant="primary" />
                <x-app.button type="submit" variant="primary" size="sm">
                    Save Changes
                </x-app.button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="md:col-span-2">
                    <x-app.input label="Company Legal Name" name="name" value="{{ old('name', $company->name) }}" placeholder="e.g. Acme Corporation LLC" required />
                </div>

                <x-app.input label="Business Email" name="email" type="email" value="{{ old('email', $company->email) }}" placeholder="contact@company.com" />
                <x-app.input label="Phone Number" name="phone" value="{{ old('phone', $company->phone) }}" placeholder="+1 (555) 000-0000" />

                <div class="md:col-span-2">
                    <x-app.input label="Street Address" name="address" value="{{ old('address', $company->address) }}" placeholder="123 Business St" />
                </div>

                <x-app.input label="City" name="city" value="{{ old('city', $company->city) }}" placeholder="New York" />
                <x-app.input label="Postal / Zip Code" name="postal_code" value="{{ old('postal_code', $company->postal_code) }}" placeholder="10001" />

                <x-app.input label="Country" name="country" value="{{ old('country', $company->country) }}" placeholder="United States" />
                <x-app.input label="VAT / Tax Number" name="vat_number" value="{{ old('vat_number', $company->vat_number) }}" placeholder="US123456789" />
            </div>
        </x-app.card>
    </form>
</x-app.settings-layout>
@endsection
