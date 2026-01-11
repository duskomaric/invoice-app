<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentCompany
{
    public function handle(Request $request, Closure $next): Response
    {
        $companyId = $request->route('company');

        if ($companyId) {
            $company = $companyId instanceof Company
                ? $companyId
                : Company::find($companyId);

            if (! $company || ! auth()->user()?->canAccessTenant($company)) {
                abort(403, 'You do not have access to this company.');
            }

            app()->instance('currentCompany', $company);
            view()->share('currentCompany', $company);
        }

        return $next($request);
    }
}
