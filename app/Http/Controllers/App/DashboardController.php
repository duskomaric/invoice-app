<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\Proforma;
use App\Models\Contract;

class DashboardController extends Controller
{
    public function index(Company $company)
    {
        $stats = [
            'invoices_count' => Invoice::where('company_id', $company->id)->count(),
            'quotes_count' => Quote::where('company_id', $company->id)->count(),
            'proformas_count' => Proforma::where('company_id', $company->id)->count(),
            'contracts_count' => Contract::where('company_id', $company->id)->count(),
            'recent_invoices' => Invoice::where('company_id', $company->id)
                ->with('client')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('app.dashboard', compact('company', 'stats'));
    }
}
