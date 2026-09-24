@extends('layouts.citizen')

@section('title', __('Application Progress') . ' - ' . $application->application_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Banner -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="text-xs text-slate-500 font-medium">{{ __('Application No') }}:</span>
                <span class="font-mono font-black text-slate-900 text-lg">{{ $application->application_number }}</span>
            </div>
            <h1 class="text-xl font-bold text-slate-900 mt-1">
                {{ app()->getLocale() === 'ne' ? ($application->serviceType->name_ne ?? $application->serviceType->name_en) : ($application->serviceType->name_en ?? $application->serviceType->name_ne) }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                @if(app()->getLocale() === 'ne')
                    {{ $application->ward->palika->name_ne ?? '' }} - वडा नं. {{ $application->ward->ward_number }} कार्यालय
                @else
                    {{ $application->ward->palika->name_en ?? '' }} - Ward No. {{ $application->ward->ward_number }} Office
                @endif
            </p>
        </div>

        <div>
            @if($application->status === 'approved')
                <div class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Approved') }}
                </div>
            @elseif($application->status === 'under_review')
                <div class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping mr-2"></span>
                    {{ __('Under Review') }}
                </div>
            @elseif($application->status === 'documents_requested')
                <div class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300">
                    {{ __('Documents Requested') }}
                </div>
            @elseif($application->status === 'rejected')
                <div class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-red-100 text-red-800 border border-red-300">
                    {{ __('Rejected') }}
                </div>
            @else
                <div class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-blue-100 text-blue-800 border border-blue-300">
                    {{ __('Submitted') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Approved Action Card: Download Certificate -->
    @if($application->isApproved())
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-2xl p-6 text-white shadow-md flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <span class="text-xs text-emerald-200 font-bold uppercase tracking-wider">
                {{ app()->getLocale() === 'ne' ? 'प्रमाणपत्र तयार छ' : 'Certificate Ready' }}
            </span>
            <h2 class="text-xl font-black mt-0.5">
                {{ app()->getLocale() === 'ne' ? 'आधिकारिक सिफारिस पत्र डाउनलोड गर्नुहोस्' : 'Download Official Recommendation Certificate' }}
            </h2>
            <p class="text-xs text-emerald-100 mt-1">
                {{ app()->getLocale() === 'ne' ? 'डिजिटल हस्ताक्षर, वडा छाप तथा क्युआर प्रमाणीकरण सहित तयार पारिएको छ।' : 'Prepared with authorized digital signature, ward seal, and QR verification.' }}
            </p>
        </div>
        <div class="flex items-center space-x-3 shrink-0">
            <a href="{{ route('citizen.applications.certificate', $application->id) }}" target="_blank"
               class="px-5 py-3 bg-white text-emerald-900 hover:bg-emerald-50 text-xs font-black rounded-xl shadow-md transition flex items-center space-x-2">
                <svg class="w-4 h-4 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>{{ __('Download Certificate') }}</span>
            </a>
            @if($application->qr_code_token)
                <a href="{{ route('verify.certificate', $application->qr_code_token) }}" target="_blank"
                   class="px-3.5 py-3 bg-emerald-800/80 hover:bg-emerald-800 text-white text-xs font-semibold rounded-xl border border-emerald-500/40 transition">
                    {{ app()->getLocale() === 'ne' ? 'क्युआर प्रमाणीकरण हेर्नुहोस्' : 'View QR Verification' }}
                </a>
            @endif
        </div>
    </div>
    @endif

    <!-- Payment Notice / Khalti Action -->
    @if($application->payment_amount > 0 && $application->payment_status !== 'paid')
    <div class="bg-amber-50 border border-amber-300 rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="text-xs text-amber-800 font-bold uppercase">
                {{ app()->getLocale() === 'ne' ? 'सिफारिस दस्तुर भुक्तानी बाँकी' : 'Recommendation Fee Due' }}
            </div>
            <h3 class="text-lg font-black text-slate-900 mt-0.5">
                {{ __('Fee:') }} {{ app()->getLocale() === 'ne' ? 'रु. ' . number_format($application->payment_amount, 2) : 'NPR ' . number_format($application->payment_amount, 2) }}
            </h3>
            <p class="text-xs text-slate-600 mt-1">
                {{ app()->getLocale() === 'ne' ? 'दस्तुर भुक्तानी भएपछि मात्र वडा कार्यालयबाट सिफारिस पत्र जारी हुनेछ।' : 'The official recommendation certificate will be issued once the fee is paid.' }}
            </p>
        </div>
        <div class="flex items-center space-x-2 shrink-0">
            <!-- Khalti Pay -->
            <form method="POST" action="{{ route('citizen.applications.pay', $application->id) }}">
                @csrf
                <button type="submit" class="px-5 py-2.5 bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold rounded-xl shadow transition flex items-center space-x-1.5">
                    <span>{{ __('Pay via Khalti') }}</span>
                </button>
            </form>
            <!-- Instant Mock Pay for sandbox -->
            <a href="{{ route('citizen.applications.mock-pay', $application->id) }}" class="px-3.5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl transition">
                {{ app()->getLocale() === 'ne' ? 'तत्काल परीक्षण भुक्तानी' : 'Instant Mock Payment' }}
            </a>
        </div>
    </div>
    @endif

    <!-- Workflow Progress Timeline -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-base font-bold text-slate-900 mb-6">{{ __('Application Progress') }}</h2>

        <div class="relative pl-6 border-l-2 border-slate-200 space-y-6">
            @forelse($application->statusLogs as $log)
                <div class="relative">
                    <div class="absolute -left-[31px] top-0 w-4 h-4 rounded-full bg-nepal-blue border-4 border-white shadow-sm"></div>
                    <div>
                        <div class="flex items-center space-x-2 text-xs">
                            <span class="font-bold text-slate-900 uppercase">
                                {{ __('' . ucfirst(str_replace('_', ' ', $log->to_status))) }}
                            </span>
                            <span class="text-slate-400">&bull;</span>
                            <span class="text-slate-500">{{ $log->created_at->format('M d, Y - h:i A') }}</span>
                            @if($log->staff)
                                <span class="text-slate-400">|</span>
                                <span class="text-slate-600 font-medium">{{ $log->staff->name }}</span>
                            @endif
                        </div>
                        @if($log->remarks)
                            <p class="text-xs text-slate-700 bg-slate-50 p-2.5 rounded-lg border border-slate-100 mt-2">
                                {{ $log->remarks }}
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-xs text-slate-400">
                    {{ app()->getLocale() === 'ne' ? 'कुनै कार्य इतिहास फेला परेन।' : 'No activity logs found.' }}
                </div>
            @endforelse
        </div>
    </div>

    <!-- Submitted Form Details -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-base font-bold text-slate-900 mb-4">{{ __('Submitted Details') }}</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            @if(!empty($application->form_data))
                @foreach($application->form_data as $key => $val)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-slate-500 block font-medium capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                        <strong class="text-slate-900 mt-0.5 block text-sm">{{ is_array($val) ? json_encode($val) : $val }}</strong>
                    </div>
                @endforeach
            @else
                <p class="text-slate-400">{{ app()->getLocale() === 'ne' ? 'विवरण उपलब्ध छैन।' : 'No details available.' }}</p>
            @endif
        </div>
    </div>

    <!-- Uploaded Documents -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-base font-bold text-slate-900 mb-4">{{ __('Attached Documents') }}</h2>

        @if($application->documents->isEmpty())
            <p class="text-xs text-slate-400">{{ app()->getLocale() === 'ne' ? 'कागजात संलग्न गरिएको छैन।' : 'No documents attached.' }}</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                @foreach($application->documents as $doc)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between">
                        <div class="truncate mr-2">
                            <span class="font-bold text-slate-800 block capitalize truncate">{{ str_replace('_', ' ', $doc->document_type) }}</span>
                            <span class="text-slate-400 text-[10px] block truncate">{{ $doc->original_filename }}</span>
                        </div>
                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                           class="px-2.5 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-800 rounded font-semibold text-[11px] shrink-0">
                            {{ app()->getLocale() === 'ne' ? 'हेर्नुहोस्' : 'View' }}
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
