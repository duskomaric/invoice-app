<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserLastSeenAtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = $request->user();
            $now = now();

            // Update only if 5 minutes have passed since last update
            if ($user->last_seen_at === null || $user->last_seen_at->lt($now->subMinutes(5))) {
                $user->forceFill(['last_seen_at' => $now])->save();
            }
        }

        return $next($request);
    }
}
