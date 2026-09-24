@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-nepal-darkblue via-nepal-blue to-nepal-darkblue text-white p-6 text-center">
            <div class="flex justify-center">
                <x-logo size="lg" :showText="false" />
            </div>
            <h2 class="text-2xl font-extrabold text-white mt-3">{{ __('नागरिक पोर्टल लगइन (Citizen Login)') }}</h2>
            <p class="text-xs text-slate-200 mt-1">{{ __('Enter your mobile number or email and password') }}</p>
        </div>

        <div class="p-6 sm:p-8">
            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 bg-rose-50 border border-rose-200 rounded-lg text-xs text-rose-800">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('citizen.login.submit') }}" class="space-y-4">
                @csrf

                <!-- Mobile / Email -->
                <div>
                    <label for="login" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        {{ __('मोबाइल नम्बर वा इमेल (Mobile or Email)') }} *
                    </label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" name="login" id="login" value="{{ old('login') }}" placeholder="98XXXXXXXX वा इमेल" required autofocus
                               class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-nepal-blue focus:border-nepal-blue text-sm font-medium transition">
                    </div>
                    @error('login')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        {{ __('पासवर्ड (Password)') }} *
                    </label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input type="password" name="password" id="password" value="" required placeholder="••••••••"
                               class="block w-full pl-10 pr-10 py-3 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-nepal-blue focus:border-nepal-blue text-sm transition">
                        <button type="button" onclick="togglePasswordVisibility('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-nepal-blue focus:ring-nepal-blue">
                        <span class="text-slate-600">{{ __('मलाई सम्झनुहोस् (Remember Me)') }}</span>
                    </label>
                    <a href="{{ route('citizen.password.request') }}" class="text-nepal-blue hover:text-nepal-crimson font-bold hover:underline transition">
                        {{ __('पासवर्ड बिर्सनुभयो? (Forgot Password?)') }}
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 px-4 bg-nepal-blue hover:bg-nepal-darkblue text-white font-bold rounded-xl shadow-md hover:shadow-lg transition text-sm flex items-center justify-center gap-2">
                    <span>{{ __('लगइन गर्नुहोस् (Log In)') }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            <!-- Register New Account Link -->
            <div class="mt-6 pt-5 border-t border-slate-200 text-center">
                <p class="text-xs text-slate-600 mb-2">{{ __('नयाँ नागरिक हुनुहुन्छ? (Don\'t have an account?)') }}</p>
                <a href="{{ route('citizen.register') }}" class="w-full inline-flex items-center justify-center py-2.5 px-4 bg-nepal-crimson hover:bg-nepal-red text-white text-xs font-bold rounded-lg shadow transition">
                    + {{ __('नयाँ नागरिक खाता दर्ता गर्नुहोस् (Register New Account)') }}
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(fieldId) {
    const input = document.getElementById(fieldId);
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}
</script>
@endsection
