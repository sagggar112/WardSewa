@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-2xl border border-slate-200 shadow-lg p-8">
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-xl bg-nepal-blue text-white flex items-center justify-center font-bold text-2xl mx-auto shadow-sm">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 mt-4">{{ __('OTP Code Verification') }}</h2>
            <p class="text-xs text-slate-500 mt-1">
                {{ __('Enter the 6-digit code sent to') }} <strong>{{ $phone }}</strong>
            </p>
        </div>

        @if (session('dev_otp'))
            <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-800 flex items-center justify-between">
                <span><strong>{{ __('Testing Code (Sandbox):') }}</strong> <code class="font-mono font-bold text-sm text-nepal-blue">{{ session('dev_otp') }}</code></span>
                <button type="button" onclick="document.getElementById('otp').value='{{ session('dev_otp') }}'" class="text-[11px] underline font-medium hover:text-amber-950">
                    {{ __('Auto-fill') }}
                </button>
            </div>
        @endif

        @if (session('sms_warning'))
            <div class="mb-4 p-3 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-700">
                {{ session('sms_warning') }}
            </div>
        @endif

        <form method="POST" action="{{ route('citizen.otp.verify') }}" class="space-y-4">
            @csrf
            <div>
                <label for="otp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider text-center mb-2">
                    {{ __('6-Digit Verification Code (OTP Code)') }}
                </label>
                <input type="text" name="otp" id="otp" maxlength="6" pattern="[0-9]{6}" required autofocus placeholder="------"
                       class="block w-full py-3 text-center border border-slate-300 rounded-lg text-slate-900 tracking-widest font-mono text-2xl font-bold focus:ring-2 focus:ring-nepal-blue focus:border-nepal-blue">
                @error('otp')
                    <p class="text-rose-600 text-xs mt-1 text-center">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-nepal-blue hover:bg-nepal-darkblue text-white font-bold rounded-lg shadow transition text-sm">
                {{ __('Verify & Log In') }} &rarr;
            </button>
        </form>

        <div class="mt-6 flex items-center justify-between text-xs text-slate-500 pt-4 border-t border-slate-100">
            <a href="{{ route('citizen.login') }}" class="text-slate-600 hover:underline">&larr; {{ __('Change Mobile Number') }}</a>
            <form method="POST" action="{{ route('citizen.login.request') }}" class="inline">
                @csrf
                <input type="hidden" name="phone" value="{{ $phone }}">
                <button type="submit" class="text-nepal-crimson hover:underline font-semibold">{{ __('Resend Code') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
