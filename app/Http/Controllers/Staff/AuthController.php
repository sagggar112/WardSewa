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
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $resolvedEmail = $this->resolveEmail($credentials['email']);

        // Attempt authentication with resolved email or original email
        $attemptSuccess = Auth::guard('staff')->attempt(['email' => $resolvedEmail, 'password' => $credentials['password']], $request->boolean('remember'));

        if (!$attemptSuccess && $resolvedEmail !== $credentials['email']) {
            $attemptSuccess = Auth::guard('staff')->attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'));
        }

        if ($attemptSuccess) {
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

    private function resolveEmail(string $email): string
    {
        $email = strtolower(trim($email));

        if ($email === 'chair.kmc32@wardsewa.gov.np') {
            return 'chair@ward32.gov.np';
        }

        if (preg_match('/^chair@([a-z]+)(\d+)\.gov\.np$/i', $email, $m)) {
            if ($m[1] === 'ward' && $m[2] === '32') {
                return 'chair@ward32.gov.np';
            }
            return "chair.{$m[1]}{$m[2]}@wardsewa.gov.np";
        }

        $palikaAdmins = [
            'admin@kathmandu.gov.np' => 'admin.kmc@wardsewa.gov.np',
            'admin@chandragiri.gov.np' => 'admin.chandragiri@wardsewa.gov.np',
            'admin@budhanilkantha.gov.np' => 'admin.budhanilkantha@wardsewa.gov.np',
            'admin@tarakeshwor.gov.np' => 'admin.tarakeshwor@wardsewa.gov.np',
            'admin@tokha.gov.np' => 'admin.tokha@wardsewa.gov.np',
            'admin@kirtipur.gov.np' => 'admin.kirtipur@wardsewa.gov.np',
            'admin@nagarjun.gov.np' => 'admin.nagarjun@wardsewa.gov.np',
            'admin@dakshinkali.gov.np' => 'admin.dakshinkali@wardsewa.gov.np',
            'admin@gokarneshwor.gov.np' => 'admin.gokarneshwor@wardsewa.gov.np',
            'admin@kageshwori.gov.np' => 'admin.kageshwori@wardsewa.gov.np',
            'admin@shankharapur.gov.np' => 'admin.shankharapur@wardsewa.gov.np',
            'admin@lalitpur.gov.np' => 'admin.lmc@wardsewa.gov.np',
            'admin@bhaktapur.gov.np' => 'admin.bkm@wardsewa.gov.np',
        ];

        return $palikaAdmins[$email] ?? $email;
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
