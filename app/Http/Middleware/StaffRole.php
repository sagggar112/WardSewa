<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffRole
{
    /**
     * Handle an incoming request checking staff role permissions.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $staff = auth('staff')->user();

        if (!$staff) {
            return redirect()->route('staff.login');
        }

        // Admin always has access to all staff sections
        if ($staff->role === 'admin') {
            return $next($request);
        }

        if (!in_array($staff->role, $roles)) {
            abort(403, 'Unauthorized. This action requires ' . implode(' or ', $roles) . ' role.');
        }

        return $next($request);
    }
}
