@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-nepal-darkblue via-nepal-blue to-nepal-darkblue text-white p-6 text-center">
            <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center mx-auto shadow-md">
                <svg class="w-7 h-7 text-nepal-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-white mt-3">{{ __('Forgot Password?') }}</h2>
            <p class="text-xs text-slate-200 mt-1">{{ app()->getLocale() === 'ne' ? 'आफ्नो दर्ता विवरण प्रमाणित गरी नयाँ पासवर्ड सेट गर्नुहोस्।' : 'Verify your registered credentials and set a new password.' }}</p>
        </div>

        <div class="p-6 sm:p-8">
            @if(session('error'))
                <div class="mb-4 p-3 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-800">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('citizen.password.update') }}" class="space-y-4">
                @csrf

                <!-- Mobile / Email -->
                <div>
                    <label for="login" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        {{ app()->getLocale() === 'ne' ? '१. दर्ता भएको मोबाइल वा इमेल' : '1. Registered Phone or Email' }} *
                    </label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" name="login" id="login" value="{{ old('login') }}" 
                               placeholder="{{ app()->getLocale() === 'ne' ? '९८XXXXXXXX वा इमेल' : '98XXXXXXXX or email' }}" required autofocus
                               class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-nepal-blue focus:border-nepal-blue text-sm transition">
                    </div>
                    @error('login')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Verification field (Full Name or Citizenship No) -->
                <div>
                    <label for="verification_field" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        {{ app()->getLocale() === 'ne' ? '२. पूरा नाम वा नागरिकता नं.' : '2. Full Name or Citizenship No.' }} *
                    </label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <input type="text" name="verification_field" id="verification_field" value="{{ old('verification_field') }}" 
                               placeholder="{{ app()->getLocale() === 'ne' ? 'दर्ता गर्दाको पूरा नाम वा नागरिकता नं.' : 'Registered full name or citizenship no.' }}" required
                               class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-nepal-blue focus:border-nepal-blue text-sm transition">
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">
                        {{ app()->getLocale() === 'ne' ? 'खाता सुरक्षाका लागि दर्ता गर्दाको पूरा नाम वा नागरिकता नम्बर प्रविष्ट गर्नुहोस्।' : 'Enter your registered full name or citizenship number for identity verification.' }}
                    </p>
                    @error('verification_field')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        {{ app()->getLocale() === 'ne' ? '३. नयाँ पासवर्ड' : '3. New Password' }} *
                    </label>
                    <input type="password" name="password" id="password" required minlength="6" 
                           placeholder="{{ app()->getLocale() === 'ne' ? 'कम्तिमा ६ अक्षरको नयाँ पासवर्ड' : 'New password at least 6 characters' }}"
                           class="block w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-nepal-blue focus:border-nepal-blue text-sm transition">
                    @error('password')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        {{ app()->getLocale() === 'ne' ? '४. नयाँ पासवर्ड पुष्टि गर्नुहोस्' : '4. Confirm New Password' }} *
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required minlength="6" 
                           placeholder="{{ app()->getLocale() === 'ne' ? 'नयाँ पासवर्ड पुनः टाइप गर्नुहोस्' : 'Re-type new password' }}"
                           class="block w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-nepal-blue focus:border-nepal-blue text-sm transition">
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-4 bg-nepal-crimson hover:bg-nepal-red text-white font-bold rounded-xl shadow-md hover:shadow-lg transition text-sm flex items-center justify-center gap-2">
                        <span>{{ app()->getLocale() === 'ne' ? 'पासवर्ड सुरक्षित गर्नुहोस्' : 'Reset Password' }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </div>
            </form>

            <!-- Back to Login Link -->
            <div class="mt-6 pt-5 border-t border-slate-200 text-center">
                <a href="{{ route('citizen.login') }}" class="text-xs text-nepal-blue font-bold hover:underline flex items-center justify-center gap-1">
                    &larr; {{ app()->getLocale() === 'ne' ? 'लगइन पृष्ठमा फर्कनुहोस्' : 'Back to Citizen Login' }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
