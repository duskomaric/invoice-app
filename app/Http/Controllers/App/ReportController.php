<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\IncomeBookEntry;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Company $company)
    {
        return view('app.reports.index', compact('company'));
    }

    public function incomeBook(Request $request, Company $company)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month');

        $query = IncomeBookEntry::where('company_id', $company->id)
            ->whereYear('date', $year);

        if ($month) {
            $query->whereMonth('date', $month);
        }

        $entries = $query->orderBy('date')->paginate(50);

        $totals = [
            'cash' => IncomeBookEntry::where('company_id', $company->id)
                ->whereYear('date', $year)
                ->when($month, fn ($q) => $q->whereMonth('date', $month))
                ->where('payment_method', 'cash')
                ->sum('amount'),
            'bank' => IncomeBookEntry::where('company_id', $company->id)
                ->whereYear('date', $year)
                ->when($month, fn ($q) => $q->whereMonth('date', $month))
                ->where('payment_method', 'bank')
                ->sum('amount'),
            'total' => IncomeBookEntry::where('company_id', $company->id)
                ->whereYear('date', $year)
                ->when($month, fn ($q) => $q->whereMonth('date', $month))
                ->sum('amount'),
        ];

        return view('app.reports.income-book', compact('company', 'entries', 'totals', 'year', 'month'));
    }
}
