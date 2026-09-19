@extends('layouts.staff')

@section('page_title', 'कर्मचारी कार्यसम्पादन ड्यासबोर्ड')

@section('content')
<div class="space-y-6">
    <!-- Header Welcome & Role Jurisdiction -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                @if($staff->isWardChair())
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase bg-blue-600 text-white">
                        वडा अध्यक्ष (CHAIRPERSON)
                    </span>
                @elseif($staff->isSecretary())
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase bg-amber-600 text-white">
                        वडा सचिव (SECRETARY)
                    </span>
                @elseif($staff->isClerk())
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase bg-emerald-600 text-white">
                        फ्रन्ट डेस्क सहायक (CLERK)
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase bg-nepal-blue text-white">
                        {{ str_replace('_', ' ', $staff->role) }}
                    </span>
                @endif

                <span class="text-xs text-slate-500 font-semibold">
                    {{ $staff->ward ? ($staff->ward->palika->name_ne . ' - वडा नं. ' . $staff->ward->ward_number) : 'काठमाडौँ महानगरपालिका' }}
                </span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 mt-2">{{ $staff->name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ $staff->designation ?? 'वडा कर्मचारी' }} • आधिकारिक लगइन: <span class="font-mono">{{ $staff->email }}</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if(in_array($staff->role, ['ward_chair', 'ward_admin', 'super_admin']))
                <a href="{{ route('staff.team.index') }}" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow transition inline-flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>कर्मचारी टोली (Ward Team)</span>
                </a>
            @endif
            <a href="{{ route('staff.applications.index') }}" class="px-4 py-2.5 bg-nepal-darkblue hover:bg-nepal-blue text-white text-xs font-bold rounded-xl shadow transition">
                सबै निवेदन हेर्नुहोस् &rarr;
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
                        <span>वडा अध्यक्ष कार्यजिम्मेवारी (Ward Chair Executive Authority)</span>
                    </div>
                    <h2 class="text-xl font-black text-white">अन्तिम प्रमाणीकरण, सिफारिस निर्णय तथा वडा सुपरिवेक्षण</h2>
                    <p class="text-xs text-blue-100 leading-relaxed">
                        तपाईंको मुख्य जिम्मेवारी नागरिक सिफारिस पत्रहरूमा अन्तिम डिजिटल हस्ताक्षर, उजुरी तथा गुनासोहरूको न्यायोचित सुनुवाई, र वडाका सचिव तथा सहायक कर्मचारीहरूको कार्यसम्पादन व्यवस्थापन हो।
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-3 shrink-0">
                    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl border border-white/10 text-center">
                        <span class="text-[10px] text-blue-200 uppercase font-bold block">स्वीकृति पर्खिरहेका</span>
                        <div class="text-2xl font-black text-white mt-0.5">{{ $stats['under_review'] + $stats['submitted'] }}</div>
                        <a href="{{ route('staff.applications.index', ['status' => 'under_review']) }}" class="text-[10px] text-blue-300 underline font-semibold mt-0.5 block">समीक्षा गर्नुहोस्</a>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl border border-white/10 text-center">
                        <span class="text-[10px] text-blue-200 uppercase font-bold block">सक्रिय कर्मचारी</span>
                        <div class="text-2xl font-black text-white mt-0.5">{{ $teamStats['total'] ?? 0 }} जना</div>
                        <a href="{{ route('staff.team.index') }}" class="text-[10px] text-blue-300 underline font-semibold mt-0.5 block">टोली हेर्नुहोस्</a>
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
                        <span>वडा सचिव कार्यजिम्मेवारी (Ward Secretary Scrutiny & Review)</span>
                    </div>
                    <h2 class="text-xl font-black text-white">कागजात रुजु, सिफारिस तयारी तथा प्राविधिक जाँच शाखा</h2>
                    <p class="text-xs text-amber-100 leading-relaxed">
                        तपाईंको मुख्य जिम्मेवारी नागरिकले पेश गरेका कागजातहरूको कानुनसम्मत रुजु गर्नु, आवश्यक भए थप प्रमाण माग गर्नु (Request Documents), र प्रमाणित प्रतिवेदन तयार गरी अध्यक्ष समक्ष स्वीकृतिका लागि पेश गर्नु हो।
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-3 shrink-0">
                    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl border border-white/10 text-center">
                        <span class="text-[10px] text-amber-200 uppercase font-bold block">रुजु गर्न बाँकी</span>
                        <div class="text-2xl font-black text-white mt-0.5">{{ $stats['submitted'] }}</div>
                        <a href="{{ route('staff.applications.index', ['status' => 'submitted']) }}" class="text-[10px] text-amber-300 underline font-semibold mt-0.5 block">रुजु सुरु गर्नुहोस्</a>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl border border-white/10 text-center">
                        <span class="text-[10px] text-amber-200 uppercase font-bold block">कागजात मागिएका</span>
                        <div class="text-2xl font-black text-white mt-0.5">{{ $stats['documents_requested'] }}</div>
                        <a href="{{ route('staff.applications.index', ['status' => 'documents_requested']) }}" class="text-[10px] text-amber-300 underline font-semibold mt-0.5 block">अनुगमन</a>
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
                        <span>सहायक कर्मचारी कार्यजिम्मेवारी (Front Desk Intake & Counter Support)</span>
                    </div>
                    <h2 class="text-xl font-black text-white">नागरिक सोधपुछ, टोकन व्यवस्थापन तथा दर्ता सहायता</h2>
                    <p class="text-xs text-emerald-100 leading-relaxed">
                        तपाईंको मुख्य जिम्मेवारी वडा कार्यालयमा उपस्थित नागरिकहरूलाई टोकन वितरण, भेटघाट चेक-इन, अनलाइन फारम भर्न सहायता प्रदान गर्नु, र निवेदनको पछिल्लो स्थिति जानकारी दिनु हो।
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-3 shrink-0">
                    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl border border-white/10 text-center">
                        <span class="text-[10px] text-emerald-200 uppercase font-bold block">आजका भेटघाट</span>
                        <div class="text-2xl font-black text-white mt-0.5">{{ $stats['today_appointments'] }}</div>
                        <a href="{{ route('staff.appointments.index') }}" class="text-[10px] text-emerald-300 underline font-semibold mt-0.5 block">टोकन डेस्क</a>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-xl border border-white/10 text-center">
                        <span class="text-[10px] text-emerald-200 uppercase font-bold block">भर्खरै दर्ता</span>
                        <div class="text-2xl font-black text-white mt-0.5">{{ $stats['submitted'] }}</div>
                        <a href="{{ route('staff.applications.index') }}" class="text-[10px] text-emerald-300 underline font-semibold mt-0.5 block">नागरिक सहायता</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Counters Grid (All Ward Staff) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-blue-600 block uppercase">दर्ता भएका</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['submitted'] }}</div>
            <a href="{{ route('staff.applications.index', ['status' => 'submitted']) }}" class="text-[10px] text-nepal-blue font-semibold hover:underline mt-1 inline-block">हेर्नुहोस् &rarr;</a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-amber-600 block uppercase">अध्ययनमा</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['under_review'] }}</div>
            <a href="{{ route('staff.applications.index', ['status' => 'under_review']) }}" class="text-[10px] text-amber-600 font-semibold hover:underline mt-1 inline-block">हेर्नुहोस् &rarr;</a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-rose-600 block uppercase">कागजात मागिएको</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['documents_requested'] }}</div>
            <a href="{{ route('staff.applications.index', ['status' => 'documents_requested']) }}" class="text-[10px] text-rose-600 font-semibold hover:underline mt-1 inline-block">हेर्नुहोस् &rarr;</a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-emerald-600 block uppercase">स्वीकृत (Approved)</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['approved'] }}</div>
            <a href="{{ route('staff.applications.index', ['status' => 'approved']) }}" class="text-[10px] text-emerald-600 font-semibold hover:underline mt-1 inline-block">हेर्नुहोस् &rarr;</a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-red-600 block uppercase">अस्वीकृत</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['rejected'] }}</div>
            <a href="{{ route('staff.applications.index', ['status' => 'rejected']) }}" class="text-[10px] text-red-600 font-semibold hover:underline mt-1 inline-block">हेर्नुहोस् &rarr;</a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[11px] font-bold text-purple-600 block uppercase">खुला उजुरी/गुनासो</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['open_complaints'] }}</div>
            <span class="text-[10px] text-slate-400 mt-1 inline-block">नागरिक गुनासो</span>
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
                    <h2 class="text-base font-bold text-slate-900">नागरिक भेटघाट तथा समय तालिका (Citizen Appointments)</h2>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-nepal-blue">
                        {{ $stats['today_appointments'] }} आज / {{ $stats['upcoming_appointments'] }} आगामी
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">नागरिकहरूले यस वडाका लागि अनलाइन बुक गरेका प्रत्यक्ष भेटघाट तथा सिफारिस तालिका</p>
            </div>
            <a href="{{ route('staff.appointments.index') }}" class="text-xs text-nepal-blue font-bold hover:underline flex items-center gap-1">
                <span>सबै भेटघाट व्यवस्थापन &rarr;</span>
            </a>
        </div>

        @php
            $displayAppointments = $todayAppointments->isNotEmpty() ? $todayAppointments : $upcomingAppointments;
        @endphp

        @if($displayAppointments->isEmpty())
            <div class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-xs text-slate-500 font-bold">हाल कुनै पनि नागरिक भेटघाट तालिका दर्ता भएको छैन।</p>
                <p class="text-[11px] text-slate-400 mt-0.5">नागरिक पोर्टलबाट नयाँ अपोइन्टमेन्ट बुक हुनासाथ यहाँ देखिनेछ।</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">अपोइन्टमेन्ट नं.</th>
                            <th class="p-3">सेवाग्राही</th>
                            <th class="p-3">सम्पर्क नम्बर</th>
                            <th class="p-3">भ्रमण मिति तथा समय</th>
                            <th class="p-3">सेवाको नाम</th>
                            <th class="p-3">उद्देश्य</th>
                            <th class="p-3">स्थिति</th>
                            <th class="p-3 text-right">कार्य</th>
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
                                <td class="p-3 text-slate-700">{{ $apt->serviceType->name_ne ?? 'सामान्य परामर्श' }}</td>
                                <td class="p-3 text-slate-500 truncate max-w-xs">{{ $apt->purpose }}</td>
                                <td class="p-3">
                                    @if($apt->status === 'completed')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">सम्पन्न</span>
                                    @elseif($apt->status === 'cancelled')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">रद्द</span>
                                    @elseif($apt->status === 'confirmed')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">स्वीकृत</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">प्रस्तावित</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('staff.appointments.index') }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded text-xs font-semibold transition">
                                        व्यवस्थापन &rarr;
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
                        निर्णय तथा प्रमाणीकरणका लागि पेश भएका निवेदनहरू (Approval Queue)
                    @elseif($staff->isSecretary())
                        कागजात रुजु तथा अध्ययन सूची (Scrutiny & Review Queue)
                    @else
                        हालै दर्ता भएका निवेदनहरू (Recent Submissions)
                    @endif
                </h2>
                <p class="text-xs text-slate-500">
                    @if($staff->isWardChair())
                        समीक्षा गरी अन्तिम सिफारिस जारी वा प्रमाणीकरण गर्नुहोस्
                    @elseif($staff->isSecretary())
                        कागजात जाँच गरी अपुग कागजात माग वा स्वीकृतिका लागि सिफारिस गर्नुहोस्
                    @else
                        नागरिकलाई निवेदनको स्थिति जानकारी तथा सहायता दिनुहोस्
                    @endif
                </p>
            </div>
            <a href="{{ route('staff.applications.index') }}" class="text-xs text-nepal-blue font-semibold hover:underline">सबै हेर्नुहोस् &rarr;</a>
        </div>

        @if($recentApplications->isEmpty())
            <p class="text-xs text-slate-400 py-6 text-center">कुनै नयाँ निवेदन फेला परेन।</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">निवेदन नं.</th>
                            <th class="p-3">नागरिकको नाम</th>
                            <th class="p-3">सम्पर्क नम्बर</th>
                            <th class="p-3">सेवाको नाम</th>
                            <th class="p-3">दर्ता मिति</th>
                            <th class="p-3">स्थिति</th>
                            <th class="p-3 text-right">कार्य</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentApplications as $app)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $app->application_number }}</td>
                                <td class="p-3 font-semibold text-slate-800">{{ $app->citizen->full_name }}</td>
                                <td class="p-3 font-mono text-slate-500">{{ $app->citizen->phone }}</td>
                                <td class="p-3 text-slate-700">{{ $app->serviceType->name_ne }}</td>
                                <td class="p-3 text-slate-500">{{ $app->created_at->format('M d, Y') }}</td>
                                <td class="p-3">
                                    @if($app->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">स्वीकृत</span>
                                    @elseif($app->status === 'under_review')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">अध्ययनमा</span>
                                    @elseif($app->status === 'documents_requested')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">कागजात मागिएको</span>
                                    @elseif($app->status === 'rejected')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">अस्वीकृत</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">दर्ता भएको</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('staff.applications.show', $app->id) }}" class="px-3 py-1.5 bg-nepal-darkblue hover:bg-nepal-blue text-white rounded text-xs font-semibold transition">
                                        @if($staff->canApproveApplications())
                                            समीक्षा / निर्णय &rarr;
                                        @else
                                            विवरण हेर्नुहोस् &rarr;
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
                    <h3 class="text-sm font-bold text-slate-900">वडा कर्मचारी टोली व्यवस्थापन (Ward Operational Team)</h3>
                    <p class="text-xs text-slate-500">कुल सक्रिय कर्मचारी: {{ $teamStats['total'] ?? 0 }} जना (सचिव: {{ $teamStats['secretaries'] ?? 0 }}, सहायक: {{ $teamStats['clerks'] ?? 0 }})</p>
                </div>
            </div>
            <a href="{{ route('staff.team.index') }}" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-100 text-slate-800 text-xs font-bold rounded-xl transition shadow-sm">
                कर्मचारी थप्नुहोस् / सम्पादन &rarr;
            </a>
        </div>
    @endif
</div>
@endsection
