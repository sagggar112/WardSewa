<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('staff')->check()) {
            $staff = Auth::guard('staff')->user();
            $targetRoute = match(true) {
                $staff->isSuperAdmin() => route('staff.superadmin.dashboard'),
                $staff->isDistrictAdmin() => route('staff.district.dashboard'),
                $staff->isLocalGovtAdmin() => route('staff.localgovt.dashboard'),
                default => route('staff.dashboard'),
            };
            return redirect($targetRoute);
        }

        return view('staff.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('staff')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $staff = Auth::guard('staff')->user();

            if (!$staff->is_active) {
                \App\Models\AuditLog::record('failed_login', "Inactive staff attempted login: {$staff->email}", $staff);
                Auth::guard('staff')->logout();
                return back()->with('error', 'Your account has been deactivated.');
            }

            \App\Models\AuditLog::record('login', "Staff logged in: {$staff->name} ({$staff->role})", $staff);

            $targetRoute = match(true) {
                $staff->isSuperAdmin() => route('staff.superadmin.dashboard'),
                $staff->isDistrictAdmin() => route('staff.district.dashboard'),
                $staff->isLocalGovtAdmin() => route('staff.localgovt.dashboard'),
                default => route('staff.dashboard'),
            };

            return redirect()->intended($targetRoute)
                ->with('success', "Welcome back, {$staff->name}!");
        }

        \App\Models\AuditLog::record('failed_login', "Failed login attempt for email: {$request->email}");

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $staff = Auth::guard('staff')->user();
        if ($staff) {
            \App\Models\AuditLog::record('logout', "Staff logged out: {$staff->name}", $staff);
        }

        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login')->with('success', 'Logged out successfully.');
    }
}
