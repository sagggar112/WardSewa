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

        $input = trim($credentials['email']);
        $resolvedEmail = $this->resolveEmail($input);

        // Attempt authentication with resolved email first
        $attemptSuccess = Auth::guard('staff')->attempt(
            ['email' => $resolvedEmail, 'password' => $credentials['password']],
            $request->boolean('remember')
        );

        // If not successful and original input was different, try with original input as email
        if (!$attemptSuccess && strtolower($resolvedEmail) !== strtolower($input)) {
            $attemptSuccess = Auth::guard('staff')->attempt(
                ['email' => $input, 'password' => $credentials['password']],
                $request->boolean('remember')
            );
        }

        // Also check if input is a phone number
        if (!$attemptSuccess) {
            $phoneStaff = \App\Models\Staff::where('phone', $input)->first();
            if ($phoneStaff) {
                $attemptSuccess = Auth::guard('staff')->attempt(
                    ['email' => $phoneStaff->email, 'password' => $credentials['password']],
                    $request->boolean('remember')
                );
            }
        }

        if ($attemptSuccess) {
            $request->session()->regenerate();
            // Clear any lingering cross-guard intended URLs
            $request->session()->forget('url.intended');

            $staff = Auth::guard('staff')->user();

            if (!$staff->is_active) {
                \App\Models\AuditLog::record('failed_login', "Inactive staff attempted login: {$staff->email}", $staff);
                Auth::guard('staff')->logout();
                return back()->with('error', 'तपाईँको खाता निष्क्रिय गरिएको छ। (Your account has been deactivated).');
            }

            \App\Models\AuditLog::record('login', "Staff logged in: {$staff->name} ({$staff->role})", $staff);

            $targetRoute = match(true) {
                $staff->isSuperAdmin() => route('staff.superadmin.dashboard'),
                $staff->isDistrictAdmin() => route('staff.district.dashboard'),
                $staff->isLocalGovtAdmin() => route('staff.localgovt.dashboard'),
                default => route('staff.dashboard'),
            };

            return redirect($targetRoute)
                ->with('success', "स्वागत छ, {$staff->name}!");
        }

        \App\Models\AuditLog::record('failed_login', "Failed login attempt for input: {$request->email}");

        return back()->withErrors([
            'email' => 'प्रविष्ट गरिएको विवरण मिलेन। कृपया आफ्नो इमेल/फोन र पासवर्ड जाँच गर्नुहोस्। (Invalid credentials).',
        ])->onlyInput('email');
    }

    private function resolveEmail(string $input): string
    {
        $input = strtolower(trim($input));

        // 1. Direct match in staff database
        $directStaff = \App\Models\Staff::whereRaw('LOWER(email) = ?', [$input])->first();
        if ($directStaff) {
            return $directStaff->email;
        }

        // 2. Direct phone match
        $phoneStaff = \App\Models\Staff::where('phone', $input)->first();
        if ($phoneStaff) {
            return $phoneStaff->email;
        }

        // 3. Short aliases
        if ($input === 'superadmin' || $input === 'superadmin@wardsewa.gov.np') {
            return 'superadmin@wardsewa.gov.np';
        }

        if ($input === 'chair.kmc32@wardsewa.gov.np' || $input === 'chair@ward32.gov.np' || $input === 'chair.ward32@wardsewa.gov.np') {
            return 'chair@ward32.gov.np';
        }

        // 4. Pattern: chair@palika{n}.gov.np (e.g. chair@bnm5.gov.np, chair@ward32.gov.np)
        if (preg_match('/^chair@([a-z]+)(\d+)\.gov\.np$/', $input, $m)) {
            if ($m[1] === 'ward' && $m[2] === '32') {
                return 'chair@ward32.gov.np';
            }
            return "chair.{$m[1]}{$m[2]}@wardsewa.gov.np";
        }

        // 5. Pattern: ward{n}@{palika}(mun)?.gov.np (e.g. ward1@chandragirimun.gov.np)
        if (preg_match('/^ward(\d+)@([a-z]+?)(?:mun)?\.gov\.np$/', $input, $m)) {
            $wNum = (int)$m[1];
            $palikaSlug = $m[2];

            $wNum = (int)$numPart;

            $palika = \App\Models\Palika::whereRaw('LOWER(code) = ?', [$palikaSlug])
                ->orWhereRaw('LOWER(name_en) LIKE ?', ["%{$palikaSlug}%"])
                ->first();

            if ($palika && $wNum > 0) {
                $ward = \App\Models\Ward::where('palika_id', $palika->id)->where('ward_number', $wNum)->first();
                if ($ward) {
                    $staff = \App\Models\Staff::where('ward_id', $ward->id)
                        ->whereIn('role', ['ward_chair', 'ward_admin', 'secretary', 'clerk'])
                        ->first();
                    if ($staff) {
                        return $staff->email;
                    }
                }
            }
        }

        // 6. Palika Admins map
        $palikaAdmins = [
            'admin@kathmandu.gov.np' => 'admin.kmc@wardsewa.gov.np',
            'admin@kmc.gov.np' => 'admin.kmc@wardsewa.gov.np',
            'admin.kmc' => 'admin.kmc@wardsewa.gov.np',
            'admin@chandragiri.gov.np' => 'admin.chandragiri@wardsewa.gov.np',
            'admin@chandragirimun.gov.np' => 'admin.chandragiri@wardsewa.gov.np',
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

        if (isset($palikaAdmins[$input])) {
            return $palikaAdmins[$input];
        }

        // 7. Generic admin@{palika} search
        if (preg_match('/^admin@([a-z]+?)(?:mun)?\.gov\.np$/', $input, $m)) {
            $palikaSlug = $m[1];
            $palika = \App\Models\Palika::whereRaw('LOWER(code) = ?', [$palikaSlug])
                ->orWhereRaw('LOWER(name_en) LIKE ?', ["%{$palikaSlug}%"])
                ->first();
            if ($palika) {
                $pAdmin = \App\Models\Staff::where('palika_id', $palika->id)
                    ->where('role', 'local_government_admin')
                    ->first();
                if ($pAdmin) {
                    return $pAdmin->email;
                }
            }
        }

        return $input;
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
