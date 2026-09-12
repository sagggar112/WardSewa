<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Citizen;
use App\Models\Palika;
use App\Models\Ward;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function showLogin()
    {
        if (Auth::guard('citizen')->check()) {
            return redirect()->route('citizen.dashboard');
        }

        return view('citizen.auth.login');
    }

    public function requestOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^(98|97)[0-9]{8}$/'],
        ], [
            'phone.regex' => 'Please enter a valid 10-digit Nepali mobile number (starts with 98 or 97).',
        ]);

        $phone = $request->input('phone');
        $otp = (string) rand(100000, 999999);

        $citizen = Citizen::firstOrCreate(
            ['phone' => $phone],
            ['full_name' => 'Citizen ' . substr($phone, -4)]
        );

        $citizen->otp_code = $otp;
        $citizen->otp_expires_at = now()->addMinutes(10);
        $citizen->save();

        $this->notificationService->sendOtp($phone, $otp);

        session(['auth_phone' => $phone]);

        return redirect()->route('citizen.otp.show')
            ->with('success', 'OTP has been sent to your mobile number: ' . $phone);
    }

    public function showVerifyOtp()
    {
        $phone = session('auth_phone');
        if (!$phone) {
            return redirect()->route('citizen.login');
        }

        return view('citizen.auth.otp', compact('phone'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $phone = session('auth_phone');
        if (!$phone) {
            return redirect()->route('citizen.login')->with('error', 'Session expired. Please request OTP again.');
        }

        $citizen = Citizen::where('phone', $phone)->first();

        if (!$citizen || $citizen->otp_code !== $request->otp) {
            return back()->with('error', 'Invalid verification code. Please try again.');
        }

        if (now()->isAfter($citizen->otp_expires_at)) {
            return back()->with('error', 'Verification code has expired. Please request a new one.');
        }

        // Mark OTP as used and verify citizen
        $citizen->otp_code = null;
        $citizen->is_verified = true;
        $citizen->save();

        Auth::guard('citizen')->login($citizen, true);
        session()->forget('auth_phone');

        // If ward is not yet selected, guide to ward selection
        if (!$citizen->ward_id) {
            return redirect()->route('citizen.ward-select')
                ->with('info', 'Welcome to WardSewa! Please select your permanent or resident ward.');
        }

        return redirect()->route('citizen.dashboard')
            ->with('success', 'Logged in successfully! Welcome back.');
    }

    public function showWardSelect()
    {
        $citizen = Auth::guard('citizen')->user();
        $palikas = Palika::with('wards')->get();

        return view('citizen.auth.ward-select', compact('citizen', 'palikas'));
    }

    public function saveWardSelect(Request $request)
    {
        $request->validate([
            'ward_id' => ['required', 'exists:wards,id'],
            'full_name' => ['required', 'string', 'max:150'],
            'citizenship_no' => ['nullable', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $citizen = Auth::guard('citizen')->user();
        $citizen->update([
            'ward_id' => $request->ward_id,
            'full_name' => $request->full_name,
            'citizenship_no' => $request->citizenship_no,
            'address' => $request->address,
        ]);

        return redirect()->route('citizen.dashboard')
            ->with('success', 'Your ward profile has been updated successfully!');
    }

    public function logout(Request $request)
    {
        Auth::guard('citizen')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
