<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Company $company)
    {
        $clients = Client::where('company_id', $company->id)
            ->latest()
            ->paginate(20);

        return view('app.clients.index', compact('company', 'clients'));
    }

    public function create(Company $company)
    {
        return view('app.clients.create', compact('company'));
    }

    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'vat_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $client = Client::create([
            'company_id' => $company->id,
            ...$validated,
        ]);

        return redirect()
            ->route('app.clients.show', [$company, $client])
            ->with('success', 'Client created successfully.');
    }

    public function show(Company $company, Client $client)
    {
        $client->load(['invoices' => fn ($q) => $q->latest()->take(5)]);

        return view('app.clients.show', compact('company', 'client'));
    }

    public function edit(Company $company, Client $client)
    {
        return view('app.clients.edit', compact('company', 'client'));
    }

    public function update(Request $request, Company $company, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'vat_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()
            ->route('app.clients.show', [$company, $client])
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Company $company, Client $client)
    {
        $client->delete();

        return redirect()
            ->route('app.clients.index', $company)
            ->with('success', 'Client deleted successfully.');
    }
}
