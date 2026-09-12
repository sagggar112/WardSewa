@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-b from-nepal-darkblue via-slate-900 to-slate-900 text-white py-16 md:py-24 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto">
            <!-- Pilot Badge -->
            <div class="inline-flex items-center space-x-2 bg-nepal-red/20 border border-nepal-red/40 px-3 py-1 rounded-full text-xs font-semibold text-rose-300 mb-6">
                <span class="w-2 h-2 rounded-full bg-nepal-red animate-ping"></span>
                <span>{{ __('Pilot Program: Kathmandu Metropolitan City Ward No. 32 (Koteshwor)') }}</span>
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                {{ __('All Ward Office Services Now') }} <span class="text-nepal-gold">{{ __('From The Comfort of Home') }}</span>
            </h1>
            <p class="mt-5 text-base md:text-lg text-slate-300 leading-relaxed">
                {{ __('Apply online for recommendation letters (Four Boundaries, Unmarried, Residence), birth registration, public grievances, and pay electricity/water bills instantly.') }}
            </p>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('citizen.login') }}" class="w-full sm:w-auto px-8 py-3.5 bg-nepal-red hover:bg-nepal-crimson text-white font-bold rounded-xl shadow-lg transition text-base text-center">
                    {{ __('Apply Online Now') }} &rarr;
                </a>
                <a href="#services" class="w-full sm:w-auto px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl transition text-base text-center border border-white/20">
                    {{ __('Explore Available Services') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Features Stats Bar -->
<div class="bg-white border-b border-slate-200 shadow-sm py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div>
            <div class="text-2xl md:text-3xl font-black text-nepal-crimson">{{ __('100%') }}</div>
            <div class="text-xs text-slate-500 font-medium mt-1">{{ __('Digital Recommendations') }}</div>
        </div>
        <div>
            <div class="text-2xl md:text-3xl font-black text-nepal-blue">{{ __('24-72 Hours') }}</div>
            <div class="text-xs text-slate-500 font-medium mt-1">{{ __('Fast Service Delivery') }}</div>
        </div>
        <div>
            <div class="text-2xl md:text-3xl font-black text-emerald-600">{{ __('QR Code') }}</div>
            <div class="text-xs text-slate-500 font-medium mt-1">{{ __('Official QR Verification') }}</div>
        </div>
        <div>
            <div class="text-2xl md:text-3xl font-black text-nepal-gold">{{ __('Khalti') }}</div>
            <div class="text-xs text-slate-500 font-medium mt-1">{{ __('Secure Online Payments') }}</div>
        </div>
    </div>
</div>

<!-- Services Grid -->
<div id="services" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center max-w-2xl mx-auto mb-12">
        <h2 class="text-3xl font-extrabold text-slate-900">{{ __('Digital Services Available from Ward Office') }}</h2>
        <p class="mt-2 text-slate-600 text-sm">{{ __('Select your required service and submit your application from home with zero hassle.') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($services as $service)
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $service->category === 'recommendation' ? 'bg-blue-50 text-nepal-blue' : ($service->category === 'vital_registration' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-800') }}">
                            {{ $service->category === 'recommendation' ? (app()->getLocale() === 'en' ? 'Recommendation' : 'सिफारिस') : ($service->category === 'vital_registration' ? (app()->getLocale() === 'en' ? 'Vital Registration' : 'घटना दर्ता') : (app()->getLocale() === 'en' ? 'Utility / Grievance' : 'महसुल / गुनासो')) }}
                        </span>
                        <span class="text-xs font-bold text-slate-500">
                            {{ app()->getLocale() === 'en' ? "Within {$service->turnaround_days} days" : "{$service->turnaround_days} दिनभित्र" }}
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900 mb-1">
                        {{ app()->getLocale() === 'en' ? $service->name_en : $service->name_ne }}
                    </h3>
                    <p class="text-xs text-slate-500 font-medium mb-3">
                        {{ app()->getLocale() === 'en' ? $service->name_ne : $service->name_en }}
                    </p>

                    <div class="border-t border-slate-100 pt-3 text-xs text-slate-600 space-y-1">
                        <div><strong>{{ __('Fee:') }}</strong> {{ $service->fee > 0 ? (app()->getLocale() === 'en' ? 'Rs. ' . number_format($service->fee, 2) : 'रु. ' . number_format($service->fee, 2)) : __('Free') }}</div>
                        <div><strong>{{ __('Required Documents:') }}</strong> {{ app()->getLocale() === 'en' ? count($service->required_documents ?? []) . ' Document types' : count($service->required_documents ?? []) . ' प्रकारका प्रमाणहरू' }}</div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ route('citizen.applications.create', ['service' => $service->code]) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 hover:bg-nepal-crimson text-white text-xs font-bold rounded-lg transition">
                        {{ __('Apply Now') }} &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- QR Verification Section -->
<div id="verify" class="bg-slate-900 text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex p-3 bg-white/10 rounded-2xl mb-4">
            <svg class="w-8 h-8 text-nepal-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
            </svg>
        </div>
        <h2 class="text-3xl font-bold">{{ __('Official Digital Certificate Verification') }}</h2>
        <p class="mt-2 text-slate-300 text-sm max-w-xl mx-auto">
            {{ __('Verify any official recommendation letter issued by the ward office by scanning the QR code with your mobile or entering the verification token hash below.') }}
        </p>

        <form action="{{ url('/verify') }}" method="GET" onsubmit="event.preventDefault(); window.location.href='/verify/' + document.getElementById('tokenInput').value.trim();" class="mt-6 flex max-w-md mx-auto">
            <input type="text" id="tokenInput" placeholder="{{ __('Enter QR Verification Token (UUID)...') }}" class="flex-grow px-4 py-3 rounded-l-xl text-slate-900 text-sm focus:outline-none" required>
            <button type="submit" class="bg-nepal-red hover:bg-nepal-crimson px-6 py-3 rounded-r-xl font-bold text-sm transition">
                {{ __('Verify Now') }}
            </button>
        </form>
    </div>
</div>

<!-- Pilot Ward Contact Info -->
@if($pilotWard)
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <span class="text-xs font-bold text-nepal-blue uppercase tracking-wider">{{ __('Pilot Ward Office') }}</span>
            <h3 class="text-xl font-bold text-slate-900 mt-1">{{ app()->getLocale() === 'en' ? ($pilotWard->palika->name_en ?? $pilotWard->palika->name_ne) : $pilotWard->palika->name_ne }} - {{ app()->getLocale() === 'en' ? 'Ward No. ' . $pilotWard->ward_number : 'वडा नं. ' . $pilotWard->ward_number }}</h3>
            <p class="text-sm text-slate-600 mt-1"><strong>{{ __('Address:') }}</strong> {{ $pilotWard->office_address }} | <strong>{{ __('Phone:') }}</strong> {{ $pilotWard->office_phone }} | <strong>{{ __('Email:') }}</strong> {{ $pilotWard->office_email }}</p>
            <p class="text-xs text-slate-500 mt-2">{{ __('Office Hours: Sunday - Thursday: 10:00 - 17:00 | Friday: 10:00 - 15:00') }}</p>
        </div>
        <a href="{{ route('citizen.login') }}" class="px-6 py-3 bg-nepal-blue hover:bg-nepal-darkblue text-white text-sm font-bold rounded-xl shrink-0 transition">
            {{ __('Ward 32 Citizen Login') }}
        </a>
    </div>
</div>
@endif
@endsection
