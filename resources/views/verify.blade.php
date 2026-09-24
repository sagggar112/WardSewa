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
                <p class="text-xs font-semibold text-emerald-700 mt-1">
                    {{ app()->getLocale() === 'ne' ? 'प्रमाणित आधिकारिक डिजिटल सिफारिस पत्र' : 'OFFICIALLY VERIFIED GOV.NP RECOMMENDATION LETTER' }}
                </p>
            @else
                <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h1 class="text-2xl font-black">{{ __('Recommendation Certificate Not Found') }}</h1>
                <p class="text-xs font-semibold text-rose-700 mt-1">
                    {{ app()->getLocale() === 'ne' ? 'सिफारिस अभिलेख फेला परेन' : 'RECORD NOT FOUND OR UNVERIFIED TOKEN' }}
                </p>
            @endif
        </div>

        @if($application)
        <div class="p-6 space-y-6 text-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div>
                    <span class="text-xs text-slate-500 block">{{ __('Application Number') }}:</span>
                    <strong class="font-mono text-base text-slate-900">{{ $application->application_number }}</strong>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">{{ app()->getLocale() === 'ne' ? 'सेवाको प्रकार:' : 'Service Type:' }}</span>
                    <strong class="text-slate-900">
                        {{ app()->getLocale() === 'ne' ? ($application->serviceType->name_ne ?? $application->serviceType->name_en) : ($application->serviceType->name_en ?? $application->serviceType->name_ne) }}
                    </strong>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">{{ __('Full Name') }}:</span>
                    <strong class="text-slate-900">{{ $application->citizen->full_name }}</strong>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">{{ app()->getLocale() === 'ne' ? 'नागरिकता नं.:' : 'Citizenship No.:' }}</span>
                    <strong class="font-mono text-slate-900">{{ $application->citizen->citizenship_no ?? (app()->getLocale() === 'ne' ? 'उपलब्ध छैन' : 'Not available') }}</strong>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">{{ app()->getLocale() === 'ne' ? 'जारी गर्ने वडा कार्यालय:' : 'Issuing Ward Office:' }}</span>
                    <strong class="text-slate-900">
                        {{ (app()->getLocale() === 'ne' ? ($application->ward->palika->name_ne ?? $application->ward->palika->name_en) : ($application->ward->palika->name_en ?? $application->ward->palika->name_ne)) . ' - ' . __('Ward No.') . ' ' . $application->ward->ward_number }}
                    </strong>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">{{ app()->getLocale() === 'ne' ? 'स्वीकृति मिति:' : 'Approved Date:' }}</span>
                    <strong class="text-slate-900">{{ $application->approved_at ? $application->approved_at->format('Y-m-d H:i') : 'N/A' }}</strong>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">{{ app()->getLocale() === 'ne' ? 'स्वीकृत गर्ने अधिकारी:' : 'Authorized Official:' }}</span>
                    <strong class="text-slate-900">
                        {{ $application->approvedBy->name ?? (app()->getLocale() === 'ne' ? 'वडा अधिकृत' : 'Authorized Officer') }}
                        @if($application->approvedBy)
                            - {{ $application->approvedBy->role === 'ward_chair' ? __('Ward Chairperson') : ($application->approvedBy->role === 'secretary' ? __('Ward Secretary') : $application->approvedBy->role) }}
                        @endif
                    </strong>
                </div>
                <div>
                    <span class="text-xs text-slate-500 block">{{ app()->getLocale() === 'ne' ? 'डिजिटल प्रमाणीकरण टोकन:' : 'Digital Verification Token:' }}</span>
                    <span class="font-mono text-xs text-slate-600 truncate block">{{ $application->qr_code_token }}</span>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 text-blue-900 p-4 rounded-xl text-xs">
                <p>
                    <strong>{{ app()->getLocale() === 'ne' ? 'सूचना:' : 'Notice:' }}</strong> 
                    {{ app()->getLocale() === 'ne' ? 'यो प्रमाण डिजिटल रूपमा नेपाल स्थानीय सरकार सञ्चालन ऐन बमोजिम वडा कार्यालयको डिजिटल अभिलेख प्रणालीबाट सिधै प्रमाणीकरण गरिएको हो।' : 'This certificate is verified directly from the official digital records system of the Local Government of Nepal.' }}
                </p>
            </div>
        </div>
        @else
        <div class="p-8 text-center text-slate-600 text-sm">
            <p>{{ app()->getLocale() === 'ne' ? 'प्रविष्ट गरिएको प्रमाणीकरण टोकन सँग मेल खाने कुनै पनि आधिकारिक सिफारिस अभिलेख भेटिएन।' : 'No verified official recommendation record matches the token provided.' }}</p>
            <p class="text-xs text-slate-500 mt-2">{{ app()->getLocale() === 'ne' ? 'कृपया कागजातमा रहेको QR कोड पुनः स्क्यान गर्नुहोस् वा सम्बन्धित वडा कार्यालयमा सम्पर्क गर्नुहोस्।' : 'Please re-scan the QR code from the document or contact the respective ward office.' }}</p>
            <div class="mt-6">
                <a href="{{ route('home') }}" class="px-5 py-2.5 bg-slate-900 text-white rounded-lg text-xs font-semibold">
                    {{ app()->getLocale() === 'ne' ? 'गृहपृष्ठ फर्कनुहोस्' : 'Return to Home' }}
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
