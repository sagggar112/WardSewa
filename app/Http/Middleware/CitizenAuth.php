<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CitizenAuth
{
    /**
     * Handle an incoming request for citizen authentication.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('citizen')->check()) {
            return redirect()->route('citizen.login')->with('error', 'Please login with your mobile number to access this page.');
        }

        $citizen = auth('citizen')->user();

        // If citizen has not yet selected their ward, redirect them to ward selection
        if (!$citizen->ward_id && !$request->routeIs('citizen.ward-select*') && !$request->routeIs('citizen.logout')) {
            return redirect()->route('citizen.ward-select')->with('info', 'Please select your permanent or current Ward to continue.');
        }

        return $next($request);
    }
}
