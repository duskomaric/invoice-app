<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function select()
    {
        $companies = auth()->user()->companies()->get();

        if ($companies->count() === 1) {
            return redirect()->route('app.dashboard', $companies->first());
        }

        return view('app.company-select', compact('companies'));
    }

    public function switch(Company $company)
    {
        if (! auth()->user()->companies()->where('companies.id', $company->id)->exists()) {
            abort(403);
        }

        return redirect()->route('app.dashboard', $company);
    }
}
