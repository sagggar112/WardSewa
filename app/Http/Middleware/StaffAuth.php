<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffAuth
{
    /**
     * Handle an incoming request for staff authentication.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('staff')->check()) {
            return redirect()->route('staff.login')->with('error', 'Please login to access the Staff portal.');
        }

        $staff = auth('staff')->user();
        if (!$staff->is_active) {
            auth('staff')->logout();
            return redirect()->route('staff.login')->with('error', 'Your staff account has been deactivated. Please contact your administrator.');
        }

        return $next($request);
    }
}
