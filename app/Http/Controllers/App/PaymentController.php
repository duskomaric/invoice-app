<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Company $company)
    {
        $payments = $company->payments()
            ->with(['client', 'invoice'])
            ->latest('payment_date')
            ->paginate(20);

        return view('app.payments.index', compact('company', 'payments'));
    }

    public function create(Company $company)
    {
        $clients = $company->clients()->orderBy('name')->get();
        $invoices = $company->invoices()->orderByDesc('date')->get();

        return view('app.payments.create', compact('company', 'clients', 'invoices'));
    }

    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:100',
            'type' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $payment = $company->payments()->create([
            'client_id' => $validated['client_id'],
            'invoice_id' => $validated['invoice_id'],
            'amount' => (int) ($validated['amount'] * 100),
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'type' => $validated['type'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('app.payments.show', [$company, $payment])
            ->with('success', 'Payment recorded.');
    }

    public function show(Company $company, Payment $payment)
    {
        $payment->load(['client', 'invoice']);

        return view('app.payments.show', compact('company', 'payment'));
    }

    public function edit(Company $company, Payment $payment)
    {
        $clients = $company->clients()->orderBy('name')->get();
        $invoices = $company->invoices()->orderByDesc('date')->get();

        return view('app.payments.edit', compact('company', 'payment', 'clients', 'invoices'));
    }

    public function update(Request $request, Company $company, Payment $payment)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:100',
            'type' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $payment->update([
            'client_id' => $validated['client_id'],
            'invoice_id' => $validated['invoice_id'],
            'amount' => (int) ($validated['amount'] * 100),
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'type' => $validated['type'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('app.payments.show', [$company, $payment])
            ->with('success', 'Payment updated.');
    }

    public function destroy(Company $company, Payment $payment)
    {
        $payment->delete();

        return redirect()->route('app.payments.index', $company)
            ->with('success', 'Payment deleted.');
    }
}
