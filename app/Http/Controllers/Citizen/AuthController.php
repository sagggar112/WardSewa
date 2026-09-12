<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Citizen;
use App\Models\District;
use App\Models\Palika;
use App\Models\Ward;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Show citizen password login form.
     */
    public function showLogin()
    {
        if (Auth::guard('citizen')->check()) {
            return redirect()->route('citizen.dashboard');
        }

        return view('citizen.auth.login');
    }

    /**
     * Handle citizen login with mobile number / email and password.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'कृपया आफ्नो मोबाइल नम्बर वा इमेल प्रविष्ट गर्नुहोस् (Please enter your mobile number or email).',
            'password.required' => 'कृपया पासवर्ड प्रविष्ट गर्नुहोस् (Please enter your password).',
        ]);

        $loginInput = trim($request->input('login'));
        $password = $request->input('password');

        // Normalize phone if login is a mobile number
        $cleanPhone = $this->notificationService->normalizePhone($loginInput);

        // Find citizen by phone (normalized or exact) or by email
        $citizen = Citizen::where('phone', $cleanPhone)
            ->orWhere('phone', $loginInput)
            ->orWhere('email', $loginInput)
            ->first();

        if (!$citizen || !Hash::check($password, $citizen->password)) {
            return back()->withInput($request->only('login', 'remember'))
                ->withErrors([
                    'login' => 'मोबाइल नम्बर वा पासवर्ड मिलेन (The provided credentials do not match our records).',
                ]);
        }

        Auth::guard('citizen')->login($citizen, $request->boolean('remember'));
        $request->session()->regenerate();

        // If ward is not yet selected, guide to ward selection
        if (!$citizen->ward_id) {
            return redirect()->route('citizen.ward-select')
                ->with('info', 'स्वागत छ! कृपया आफ्नो स्थायी वा बसोबास वडा चयन गर्नुहोस्।');
        }

        return redirect()->intended(route('citizen.dashboard'))
            ->with('success', "स्वागत छ, {$citizen->full_name}!");
    }

    /**
     * Show citizen registration form.
     */
    public function showRegister()
    {
        if (Auth::guard('citizen')->check()) {
            return redirect()->route('citizen.dashboard');
        }

        $districts = District::whereIn('code', ['KTM', 'LAL', 'BKT'])
            ->with([
                'palikas' => function ($q) {
                    $q->orderBy('type')->orderBy('name_en');
                },
                'palikas.wards' => function ($q) {
                    $q->orderBy('ward_number');
                }
            ])
            ->get();

        return view('citizen.auth.register', compact('districts'));
    }

    /**
     * Handle citizen new account registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'regex:/^(98|97|96)[0-9]{8}$/'],
            'email' => ['nullable', 'email', 'max:150', 'unique:citizens,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'citizenship_no' => ['nullable', 'string', 'max:50'],
            'ward_id' => ['required', 'exists:wards,id'],
            'address' => ['required', 'string', 'max:255'],
        ], [
            'full_name.required' => 'कृपया आफ्नो पूरा नाम प्रविष्ट गर्नुहोस्।',
            'phone.required' => 'कृपया आफ्नो मोबाइल नम्बर प्रविष्ट गर्नुहोस्।',
            'phone.regex' => 'कृपया १० अङ्कको मान्य नेपाली मोबाइल नम्बर प्रविष्ट गर्नुहोस् (९८, ९७ वा ९६ बाट सुरु भएको)।',
            'email.email' => 'कृपया मान्य इमेल ठेगाना प्रविष्ट गर्नुहोस्।',
            'email.unique' => 'यो इमेल पहिले नै दर्ता भइसकेको छ।',
            'password.required' => 'कृपया पासवर्ड प्रविष्ट गर्नुहोस्।',
            'password.min' => 'पासवर्ड कम्तिमा ६ अक्षरको हुनुपर्छ।',
            'password.confirmed' => 'पासवर्ड र पुष्टि पासवर्ड मिलेनन्।',
            'ward_id.required' => 'कृपया आफ्नो वडा चयन गर्नुहोस्।',
            'address.required' => 'कृपया आफ्नो टोल / सडक ठेगाना प्रविष्ट गर्नुहोस्।',
        ]);

        $cleanPhone = $this->notificationService->normalizePhone($request->phone);

        // Check unique phone with normalized format
        $existingCitizen = Citizen::where('phone', $cleanPhone)->first();
        if ($existingCitizen) {
            return back()->withInput($request->except('password', 'password_confirmation'))
                ->withErrors([
                    'phone' => 'यो मोबाइल नम्बर पहिले नै दर्ता भइसकेको छ। कृपया सिधै लगइन गर्नुहोस्।',
                ]);
        }

        $citizen = Citizen::create([
            'full_name' => $request->full_name,
            'phone' => $cleanPhone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'citizenship_no' => $request->citizenship_no,
            'ward_id' => $request->ward_id,
            'address' => $request->address,
            'is_verified' => true,
        ]);

        Auth::guard('citizen')->login($citizen, true);
        $request->session()->regenerate();

        return redirect()->route('citizen.dashboard')
            ->with('success', 'तपाईँको नागरिक खाता सफलतापूर्वक सिर्जना भयो! स्वागत छ। (Account registered successfully!)');
    }

    /**
     * Show SMS OTP Login option.
     */
    public function showOtpLogin()
    {
        if (Auth::guard('citizen')->check()) {
            return redirect()->route('citizen.dashboard');
        }

        return view('citizen.auth.otp-login');
    }

    /**
     * Request OTP code via SMS.
     */
    public function requestOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^(98|97|96)[0-9]{8}$/'],
        ], [
            'phone.regex' => 'Please enter a valid 10-digit Nepali mobile number (starts with 98, 97, or 96).',
        ]);

        $phone = $this->notificationService->normalizePhone($request->input('phone'));
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
            ->with('success', 'OTP code has been sent to ' . $phone);
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
            return redirect()->route('citizen.login')->with('error', 'Session expired. Please log in.');
        }

        $citizen = Citizen::where('phone', $phone)->first();

        if (!$citizen || $citizen->otp_code !== $request->otp) {
            return back()->with('error', 'Invalid verification code. Please try again.');
        }

        if (now()->isAfter($citizen->otp_expires_at)) {
            return back()->with('error', 'Verification code has expired. Please request a new one.');
        }

        $citizen->otp_code = null;
        $citizen->is_verified = true;
        $citizen->save();

        Auth::guard('citizen')->login($citizen, true);
        session()->forget('auth_phone');

        if (!$citizen->ward_id) {
            return redirect()->route('citizen.ward-select')
                ->with('info', 'Welcome to WardSewa! Please select your permanent or resident ward.');
        }

        return redirect()->route('citizen.dashboard')
            ->with('success', 'Logged in successfully! Welcome back.');
    }

    public function showWardSelect()
    {
        /** @var \App\Models\Citizen $citizen */
        $citizen = Auth::guard('citizen')->user();
        if ($citizen) {
            $citizen->load('ward.palika.district');
        }

        $districts = District::whereIn('code', ['KTM', 'LAL', 'BKT'])
            ->with([
                'palikas' => function ($q) {
                    $q->orderBy('type')->orderBy('name_en');
                },
                'palikas.wards' => function ($q) {
                    $q->orderBy('ward_number');
                }
            ])
            ->get();

        $palikas = Palika::with(['wards', 'district'])->get();

        return view('citizen.auth.ward-select', compact('citizen', 'districts', 'palikas'));
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
