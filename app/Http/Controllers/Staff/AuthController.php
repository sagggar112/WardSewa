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
            return redirect()->route('staff.dashboard');
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
                Auth::guard('staff')->logout();
                return back()->with('error', 'Your account has been deactivated.');
            }

            return redirect()->intended(route('staff.dashboard'))
                ->with('success', "Welcome back, {$staff->name}!");
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login')->with('success', 'Logged out successfully.');
    }
}
