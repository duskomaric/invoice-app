<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\Currency;
use App\Models\Proforma;
use App\Services\DocumentConversionService;
use App\Services\DocumentNumberingService;
use Illuminate\Http\Request;

class ProformaController extends Controller
{
    public function index(Company $company)
    {
        $proformas = Proforma::where('company_id', $company->id)
            ->with('client')
            ->latest()
            ->paginate(20);

        return view('app.proformas.index', compact('company', 'proformas'));
    }

    public function create(Company $company)
    {
        $clients = Client::where('company_id', $company->id)->orderBy('name')->get();
        $currencies = Currency::where('company_id', $company->id)->orderBy('code')->get();

        $numbering = app(DocumentNumberingService::class);
        $previewNumber = $numbering->previewForCompany(
            $company->id,
            Proforma::class,
            ['prefix' => 'proforma_prefix', 'year' => 'proforma_year', 'number' => 'proforma_number']
        );

        return view('app.proformas.create', compact('company', 'clients', 'currencies', 'previewNumber'));
    }

    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'due_date' => 'nullable|date',
            'currency' => 'required|string',
            'language' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.article_id' => 'nullable|exists:articles,id',
            'items.*.name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0',
        ]);

        $proforma = Proforma::create([
            'company_id' => $company->id,
            'client_id' => $validated['client_id'],
            'status' => 'draft',
            'date' => $validated['date'],
            'due_date' => $validated['due_date'] ?? now()->addDays((int) CompanySetting::get('default_invoice_due_days', 14, $company->id)),
            'currency' => $validated['currency'],
            'language' => $validated['language'],
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            $unitPriceInCents = (int) round($item['unit_price'] * 100);
            $proforma->items()->create([
                'article_id' => $item['article_id'] ?? null,
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPriceInCents,
                'total' => $unitPriceInCents * $item['quantity'],
                'tax_rate' => $item['tax_rate'],
            ]);
        }

        return redirect()
            ->route('app.proformas.show', [$company, $proforma])
            ->with('success', 'Proforma created successfully.');
    }

    public function show(Company $company, Proforma $proforma)
    {
        $proforma->load('client', 'items');

        return view('app.proformas.show', compact('company', 'proforma'));
    }

    public function edit(Company $company, Proforma $proforma)
    {
        $proforma->load('items');
        $clients = Client::where('company_id', $company->id)->orderBy('name')->get();
        $currencies = Currency::where('company_id', $company->id)->orderBy('code')->get();

        return view('app.proformas.edit', compact('company', 'proforma', 'clients', 'currencies'));
    }

    public function update(Request $request, Company $company, Proforma $proforma)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'due_date' => 'nullable|date',
            'currency' => 'required|string',
            'language' => 'required|string',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.article_id' => 'nullable|exists:articles,id',
            'items.*.name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0',
        ]);

        $proforma->update([
            'client_id' => $validated['client_id'],
            'date' => $validated['date'],
            'due_date' => $validated['due_date'],
            'currency' => $validated['currency'],
            'language' => $validated['language'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $proforma->items()->delete();

        foreach ($validated['items'] as $item) {
            $unitPriceInCents = (int) round($item['unit_price'] * 100);
            $proforma->items()->create([
                'article_id' => $item['article_id'] ?? null,
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPriceInCents,
                'total' => $unitPriceInCents * $item['quantity'],
                'tax_rate' => $item['tax_rate'],
            ]);
        }

        return redirect()
            ->route('app.proformas.show', [$company, $proforma])
            ->with('success', 'Proforma updated successfully.');
    }

    public function destroy(Company $company, Proforma $proforma)
    {
        $proforma->items()->delete();
        $proforma->delete();

        return redirect()
            ->route('app.proformas.index', $company)
            ->with('success', 'Proforma deleted successfully.');
    }

    public function convertToInvoice(Company $company, Proforma $proforma)
    {
        $invoice = app(DocumentConversionService::class)->convertProformaToInvoice($proforma);

        return redirect()
            ->route('app.invoices.show', [$company, $invoice])
            ->with('success', 'Proforma converted to invoice successfully.');
    }
}
