@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md overflow-hidden">
        <!-- Verification Header -->
        <div class="p-6 text-center border-b border-slate-200 {{ $application ? 'bg-emerald-50 text-emerald-900' : 'bg-rose-50 text-rose-900' }}">
            @if($application)
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-2xl font-black">{{ __('Official Recommendation Certificate Verification Successful') }}</h1>
                <p class="text-xs font-semibold text-emerald-700 mt-1">OFFICIALLY VERIFIED GOV.NP RECOMMENDATION LETTER</p>
            @else
                <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h1 class="text-2xl font-black">{{ __('Recommendation Certificate Not Found') }}</h1>
                <p class="text-xs font-semibold text-rose-700 mt-1">RECORD NOT FOUND OR UNVERIFIED TOKEN</p>
            @endif
        </div>

        @if($application)
        <div class="p-6 space-y-6 text-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div>
                    <span class="text-xs text-slate-500 block">निवेदन नम्बर (Application No):</span>
                    <strong class="font-mono text-base text-slate-900">{{ $application->application_number }}</strong>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">सेवाको प्रकार (Service Type):</span>
                    <strong class="text-slate-900">{{ $application->serviceType->name_ne }} ({{ $application->serviceType->name_en }})</strong>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">नागरिकको नाम (Citizen Name):</span>
                    <strong class="text-slate-900">{{ $application->citizen->full_name }}</strong>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">नागरिकता नं. (Citizenship No):</span>
                    <strong class="font-mono text-slate-900">{{ $application->citizen->citizenship_no ?? 'उपलब्ध छैन' }}</strong>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">जारी गर्ने वडा कार्यालय (Issuing Ward):</span>
                    <strong class="text-slate-900">{{ $application->ward->palika->name_ne }} - वडा नं. {{ $application->ward->ward_number }}</strong>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">स्वीकृति मिति (Approved Date):</span>
                    <strong class="text-slate-900">{{ $application->approved_at ? $application->approved_at->format('Y-m-d H:i') : 'N/A' }}</strong>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">स्वीकृत गर्ने अधिकारी (Authorized By):</span>
                    <strong class="text-slate-900">{{ $application->approvedBy->name ?? 'वडा अध्यक्ष / सचिव' }} ({{ $application->approvedBy ? ucfirst(str_replace('_', ' ', $application->approvedBy->role)) : 'Officer' }})</strong>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">डिजिटल टोकन (Token Hash):</span>
                    <span class="font-mono text-xs text-slate-600 truncate block">{{ $application->qr_code_token }}</span>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 text-blue-900 p-4 rounded-xl text-xs">
                <p><strong>सूचना:</strong> यो प्रमाण डिजिटल रूपमा नेपाल स्थानीय सरकार सञ्चालन ऐन बमोजिम वडा कार्यालयको डिजिटल अभिलेख प्रणालीबाट सिधै प्रमाणीकरण गरिएको हो।</p>
            </div>
        </div>
        @else
        <div class="p-8 text-center text-slate-600 text-sm">
            <p>प्रविष्ट गरिएको प्रमाणीकरण टोकन (<strong>{{ $token }}</strong>) सँग मेल खाने कुनै पनि आधिकारिक सिफारिस अभिलेख भेटिएन।</p>
            <p class="text-xs text-slate-500 mt-2">कृपया कागजातमा रहेको QR कोड पुनः स्क्यान गर्नुहोस् वा सम्बन्धित वडा कार्यालयमा सम्पर्क गर्नुहोस्।</p>
            <div class="mt-6">
                <a href="{{ route('home') }}" class="px-5 py-2.5 bg-slate-900 text-white rounded-lg text-xs font-semibold">
                    गृहपृष्ठ फर्कनुहोस्
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
