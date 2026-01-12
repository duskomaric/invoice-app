<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Quote;
use App\Services\DocumentConversionService;
use App\Services\DocumentNumberingService;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Company $company)
    {
        $quotes = Quote::where('company_id', $company->id)
            ->with('client')
            ->latest()
            ->paginate(20);

        return view('app.quotes.index', compact('company', 'quotes'));
    }

    public function create(Company $company)
    {
        $clients = Client::where('company_id', $company->id)->orderBy('name')->get();
        $currencies = Currency::where('company_id', $company->id)->orderBy('code')->get();

        $numbering = app(DocumentNumberingService::class);
        $previewNumber = $numbering->previewForCompany(
            $company->id,
            Quote::class,
            ['prefix' => 'quote_prefix', 'year' => 'quote_year', 'number' => 'quote_number']
        );

        return view('app.quotes.create', compact('company', 'clients', 'currencies', 'previewNumber'));
    }

    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'valid_until' => 'nullable|date',
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

        $quote = Quote::create([
            'company_id' => $company->id,
            'client_id' => $validated['client_id'],
            'status' => 'draft',
            'date' => $validated['date'],
            'valid_until' => $validated['valid_until'] ?? now()->addDays(30),
            'currency' => $validated['currency'],
            'language' => $validated['language'],
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            $unitPriceInCents = (int) round($item['unit_price'] * 100);
            $quote->items()->create([
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
            ->route('app.quotes.show', [$company, $quote])
            ->with('success', 'Quote created successfully.');
    }

    public function show(Company $company, Quote $quote)
    {
        $quote->load('client', 'items');

        return view('app.quotes.show', compact('company', 'quote'));
    }

    public function edit(Company $company, Quote $quote)
    {
        $quote->load('items');
        $clients = Client::where('company_id', $company->id)->orderBy('name')->get();
        $currencies = Currency::where('company_id', $company->id)->orderBy('code')->get();

        return view('app.quotes.edit', compact('company', 'quote', 'clients', 'currencies'));
    }

    public function update(Request $request, Company $company, Quote $quote)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'valid_until' => 'nullable|date',
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

        $quote->update([
            'client_id' => $validated['client_id'],
            'date' => $validated['date'],
            'valid_until' => $validated['valid_until'],
            'currency' => $validated['currency'],
            'language' => $validated['language'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $quote->items()->delete();

        foreach ($validated['items'] as $item) {
            $unitPriceInCents = (int) round($item['unit_price'] * 100);
            $quote->items()->create([
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
            ->route('app.quotes.show', [$company, $quote])
            ->with('success', 'Quote updated successfully.');
    }

    public function destroy(Company $company, Quote $quote)
    {
        $quote->items()->delete();
        $quote->delete();

        return redirect()
            ->route('app.quotes.index', $company)
            ->with('success', 'Quote deleted successfully.');
    }

    public function convertToProforma(Company $company, Quote $quote)
    {
        $proforma = app(DocumentConversionService::class)->convertQuoteToProforma($quote);

        return redirect()
            ->route('app.proformas.show', [$company, $proforma])
            ->with('success', 'Quote converted to proforma successfully.');
    }

    public function convertToInvoice(Company $company, Quote $quote)
    {
        $invoice = app(DocumentConversionService::class)->convertQuoteToInvoice($quote);

        return redirect()
            ->route('app.invoices.show', [$company, $invoice])
            ->with('success', 'Quote converted to invoice successfully.');
    }
}
