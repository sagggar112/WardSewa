@extends('layouts.staff')

@section('page_title', __('Ward Dashboard'))

@section('content')
<div class="space-y-6">
    <!-- Header Welcome & Role Jurisdiction -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                @if($staff->isWardChair())
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase bg-blue-600 text-white">
                        {{ __('Ward Chairperson') }}
                    </span>
                @elseif($staff->isSecretary())
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase bg-amber-600 text-white">
                        {{ __('Ward Secretary') }}
                    </span>
                @elseif($staff->isClerk())
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase bg-emerald-600 text-white">
                        {{ __('Ward Clerk') }}
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase bg-nepal-blue text-white">
                        {{ str_replace('_', ' ', $staff->role) }}
                    </span>
                @endif

                <span class="text-xs text-slate-500 font-semibold">
                    @if($staff->ward)
                        {{ (app()->getLocale() === 'ne' ? ($staff->ward->palika->name_ne ?? $staff->ward->palika->name_en) : ($staff->ward->palika->name_en ?? $staff->ward->palika->name_ne)) . ' - ' . __('Ward No.') . ' ' . $staff->ward->ward_number }}
                    @else
                        {{ __('Pilot Ward Office') }}
                    @endif
                </span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 mt-2">{{ $staff->name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ $staff->designation ?? __('Ward Operations') }} • {{ __('Email:') }} <span class="font-mono">{{ $staff->email }}</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if(in_array($staff->role, ['ward_chair', 'ward_admin', 'super_admin']))
                <a href="{{ route('staff.team.index') }}" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow transition inline-flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>{{ __('Ward Team') }}</span>
                </a>
            @endif
            <a href="{{ route('staff.applications.index') }}" class="px-4 py-2.5 bg-nepal-darkblue hover:bg-nepal-blue text-white text-xs font-bold rounded-xl shadow transition">
                {{ __('View All') }} &rarr;
            </a>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- ROLE-TAILORED RESPONSIBILITY DASHBOARD BANNER                             -->
    <!-- ========================================================================= -->
    @if($staff->isWardChair() || $staff->role === 'ward_admin')
        <!-- WARD CHAIRPERSON RESPONSIBILITY SUITE -->
        <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="space-y-2 max-w-2xl">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 rounded-full text-[11px] font-bold text-blue-200">
                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                        <span>{{ app()->getLocale() === 'ne' ? 'वडा अध्यक्ष कार्यजिम्मेवारी' : 'Ward Chair Executive Authority' }}</span>
                    </div>
                    <h2 class="text-xl font-black text-white">{{ app()->getLocale() === 'ne' ? 'अन्तिम प्रमाणीकरण, सिफारिस निर्णय तथा वडा सुपरिवेक्षण' : 'Final Verification, Recommendation Decisions & Ward Oversight' }}</h2>
                    <p class="text-xs text-blue-100 leading-relaxed">
                        {{ app()->getLocale() === 'ne' ? 'तपाईंको मुख्य जिम्मेवारी नागरिक सिफारिस पत्रहरूमा अन्तिम डिजिटल हस्ताक्षर, उजुरी तथा गुनासोहरूको न्यायोचित सुनुवाई, र वडाका सचिव तथा सहायक कर्मचारीहरूको कार्यसम्पादन व्यवस्थापन हो।' : 'Your primary responsibility is digital signing of citizen recommendations, resolving grievances fairly, and managing the operational workflow of ward staff.' }}
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-3 shrink-0">
                    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl border border-white/10 text-center">
                        <span class="text-[10px] text-blue-200 uppercase font-bold block">{{ __('Pending Reviews') }}</span>
                        <div class="text-2xl font-black text-white mt-0.5">{{ $stats['under_review'] + $stats['submitted'] }}</div>
                        <a href="{{ route('staff.applications.index', ['status' => 'under_review']) }}" class="text-[10px] text-blue-300 underline font-semibold mt-0.5 block">{{ app()->getLocale() === 'ne' ? 'समीक्षा गर्नुहोस्' : 'Review Applications' }}</a>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl border border-white/10 text-center">
                        <span class="text-[10px] text-blue-200 uppercase font-bold block">{{ app()->getLocale() === 'ne' ? 'सक्रिय कर्मचारी' : 'Active Staff' }}</span>
                        <div class="text-2xl font-black text-white mt-0.5">{{ $teamStats['total'] ?? 0 }} {{ app()->getLocale() === 'ne' ? 'जना' : 'Staff' }}</div>
                        <a href="{{ route('staff.team.index') }}" class="text-[10px] text-blue-300 underline font-semibold mt-0.5 block">{{ app()->getLocale() === 'ne' ? 'टोली हेर्नुहोस्' : 'View Team' }}</a>
                    </div>
                </div>
            </div>
        </div>

    @elseif($staff->isSecretary())
        <!-- WARD SECRETARY RESPONSIBILITY SUITE -->
        <div class="bg-gradient-to-r from-amber-800 via-orange-900 to-slate-900 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="space-y-2 max-w-2xl">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 rounded-full text-[11px] font-bold text-amber-200">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <span>{{ app()->getLocale() === 'ne' ? 'वडा सचिव कार्यजिम्मेवारी' : 'Ward Secretary Scrutiny & Review' }}</span>
                    </div>
                    <h2 class="text-xl font-black text-white">{{ app()->getLocale() === 'ne' ? 'कागजात रुजु, सिफारिस तयारी तथा प्राविधिक जाँच शाखा' : 'Document Scrutiny, Recommendation Preparation & Review' }}</h2>
                    <p class="text-xs text-amber-100 leading-relaxed">
                        {{ app()->getLocale() === 'ne' ? 'तपाईंको मुख्य जिम्मेवारी नागरिकले पेश गरेका कागजातहरूको कानुनसम्मत रुजु गर्नु, आवश्यक भए थप प्रमाण माग गर्नु, र प्रमाणित प्रतिवेदन तयार गरी अध्यक्ष समक्ष स्वीकृतिका लागि पेश गर्नु हो।' : 'Your primary responsibility is verifying citizen documents legally, requesting additional documents if necessary, and preparing verified reports for the Chairperson.' }}
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-3 shrink-0">
                    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl border border-white/10 text-center">
                        <span class="text-[10px] text-amber-200 uppercase font-bold block">{{ app()->getLocale() === 'ne' ? 'रुजु गर्न बाँकी' : 'Awaiting Scrutiny' }}</span>
                        <div class="text-2xl font-black text-white mt-0.5">{{ $stats['submitted'] }}</div>
                        <a href="{{ route('staff.applications.index', ['status' => 'submitted']) }}" class="text-[10px] text-amber-300 underline font-semibold mt-0.5 block">{{ app()->getLocale() === 'ne' ? 'रुजु सुरु गर्नुहोस्' : 'Start Review' }}</a>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl border border-white/10 text-center">
                        <span class="text-[10px] text-amber-200 uppercase font-bold block">{{ __('Documents Requested') }}</span>
                        <div class="text-2xl font-black text-white mt-0.5">{{ $stats['documents_requested'] }}</div>
                        <a href="{{ route('staff.applications.index', ['status' => 'documents_requested']) }}" class="text-[10px] text-amber-300 underline font-semibold mt-0.5 block">{{ app()->getLocale() === 'ne' ? 'अनुगमन' : 'Track' }}</a>
                    </div>
                </div>
            </div>
        </div>

    @elseif($staff->isClerk())
        <!-- FRONT DESK CLERK RESPONSIBILITY SUITE -->
        <div class="bg-gradient-to-r from-emerald-800 via-teal-900 to-slate-900 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="space-y-2 max-w-2xl">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 rounded-full text-[11px] font-bold text-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>{{ app()->getLocale() === 'ne' ? 'सहायक कर्मचारी कार्यजिम्मेवारी' : 'Front Desk Intake & Counter Support' }}</span>
                    </div>
                    <h2 class="text-xl font-black text-white">{{ app()->getLocale() === 'ne' ? 'नागरिक सोधपुछ, टोकन व्यवस्थापन तथा दर्ता सहायता' : 'Citizen Helpdesk, Token Management & Registration Support' }}</h2>
                    <p class="text-xs text-emerald-100 leading-relaxed">
                        {{ app()->getLocale() === 'ne' ? 'तपाईंको मुख्य जिम्मेवारी वडा कार्यालयमा उपस्थित नागरिकहरूलाई टोकन वितरण, भेटघाट चेक-इन, अनलाइन फारम भर्न सहायता प्रदान गर्नु, र निवेदनको पछिल्लो स्थिति जानकारी दिनु हो।' : 'Your primary responsibility is issuing tokens to visiting citizens, appointment check-ins, helping fill online forms, and providing status updates.' }}
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-3 shrink-0">
                    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl border border-white/10 text-center">
                        <span class="text-[10px] text-emerald-200 uppercase font-bold block">{{ app()->getLocale() === 'ne' ? 'आजका भेटघाट' : "Today's Appointments" }}</span>
                        <div class="text-2xl font-black text-white mt-0.5">{{ $stats['today_appointments'] }}</div>
                        <a href="{{ route('staff.appointments.index') }}" class="text-[10px] text-emerald-300 underline font-semibold mt-0.5 block">{{ app()->getLocale() === 'ne' ? 'टोकन डेस्क' : 'Token Desk' }}</a>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl border border-white/10 text-center">
                        <span class="text-[10px] text-emerald-200 uppercase font-bold block">{{ app()->getLocale() === 'ne' ? 'भर्खरै दर्ता' : 'Newly Registered' }}</span>
                        <div class="text-2xl font-black text-white mt-0.5">{{ $stats['submitted'] }}</div>
                        <a href="{{ route('staff.applications.index') }}" class="text-[10px] text-emerald-300 underline font-semibold mt-0.5 block">{{ app()->getLocale() === 'ne' ? 'नागरिक सहायता' : 'Citizen Support' }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Counters Grid (All Ward Staff) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-blue-600 block uppercase">{{ __('Submitted') }}</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['submitted'] }}</div>
            <a href="{{ route('staff.applications.index', ['status' => 'submitted']) }}" class="text-[10px] text-nepal-blue font-semibold hover:underline mt-1 inline-block">{{ __('View Status') }} &rarr;</a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-amber-600 block uppercase">{{ __('Under Review') }}</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['under_review'] }}</div>
            <a href="{{ route('staff.applications.index', ['status' => 'under_review']) }}" class="text-[10px] text-amber-600 font-semibold hover:underline mt-1 inline-block">{{ __('View Status') }} &rarr;</a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-rose-600 block uppercase">{{ __('Documents Requested') }}</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['documents_requested'] }}</div>
            <a href="{{ route('staff.applications.index', ['status' => 'documents_requested']) }}" class="text-[10px] text-rose-600 font-semibold hover:underline mt-1 inline-block">{{ __('View Status') }} &rarr;</a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-emerald-600 block uppercase">{{ __('Approved') }}</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['approved'] }}</div>
            <a href="{{ route('staff.applications.index', ['status' => 'approved']) }}" class="text-[10px] text-emerald-600 font-semibold hover:underline mt-1 inline-block">{{ __('View Status') }} &rarr;</a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-red-600 block uppercase">{{ __('Rejected') }}</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['rejected'] }}</div>
            <a href="{{ route('staff.applications.index', ['status' => 'rejected']) }}" class="text-[10px] text-red-600 font-semibold hover:underline mt-1 inline-block">{{ __('View Status') }} &rarr;</a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-purple-600 block uppercase">{{ __('Open Grievances') }}</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['open_complaints'] }}</div>
            <span class="text-[10px] text-slate-400 mt-1 inline-block">{{ __('Grievance') }}</span>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- ROLE-SPECIFIC ACTION PANELS                                               -->
    <!-- ========================================================================= -->
    <!-- APPOINTMENTS & TOKENS PANEL (Visible to all Ward Staff: Chair, Secretary, Clerk, Admin) -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
            <div>
                <div class="flex items-center space-x-2">
                    <h2 class="text-base font-bold text-slate-900">{{ __('Appointments') }}</h2>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-nepal-blue">
                        {{ $stats['today_appointments'] }} {{ app()->getLocale() === 'ne' ? 'आज' : 'Today' }} / {{ $stats['upcoming_appointments'] }} {{ app()->getLocale() === 'ne' ? 'आगामी' : 'Upcoming' }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">{{ app()->getLocale() === 'ne' ? 'नागरिकहरूले यस वडाका लागि अनलाइन बुक गरेका प्रत्यक्ष भेटघाट तथा सिफारिस तालिका' : 'Direct in-person citizen appointments and recommendation consultations scheduled for this ward' }}</p>
            </div>
            <a href="{{ route('staff.appointments.index') }}" class="text-xs text-nepal-blue font-bold hover:underline flex items-center gap-1">
                <span>{{ app()->getLocale() === 'ne' ? 'सबै भेटघाट व्यवस्थापन' : 'Manage Appointments' }} &rarr;</span>
            </a>
        </div>

        @php
            $displayAppointments = $todayAppointments->isNotEmpty() ? $todayAppointments : $upcomingAppointments;
        @endphp

        @if($displayAppointments->isEmpty())
            <div class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-xs text-slate-500 font-bold">{{ app()->getLocale() === 'ne' ? 'हाल कुनै पनि नागरिक भेटघाट तालिका दर्ता भएको छैन।' : 'No citizen appointments scheduled at the moment.' }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">{{ app()->getLocale() === 'ne' ? 'नागरिक पोर्टलबाट नयाँ अपोइन्टमेन्ट बुक हुनासाथ यहाँ देखिनेछ।' : 'New appointments booked from the citizen portal will appear here.' }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">{{ app()->getLocale() === 'ne' ? 'अपोइन्टमेन्ट नं.' : 'Appointment No.' }}</th>
                            <th class="p-3">{{ app()->getLocale() === 'ne' ? 'सेवाग्राही' : 'Citizen' }}</th>
                            <th class="p-3">{{ app()->getLocale() === 'ne' ? 'सम्पर्क नम्बर' : 'Contact No.' }}</th>
                            <th class="p-3">{{ app()->getLocale() === 'ne' ? 'भ्रमण मिति तथा समय' : 'Date & Time' }}</th>
                            <th class="p-3">{{ app()->getLocale() === 'ne' ? 'सेवाको नाम' : 'Service' }}</th>
                            <th class="p-3">{{ app()->getLocale() === 'ne' ? 'उद्देश्य' : 'Purpose' }}</th>
                            <th class="p-3">{{ __('Status') }}</th>
                            <th class="p-3 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($displayAppointments as $apt)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $apt->appointment_number }}</td>
                                <td class="p-3 font-semibold text-slate-900">{{ $apt->citizen->full_name }}</td>
                                <td class="p-3 font-mono text-slate-600">{{ $apt->citizen->phone }}</td>
                                <td class="p-3">
                                    <div class="font-bold text-slate-800">{{ $apt->appointment_date->format('Y-m-d') }}</div>
                                    <div class="text-[11px] text-nepal-blue font-semibold">{{ $apt->time_slot }}</div>
                                </td>
                                <td class="p-3 text-slate-700">
                                    {{ app()->getLocale() === 'ne' ? ($apt->serviceType->name_ne ?? $apt->serviceType->name_en ?? 'सामान्य परामर्श') : ($apt->serviceType->name_en ?? $apt->serviceType->name_ne ?? 'General Inquiry') }}
                                </td>
                                <td class="p-3 text-slate-500 truncate max-w-xs">{{ $apt->purpose }}</td>
                                <td class="p-3">
                                    @if($apt->status === 'completed')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">{{ __('Completed') }}</span>
                                    @elseif($apt->status === 'cancelled')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">{{ __('Cancelled') }}</span>
                                    @elseif($apt->status === 'confirmed')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">{{ __('Confirmed') }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">{{ __('Scheduled') }}</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('staff.appointments.index') }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded text-xs font-semibold transition">
                                        {{ app()->getLocale() === 'ne' ? 'व्यवस्थापन' : 'Manage' }} &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Recent Submissions Table (All Staff) -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900">
                    @if($staff->isWardChair())
                        {{ app()->getLocale() === 'ne' ? 'निर्णय तथा प्रमाणीकरणका लागि पेश भएका निवेदनहरू' : 'Approval Queue' }}
                    @elseif($staff->isSecretary())
                        {{ app()->getLocale() === 'ne' ? 'कागजात रुजु तथा अध्ययन सूची' : 'Scrutiny & Review Queue' }}
                    @else
                        {{ __('Recent Applications') }}
                    @endif
                </h2>
                <p class="text-xs text-slate-500">
                    @if($staff->isWardChair())
                        {{ app()->getLocale() === 'ne' ? 'समीक्षा गरी अन्तिम सिफारिस जारी वा प्रमाणीकरण गर्नुहोस्' : 'Review and issue final recommendations or approval' }}
                    @elseif($staff->isSecretary())
                        {{ app()->getLocale() === 'ne' ? 'कागजात जाँच गरी अपुग कागजात माग वा स्वीकृतिका लागि सिफारिस गर्नुहोस्' : 'Scrutinize documents, request missing files, or recommend for approval' }}
                    @else
                        {{ app()->getLocale() === 'ne' ? 'नागरिकलाई निवेदनको स्थिति जानकारी तथा सहायता दिनुहोस्' : 'Track application status and support citizens at the counter' }}
                    @endif
                </p>
            </div>
            <a href="{{ route('staff.applications.index') }}" class="text-xs text-nepal-blue font-semibold hover:underline">{{ __('View All') }} &rarr;</a>
        </div>

        @if($recentApplications->isEmpty())
            <p class="text-xs text-slate-400 py-6 text-center">{{ app()->getLocale() === 'ne' ? 'कुनै नयाँ निवेदन फेला परेन।' : 'No applications found.' }}</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">{{ __('Application Number') }}</th>
                            <th class="p-3">{{ __('Full Name') }}</th>
                            <th class="p-3">{{ __('Mobile Number') }}</th>
                            <th class="p-3">{{ app()->getLocale() === 'ne' ? 'सेवाको नाम' : 'Service' }}</th>
                            <th class="p-3">{{ __('Date') }}</th>
                            <th class="p-3">{{ __('Status') }}</th>
                            <th class="p-3 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentApplications as $app)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $app->application_number }}</td>
                                <td class="p-3 font-semibold text-slate-800">{{ $app->citizen->full_name }}</td>
                                <td class="p-3 font-mono text-slate-500">{{ $app->citizen->phone }}</td>
                                <td class="p-3 text-slate-700">
                                    {{ app()->getLocale() === 'ne' ? ($app->serviceType->name_ne ?? $app->serviceType->name_en) : ($app->serviceType->name_en ?? $app->serviceType->name_ne) }}
                                </td>
                                <td class="p-3 text-slate-500">{{ $app->created_at->format('M d, Y') }}</td>
                                <td class="p-3">
                                    @if($app->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">{{ __('Approved') }}</span>
                                    @elseif($app->status === 'under_review')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">{{ __('Under Review') }}</span>
                                    @elseif($app->status === 'documents_requested')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">{{ __('Documents Requested') }}</span>
                                    @elseif($app->status === 'rejected')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">{{ __('Rejected') }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">{{ __('Submitted') }}</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('staff.applications.show', $app->id) }}" class="px-3 py-1.5 bg-nepal-darkblue hover:bg-nepal-blue text-white rounded text-xs font-semibold transition">
                                        @if($staff->canApproveApplications())
                                            {{ app()->getLocale() === 'ne' ? 'समीक्षा / निर्णय' : 'Review & Decide' }} &rarr;
                                        @else
                                            {{ __('View Status') }} &rarr;
                                        @endif
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Ward Team Summary Card for Chairperson -->
    @if(in_array($staff->role, ['ward_chair', 'ward_admin']))
        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">{{ app()->getLocale() === 'ne' ? 'वडा कर्मचारी टोली व्यवस्थापन' : 'Ward Operational Team' }}</h3>
                    <p class="text-xs text-slate-500">{{ app()->getLocale() === 'ne' ? 'कुल सक्रिय कर्मचारी: ' . ($teamStats['total'] ?? 0) . ' जना (सचिव: ' . ($teamStats['secretaries'] ?? 0) . ', सहायक: ' . ($teamStats['clerks'] ?? 0) . ')' : 'Total Active Staff: ' . ($teamStats['total'] ?? 0) . ' (Secretaries: ' . ($teamStats['secretaries'] ?? 0) . ', Clerks: ' . ($teamStats['clerks'] ?? 0) . ')' }}</p>
                </div>
            </div>
            <a href="{{ route('staff.team.index') }}" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-100 text-slate-800 text-xs font-bold rounded-xl transition shadow-sm">
                {{ app()->getLocale() === 'ne' ? 'कर्मचारी थप्नुहोस् / सम्पादन' : 'Manage Team' }} &rarr;
            </a>
        </div>
    @endif
</div>
@endsection
