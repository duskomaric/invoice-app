<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\Currency;
use App\Models\Invoice;
use App\Services\DocumentNumberingService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request, Company $company)
    {
        $currencies = Currency::where('company_id', $company->id)->orderBy('code')->pluck('code')->toArray();
        $activeCurrency = $request->get('currency', $currencies[0] ?? null);

        $query = Invoice::where('company_id', $company->id)->with('client');

        if ($activeCurrency) {
            $query->where('currency', $activeCurrency);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('client', fn($c) => $c->where('name', 'like', "%{$search}%"))
                  ->orWhereRaw("CONCAT(COALESCE(invoice_prefix, ''), '-', invoice_number, '/', invoice_year) LIKE ?", ["%{$search}%"]);
            });
        }

        $invoices = $query->latest('date')->paginate(20)->withQueryString();

        return view('app.invoices.index', compact('company', 'invoices', 'currencies', 'activeCurrency'));
    }

    public function create(Company $company)
    {
        $clients = Client::where('company_id', $company->id)->orderBy('name')->get();
        $currencies = Currency::where('company_id', $company->id)->orderBy('code')->get();
        $bankAccounts = $company->bankAccounts()->get();
        $articles = $company->articles()->where('is_active', true)->orderBy('name')->get();

        $numbering = app(DocumentNumberingService::class);
        $previewNumber = $numbering->previewForCompany(
            $company->id,
            Invoice::class,
            ['prefix' => 'invoice_prefix', 'year' => 'invoice_year', 'number' => 'invoice_number']
        );

        $defaults = [
            'currency' => CompanySetting::get('default_invoice_currency', 'BAM', $company->id),
            'language' => CompanySetting::get('default_invoice_language', 'en', $company->id),
            'template' => CompanySetting::get('default_invoice_template', 'classic', $company->id),
            'due_days' => (int) CompanySetting::get('default_invoice_due_days', 14, $company->id),
            'bank_account_id' => (int) CompanySetting::get('default_company_bank_account_id', 0, $company->id),
        ];

        return view('app.invoices.create', compact('company', 'clients', 'currencies', 'bankAccounts', 'articles', 'previewNumber', 'defaults'));
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
            'items.*.article_id' => 'nullable|integer',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0',
        ]);

        $invoice = Invoice::create([
            'company_id' => $company->id,
            'client_id' => $validated['client_id'],
            'status' => 'unpaid',
            'date' => $validated['date'],
            'due_date' => $validated['due_date'] ?? now()->addDays((int) CompanySetting::get('default_invoice_due_days', 14, $company->id)),
            'currency' => $validated['currency'],
            'language' => $validated['language'],
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            $unitPriceCents = (int) round($item['unit_price'] * 100);
            $taxRate = $item['tax_rate'] ?? 17;
            
            $invoice->items()->create([
                'article_id' => $item['article_id'] ?? null,
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPriceCents,
                'total' => (int) round($unitPriceCents * $item['quantity']),
                'tax_rate' => $taxRate,
            ]);
        }

        return redirect()
            ->route('app.invoices.show', [$company, $invoice])
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Company $company, Invoice $invoice)
    {
        $invoice->load('client', 'items');

        return view('app.invoices.show', compact('company', 'invoice'));
    }

    public function edit(Company $company, Invoice $invoice)
    {
        $invoice->load('items');
        $clients = Client::where('company_id', $company->id)->orderBy('name')->get();
        $currencies = Currency::where('company_id', $company->id)->orderBy('code')->get();

        return view('app.invoices.edit', compact('company', 'invoice', 'clients', 'currencies'));
    }

    public function update(Request $request, Company $company, Invoice $invoice)
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
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'client_id' => $validated['client_id'],
            'date' => $validated['date'],
            'due_date' => $validated['due_date'],
            'currency' => $validated['currency'],
            'language' => $validated['language'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $invoice->items()->delete();

        foreach ($validated['items'] as $item) {
            $unitPrice = (int) round($item['unit_price'] * 100);
            $invoice->items()->create([
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'total' => $unitPrice * $item['quantity'],
                'tax_rate' => $item['tax_rate'] ?? 0,
            ]);
        }

        return redirect()
            ->route('app.invoices.show', [$company, $invoice])
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Company $company, Invoice $invoice)
    {
        $invoice->items()->delete();
        $invoice->delete();

        return redirect()
            ->route('app.invoices.index', $company)
            ->with('success', 'Invoice deleted successfully.');
    }

    public function pdf(Company $company, Invoice $invoice)
    {
        // TODO: Implement PDF generation
        return response()->json(['message' => 'PDF generation not implemented yet']);
    }

    public function send(Company $company, Invoice $invoice)
    {
        // TODO: Implement email sending
        return redirect()
            ->route('app.invoices.show', [$company, $invoice])
            ->with('success', 'Invoice sent successfully.');
    }
}
