@extends('layouts.staff')

@section('page_title', __('Applications & Recommendations') . ' - ' . $application->application_number)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto" x-data="{ docModal: false, rejectModal: false }">
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
                {{ __('Date') }}: {{ $application->created_at->format('Y-m-d H:i') }} | 
                {{ __('Fee:') }} {{ $application->payment_status === 'paid' ? (app()->getLocale() === 'ne' ? 'रु. ' . number_format($application->payment_amount, 2) . ' - भुक्तान सम्पन्न' : 'NPR ' . number_format($application->payment_amount, 2) . ' - Paid') : (app()->getLocale() === 'ne' ? 'बाँकी' : 'Due') }}
            </p>
        </div>

        <div>
            @if($application->status === 'approved')
                <span class="px-4 py-2 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Approved') }}
                </span>
            @elseif($application->status === 'under_review')
                <span class="px-4 py-2 rounded-xl text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 inline-flex items-center">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping mr-2"></span>
                    {{ __('Under Review') }}
                </span>
            @elseif($application->status === 'documents_requested')
                <span class="px-4 py-2 rounded-xl text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300">
                    {{ __('Documents Requested') }}
                </span>
            @elseif($application->status === 'rejected')
                <span class="px-4 py-2 rounded-xl text-xs font-bold bg-red-100 text-red-800 border border-red-300">
                    {{ __('Rejected') }}
                </span>
            @else
                <span class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-100 text-blue-800 border border-blue-300">
                    {{ __('Submitted') }}
                </span>
            @endif
        </div>
    </div>

    <!-- Staff Action Command Center -->
    <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-md">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-white">
                    {{ app()->getLocale() === 'ne' ? 'कर्मचारी कार्य सम्पादन प्यानल' : 'Staff Action Panel' }}
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">
                    {{ app()->getLocale() === 'ne' ? 'तपाईँको भूमिका:' : 'Your Role:' }} 
                    <strong class="text-nepal-gold uppercase">
                        {{ __($staff->role) }}
                    </strong>
                    @if(!$staff->canApproveApplications())
                        <span class="text-amber-300 ml-1">
                            {{ app()->getLocale() === 'ne' ? '(स्वीकृति अधिकार: वडा अध्यक्ष / वडा सचिव मात्र)' : '(Approval Authority: Ward Chairperson / Secretary only)' }}
                        </span>
                    @endif
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                @if($application->status === 'submitted')
                    <form method="POST" action="{{ route('staff.applications.start-review', $application->id) }}">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-lg transition">
                            {{ __('Start Review') }}
                        </button>
                    </form>
                @endif

                @if(!in_array($application->status, ['approved', 'rejected']))
                    <!-- Request more documents -->
                    <button @click="docModal = true" class="px-4 py-2.5 bg-rose-700 hover:bg-rose-800 text-white text-xs font-bold rounded-lg transition">
                        {{ app()->getLocale() === 'ne' ? 'थप कागजात माग्नुहोस्' : 'Request Documents' }}
                    </button>

                    @if($staff->canApproveApplications())
                        <!-- Approve with Digital Signature -->
                        <form method="POST" action="{{ route('staff.applications.approve', $application->id) }}" onsubmit="return confirm('{{ app()->getLocale() === 'ne' ? 'के तपाईँ यो निवेदनलाई आधिकारिक रूपमा स्वीकृत गरी सिफारिस पत्र जारी गर्न चाहनुहुन्छ?' : 'Are you sure you want to approve this application and issue the certificate?' }}');">
                            @csrf
                            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-lg shadow-md transition flex items-center space-x-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ app()->getLocale() === 'ne' ? 'स्वीकृत गरी सिफारिस पत्र जारी गर्नुहोस्' : 'Approve & Issue Certificate' }}</span>
                            </button>
                        </form>

                        <!-- Reject -->
                        <button @click="rejectModal = true" class="px-3.5 py-2.5 bg-red-900 hover:bg-red-800 text-red-200 text-xs font-semibold rounded-lg transition">
                            {{ __('Reject') }}
                        </button>
                    @endif
                @endif

                @if($application->status === 'approved')
                    <a href="{{ route('citizen.applications.certificate', $application->id) }}" target="_blank"
                       class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg shadow transition flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>{{ __('Download Certificate') }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Grid: Citizen Info & Submitted Data -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Citizen Information -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-3">
                {{ __('Applicant Info') }}
            </h2>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-500">{{ __('Full Name') }}:</span>
                    <strong class="text-slate-900">{{ $application->citizen->full_name }}</strong>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-500">{{ __('Phone:') }}</span>
                    <strong class="font-mono text-slate-900">{{ $application->citizen->phone }}</strong>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-500">{{ __('Citizenship No.') }}:</span>
                    <strong class="font-mono text-slate-900">{{ $application->citizen->citizenship_no ?? (app()->getLocale() === 'ne' ? 'उपलब्ध छैन' : 'Not Provided') }}</strong>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-500">{{ __('Tole / Street Address') }}:</span>
                    <strong class="text-slate-900">{{ $application->citizen->address }}</strong>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-500">{{ __('Ward') }}:</span>
                    <strong class="text-slate-900">
                        {{ app()->getLocale() === 'ne' ? ($application->ward->palika->name_ne ?? '') : ($application->ward->palika->name_en ?? '') }} - {{ __('Ward No.') }} {{ $application->ward->ward_number }}
                    </strong>
                </div>
            </div>
        </div>

        <!-- Form Data -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-3">
                {{ __('Submitted Details') }}
            </h2>
            <div class="space-y-2 text-xs">
                @if(!empty($application->form_data))
                    @foreach($application->form_data as $k => $v)
                        <div class="flex justify-between py-1 border-b border-slate-50">
                            <span class="text-slate-500 capitalize">{{ str_replace('_', ' ', $k) }}:</span>
                            <strong class="text-slate-900 text-right max-w-[60%]">{{ is_array($v) ? json_encode($v) : $v }}</strong>
                        </div>
                    @endforeach
                @else
                    <p class="text-slate-400">{{ app()->getLocale() === 'ne' ? 'फारम विवरण उपलब्ध छैन।' : 'No form data submitted.' }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Uploaded Documents Inspection -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-base font-bold text-slate-900 mb-3">{{ __('Attached Documents') }}</h2>
        @if($application->documents->isEmpty())
            <p class="text-xs text-slate-400">{{ app()->getLocale() === 'ne' ? 'कुनै प्रमाण कागजात संलग्न गरिएको छैन।' : 'No documents attached.' }}</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($application->documents as $doc)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex flex-col justify-between text-xs">
                        <div>
                            <span class="font-bold text-slate-900 block capitalize">{{ str_replace('_', ' ', $doc->document_type) }}</span>
                            <span class="text-[10px] text-slate-400 truncate block">{{ $doc->original_filename }}</span>
                        </div>
                        <div class="mt-3 pt-2 border-t border-slate-200">
                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="w-full inline-flex items-center justify-center px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded font-semibold text-[11px]">
                                {{ app()->getLocale() === 'ne' ? 'फाइल हेर्नुहोस्' : 'View File' }} &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Status History Audit Logs -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-base font-bold text-slate-900 mb-4">{{ __('Audit Trail') }}</h2>
        <div class="relative pl-6 border-l-2 border-slate-200 space-y-4 text-xs">
            @foreach($application->statusLogs as $log)
                <div class="relative">
                    <div class="absolute -left-[31px] top-0 w-4 h-4 rounded-full bg-slate-800 border-4 border-white shadow-sm"></div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-slate-900 uppercase">{{ __('' . ucfirst(str_replace('_', ' ', $log->to_status))) }}</span>
                            <span class="text-slate-400">&bull;</span>
                            <span class="text-slate-500">{{ $log->created_at->format('M d, Y - h:i A') }}</span>
                            @if($log->staff)
                                <span class="text-slate-400">|</span>
                                <span class="text-slate-700 font-semibold">{{ $log->staff->name }}</span>
                            @endif
                        </div>
                        @if($log->remarks)
                            <p class="text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-100 mt-1">
                                {{ $log->remarks }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal: Request Documents -->
    <div x-show="docModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4">
            <h3 class="text-base font-bold text-slate-900">
                {{ app()->getLocale() === 'ne' ? 'थप प्रमाण कागजातको माग गर्नुहोस्' : 'Request Additional Documents' }}
            </h3>
            <p class="text-xs text-slate-500">
                {{ app()->getLocale() === 'ne' ? 'नागरिकलाई कुन कागजात अपुग वा अस्पष्ट छ सो को कारण सहित नोटिस पठाइनेछ।' : 'The applicant will receive notification detailing which documents are required.' }}
            </p>
            <form method="POST" action="{{ route('staff.applications.request-docs', $application->id) }}">
                @csrf
                <textarea name="remarks" rows="3" required 
                          placeholder="{{ app()->getLocale() === 'ne' ? 'उदा. जग्गाधनी प्रमाणपुर्जाको स्पष्ट प्रतिलिपि वा चालु आ.व. को मालपोत रसिद पुनः अपलोड गर्नुहोला...' : 'Specify which documents or corrections are required...' }}"
                          class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs"></textarea>
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" @click="docModal = false" class="px-4 py-2 text-xs text-slate-600 font-semibold">
                        {{ app()->getLocale() === 'ne' ? 'रद्द' : 'Cancel' }}
                    </button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg">
                        {{ app()->getLocale() === 'ne' ? 'कागजात माग्नुहोस्' : 'Send Request' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Reject Application -->
    <div x-show="rejectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4">
            <h3 class="text-base font-bold text-red-600">
                {{ app()->getLocale() === 'ne' ? 'निवेदन अस्वीकृत गर्नुहोस्' : 'Reject Application' }}
            </h3>
            <p class="text-xs text-slate-500">
                {{ app()->getLocale() === 'ne' ? 'कृपया अस्वीकृत गर्नुको कानुनी वा प्रक्रियागत कारण स्पष्ट खुलाउनुहोस्।' : 'Please state the legal or procedural reason for rejection clearly.' }}
            </p>
            <form method="POST" action="{{ route('staff.applications.reject', $application->id) }}">
                @csrf
                <textarea name="rejection_reason" rows="3" required 
                          placeholder="{{ app()->getLocale() === 'ne' ? 'अस्वीकृत गर्नुको कारण...' : 'Reason for rejection...' }}"
                          class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs"></textarea>
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" @click="rejectModal = false" class="px-4 py-2 text-xs text-slate-600 font-semibold">
                        {{ app()->getLocale() === 'ne' ? 'रद्द' : 'Cancel' }}
                    </button>
                    <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg">
                        {{ app()->getLocale() === 'ne' ? 'अस्वीकृत पुष्टि गर्नुहोस्' : 'Confirm Rejection' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
