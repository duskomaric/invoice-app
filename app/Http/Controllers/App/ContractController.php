<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Currency;
use App\Services\DocumentConversionService;
use App\Services\DocumentNumberingService;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index(Company $company)
    {
        $contracts = Contract::where('company_id', $company->id)
            ->with('client')
            ->latest()
            ->paginate(20);

        return view('app.contracts.index', compact('company', 'contracts'));
    }

    public function create(Company $company)
    {
        $clients = Client::where('company_id', $company->id)->orderBy('name')->get();
        $currencies = Currency::where('company_id', $company->id)->orderBy('code')->get();

        $numbering = app(DocumentNumberingService::class);
        $previewNumber = $numbering->previewForCompany(
            $company->id,
            Contract::class,
            ['prefix' => 'contract_prefix', 'year' => 'contract_year', 'number' => 'contract_number']
        );

        return view('app.contracts.create', compact('company', 'clients', 'currencies', 'previewNumber'));
    }

    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'currency' => 'required|string',
            'language' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $contract = Contract::create([
            'company_id' => $company->id,
            'client_id' => $validated['client_id'],
            'status' => 'draft',
            'date' => $validated['date'],
            'currency' => $validated['currency'],
            'language' => $validated['language'],
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            $unitPrice = (int) round($item['unit_price'] * 100);
            $contract->items()->create([
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'total' => $unitPrice * $item['quantity'],
                'tax_rate' => $item['tax_rate'] ?? 0,
            ]);
        }

        return redirect()
            ->route('app.contracts.show', [$company, $contract])
            ->with('success', 'Contract created successfully.');
    }

    public function show(Company $company, Contract $contract)
    {
        $contract->load('client', 'items');

        return view('app.contracts.show', compact('company', 'contract'));
    }

    public function edit(Company $company, Contract $contract)
    {
        $contract->load('items');
        $clients = Client::where('company_id', $company->id)->orderBy('name')->get();
        $currencies = Currency::where('company_id', $company->id)->orderBy('code')->get();

        return view('app.contracts.edit', compact('company', 'contract', 'clients', 'currencies'));
    }

    public function update(Request $request, Company $company, Contract $contract)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'currency' => 'required|string',
            'language' => 'required|string',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $contract->update([
            'client_id' => $validated['client_id'],
            'date' => $validated['date'],
            'currency' => $validated['currency'],
            'language' => $validated['language'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $contract->items()->delete();

        foreach ($validated['items'] as $item) {
            $unitPrice = (int) round($item['unit_price'] * 100);
            $contract->items()->create([
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'total' => $unitPrice * $item['quantity'],
                'tax_rate' => $item['tax_rate'] ?? 0,
            ]);
        }

        return redirect()
            ->route('app.contracts.show', [$company, $contract])
            ->with('success', 'Contract updated successfully.');
    }

    public function destroy(Company $company, Contract $contract)
    {
        $contract->items()->delete();
        $contract->delete();

        return redirect()
            ->route('app.contracts.index', $company)
            ->with('success', 'Contract deleted successfully.');
    }

    public function convertToInvoice(Company $company, Contract $contract)
    {
        $invoice = app(DocumentConversionService::class)->convertContractToInvoice($contract);

        return redirect()
            ->route('app.invoices.show', [$company, $invoice])
            ->with('success', 'Contract converted to invoice successfully.');
    }
}
