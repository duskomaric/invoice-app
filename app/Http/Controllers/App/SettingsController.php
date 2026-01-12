<?php

namespace App\Http\Controllers\App;

use App\Enums\InvoiceTemplateEnum;
use App\Enums\LanguageEnum;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyBankAccount;
use App\Models\CompanySetting;
use App\Models\Currency;
use App\Models\EmailSignature;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Company $company)
    {
        return view('app.settings.index', compact('company'));
    }

    public function company(Company $company)
    {
        return view('app.settings.company', compact('company'));
    }

    public function updateCompany(Request $request, Company $company)
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
        ]);

        $company->update($validated);

        return redirect()
            ->route('app.settings.company', $company)
            ->with('success', 'Company settings updated successfully.');
    }

    public function invoice(Company $company)
    {
        $settings = [
            'default_invoice_template' => CompanySetting::get('default_invoice_template', InvoiceTemplateEnum::Classic->value, $company->id),
            'default_invoice_language' => CompanySetting::get('default_invoice_language', LanguageEnum::English->value, $company->id),
            'default_invoice_due_days' => (int) CompanySetting::get('default_invoice_due_days', 14, $company->id),
            'default_invoice_currency' => CompanySetting::get('default_invoice_currency', 'BAM', $company->id),
            'invoice_numbering_reset_yearly' => (bool) CompanySetting::get('invoice_numbering_reset_yearly', true, $company->id),
            'invoice_numbering_pad_zeros' => (int) CompanySetting::get('invoice_numbering_pad_zeros', 3, $company->id),
            'invoice_numbering_starting_number' => (int) CompanySetting::get('invoice_numbering_starting_number', 1, $company->id),
            'invoice_numbering_prefix' => CompanySetting::get('invoice_numbering_prefix', 'currency', $company->id),
        ];

        $currencies = Currency::where('company_id', $company->id)->orderBy('code')->get();
        $templates = InvoiceTemplateEnum::cases();
        $languages = LanguageEnum::cases();

        return view('app.settings.invoice', compact('company', 'settings', 'currencies', 'templates', 'languages'));
    }

    public function updateInvoice(Request $request, Company $company)
    {
        $validated = $request->validate([
            'default_invoice_template' => 'required|string',
            'default_invoice_language' => 'required|string',
            'default_invoice_due_days' => 'required|integer|min:0',
            'default_invoice_currency' => 'required|string',
            'invoice_numbering_reset_yearly' => 'boolean',
            'invoice_numbering_pad_zeros' => 'required|integer|min:1',
            'invoice_numbering_starting_number' => 'required|integer|min:1',
            'invoice_numbering_prefix' => 'required|string',
        ]);

        foreach ($validated as $key => $value) {
            CompanySetting::set($key, $value, $company->id);
        }

        return redirect()
            ->route('app.settings.invoice', $company)
            ->with('success', 'Invoice settings updated successfully.');
    }

    public function currencies(Company $company)
    {
        $currencies = Currency::where('company_id', $company->id)->orderBy('code')->get();

        return view('app.settings.currencies', compact('company', 'currencies'));
    }

    public function storeCurrency(Request $request, Company $company)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'prefix' => 'nullable|string|max:10',
        ]);

        $company->currencies()->create($validated);

        return redirect()
            ->route('app.settings.currencies', $company)
            ->with('success', 'Currency added successfully.');
    }

    public function deleteCurrency(Company $company, Currency $currency)
    {
        if ($currency->company_id !== $company->id) {
            abort(403);
        }

        $currency->delete();

        return redirect()
            ->route('app.settings.currencies', $company)
            ->with('success', 'Currency deleted successfully.');
    }

    public function bankAccounts(Company $company)
    {
        $bankAccounts = CompanyBankAccount::where('company_id', $company->id)->get();
        $currencies = Currency::where('company_id', $company->id)->orderBy('code')->get();

        return view('app.settings.bank-accounts', compact('company', 'bankAccounts', 'currencies'));
    }

    public function storeBankAccount(Request $request, Company $company)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'currency' => 'required|string',
            'swift' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
        ]);

        $company->bankAccounts()->create($validated);

        return redirect()
            ->route('app.settings.bank-accounts', $company)
            ->with('success', 'Bank account added successfully.');
    }

    public function updateBankAccount(Request $request, Company $company, CompanyBankAccount $bankAccount)
    {
        if ($bankAccount->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'currency' => 'required|string',
            'swift' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
        ]);

        $bankAccount->update($validated);

        return redirect()
            ->route('app.settings.bank-accounts', $company)
            ->with('success', 'Bank account updated successfully.');
    }

    public function deleteBankAccount(Company $company, CompanyBankAccount $bankAccount)
    {
        if ($bankAccount->company_id !== $company->id) {
            abort(403);
        }

        $bankAccount->delete();

        return redirect()
            ->route('app.settings.bank-accounts', $company)
            ->with('success', 'Bank account deleted successfully.');
    }

    public function emailTemplates(Company $company)
    {
        $templates = EmailTemplate::where('company_id', $company->id)->orderBy('name')->get();

        return view('app.settings.email-templates', compact('company', 'templates'));
    }

    public function emailSignatures(Company $company)
    {
        $signatures = EmailSignature::where('company_id', $company->id)->orderBy('name')->get();

        return view('app.settings.email-signatures', compact('company', 'signatures'));
    }

    public function fiscalization(Company $company)
    {
        return view('app.settings.fiscalization', compact('company'));
    }

    public function appearance(Company $company)
    {
        return view('app.settings.appearance', compact('company'));
    }

    public function email(Company $company)
    {
        return view('app.settings.email', compact('company'));
    }

    public function notifications(Company $company)
    {
        return view('app.settings.notifications', compact('company'));
    }

    public function roles(Company $company)
    {
        return view('app.settings.roles', compact('company'));
    }
}
