<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckForDashboardMaintenanceMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Setting::get('dashboard_under_maintenance')) {
            if (auth()->user()->role !== RoleEnum::SuperAdmin) {
                return response()->view('dashboard-maintenance');
            }
        }

        return $next($request);
    }
}
