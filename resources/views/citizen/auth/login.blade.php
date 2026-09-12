@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-2xl border border-slate-200 shadow-lg p-8">
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-xl bg-nepal-crimson text-white flex items-center justify-center font-bold text-2xl mx-auto shadow-sm">
                व
            </div>
            <h2 class="text-2xl font-bold text-slate-900 mt-4">{{ __('Citizen Login / New Registration') }}</h2>
            <p class="text-xs text-slate-500 mt-1">{{ __('Enter your 10-digit Nepali mobile number') }}</p>
        </div>

        <form method="POST" action="{{ route('citizen.login.request') }}" class="space-y-4">
            @csrf
            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    {{ __('Mobile Number (Mobile Number)') }}
                </label>
                <div class="relative rounded-lg shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-semibold text-sm">
                        +977
                    </div>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="98XXXXXXXX" maxlength="10" required autofocus
                           class="block w-full pl-16 pr-3 py-3 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-nepal-crimson focus:border-nepal-crimson font-mono text-base font-semibold">
                </div>
                @error('phone')
                    <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-nepal-crimson hover:bg-nepal-red text-white font-bold rounded-lg shadow transition text-sm">
                {{ __('Send OTP Code') }} &rarr;
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-500">
            <p>{{ __('No password required! Log in securely and instantly with a 6-digit one-time password (OTP) sent to your mobile.') }}</p>
        </div>
    </div>
</div>
@endsection
