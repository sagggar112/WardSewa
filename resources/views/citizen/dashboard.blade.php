@extends('layouts.citizen')

@section('title', __('Dashboard'))

@section('content')
<div class="space-y-8" x-data="{ 
    searchQuery: '', 
    activeCategory: 'all',
    matches(name, category) {
        const matchesCat = this.activeCategory === 'all' || category === this.activeCategory;
        const matchesQuery = this.searchQuery === '' || name.toLowerCase().includes(this.searchQuery.toLowerCase());
        return matchesCat && matchesQuery;
    }
}">

    <!-- 1. Digital Nagarik Civic Identity Card -->
    <div class="relative overflow-hidden bg-gradient-to-br from-nepal-darkblue via-slate-900 to-slate-950 rounded-2xl p-6 sm:p-8 text-white shadow-xl border border-slate-800">
        <!-- Background subtle crest decoration -->
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <x-logo class="w-64 h-64 text-white" />
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Citizen Identity Profile -->
            <div class="flex items-start sm:items-center space-x-4 sm:space-x-5">
                <div class="relative">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-nepal-crimson to-nepal-red text-white flex items-center justify-center font-extrabold text-2xl sm:text-3xl shadow-lg border-2 border-white/20">
                        {{ mb_substr($citizen->full_name, 0, 1) }}
                    </div>
                    <span class="absolute -bottom-1 -right-1 p-1 bg-emerald-500 rounded-full border-2 border-slate-900 text-white" title="{{ __('Verified Citizen') }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                </div>

                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            {{ __('Verified Citizen') }}
                        </span>
                        <span class="text-xs text-nepal-gold font-semibold uppercase tracking-wider">
                            {{ __('Digital Citizen Card') }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold mt-1 tracking-tight">
                        {{ $citizen->full_name }}
                    </h1>

                    <!-- Civic Credentials Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-1.5 mt-3 text-xs text-slate-300">
                        <div class="flex items-center space-x-1.5">
                            <span class="text-slate-400 font-medium">{{ __('Ward Office & Jurisdiction') }}:</span>
                            <span class="font-bold text-white">
                                @if($citizen->ward)
                                    {{ app()->getLocale() === 'ne' ? ($citizen->ward->palika->name_ne ?? $citizen->ward->palika->name_en) : ($citizen->ward->palika->name_en ?? $citizen->ward->palika->name_ne) }}, {{ __('Ward No.') }} {{ $citizen->ward->ward_number }}
                                @else
                                    {{ __('Not Assigned') }}
                                @endif
                            </span>
                        </div>

                        <div class="flex items-center space-x-1.5">
                            <span class="text-slate-400 font-medium">{{ __('Citizenship No:') }}</span>
                            <span class="font-mono font-bold text-slate-100">{{ $citizen->citizenship_no ?? 'N/A' }}</span>
                        </div>

                        <div class="flex items-center space-x-1.5">
                            <span class="text-slate-400 font-medium">{{ __('Official Contact:') }}</span>
                            <span class="font-mono font-bold text-slate-100">{{ $citizen->phone }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action Toolbar -->
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 shrink-0 pt-2 lg:pt-0">
                <a href="{{ route('citizen.applications.create') }}" class="w-full sm:w-auto px-5 py-3 bg-nepal-crimson hover:bg-nepal-red text-white text-xs font-bold rounded-xl shadow-lg hover:shadow-xl transition flex items-center justify-center space-x-2 border border-nepal-red/40">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>{{ __('New Application') }}</span>
                </a>

                <a href="{{ route('citizen.appointments.create') }}" class="w-full sm:w-auto px-4 py-3 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-xl border border-white/20 transition flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4 text-nepal-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ __('Book Appointment') }}</span>
                </a>

                <a href="{{ route('citizen.complaints.create') }}" class="w-full sm:w-auto px-4 py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl border border-slate-700 transition flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <span>{{ __('Grievance') }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. High-Priority Action Alert (Conditional) -->
    @if(isset($actionRequiredApplications) && $actionRequiredApplications->isNotEmpty())
        @foreach($actionRequiredApplications as $actionApp)
            <div class="bg-gradient-to-r from-amber-50 to-rose-50 border-l-4 border-amber-500 rounded-xl p-4 sm:p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start space-x-3.5">
                    <div class="p-2 bg-amber-100 rounded-lg text-amber-700 shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 bg-amber-200 text-amber-900 font-black text-[10px] rounded uppercase tracking-wider">
                                {{ __('Action Required') }}
                            </span>
                            <span class="font-mono font-bold text-xs text-slate-800">
                                #{{ $actionApp->application_number }}
                            </span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 mt-1">
                            {{ __('Additional Documents Requested') }}: {{ app()->getLocale() === 'ne' ? ($actionApp->serviceType->name_ne ?? $actionApp->serviceType->name_en) : ($actionApp->serviceType->name_en ?? $actionApp->serviceType->name_ne) }}
                        </h4>
                        <p class="text-xs text-slate-600 mt-0.5">
                            {{ __('Please upload the requested documents to process your application without delay.') }}
                        </p>
                    </div>
                </div>

                <a href="{{ route('citizen.applications.show', $actionApp->id) }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow-sm transition shrink-0 flex items-center justify-center space-x-1.5">
                    <span>{{ __('Upload Documents') }}</span>
                    <span>&rarr;</span>
                </a>
            </div>
        @endforeach
    @endif

    <!-- 3. Systematic 4-Quadrant Metric Matrix -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Pending Submissions -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow transition relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Pending Reviews') }}</span>
                <span class="p-2.5 rounded-xl bg-amber-50 text-amber-600 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="text-3xl font-black text-slate-900 mt-2">{{ $stats['pending_reviews'] }}</div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400 font-medium">{{ __('In Review') }}</span>
                <a href="{{ route('citizen.applications.index', ['status' => 'under_review']) }}" class="text-amber-600 font-bold hover:underline">
                    {{ __('View Status') }} &rarr;
                </a>
            </div>
        </div>

        <!-- Approved Recommendations -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow transition relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Approved Recommendations') }}</span>
                <span class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="text-3xl font-black text-slate-900 mt-2 text-emerald-600">{{ $stats['approved_certificates'] }}</div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400 font-medium">{{ __('Ready to Download') }}</span>
                <a href="{{ route('citizen.applications.index', ['status' => 'approved']) }}" class="text-emerald-600 font-bold hover:underline">
                    {{ __('Download Certificate') }} &rarr;
                </a>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow transition relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Upcoming Appointments') }}</span>
                <span class="p-2.5 rounded-xl bg-blue-50 text-nepal-blue group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </span>
            </div>
            <div class="text-3xl font-black text-slate-900 mt-2">{{ $stats['upcoming_appointments'] ?? 0 }}</div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400 font-medium">{{ __('Scheduled Ward Visit') }}</span>
                <a href="{{ route('citizen.appointments.index') }}" class="text-nepal-blue font-bold hover:underline">
                    {{ __('View All') }} &rarr;
                </a>
            </div>
        </div>

        <!-- Open Complaints -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow transition relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Open Grievances') }}</span>
                <span class="p-2.5 rounded-xl bg-rose-50 text-nepal-crimson group-hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </span>
            </div>
            <div class="text-3xl font-black text-slate-900 mt-2">{{ $stats['open_complaints'] }}</div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400 font-medium">{{ __('Active Resolution') }}</span>
                <a href="{{ route('citizen.complaints.index') }}" class="text-nepal-crimson font-bold hover:underline">
                    {{ __('View Status') }} &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Systematic Service Catalog & Search Center -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">
                    {{ __('Digital Services Available from Ward Office') }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ __('Select your required service and submit your application from home with zero hassle.') }}
                </p>
            </div>

            <!-- Instant Search Bar -->
            <div class="relative w-full md:w-80">
                <input type="text" 
                       x-model="searchQuery" 
                       placeholder="{{ __('Search Ward Services') }}" 
                       class="w-full pl-9 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-nepal-blue focus:border-transparent transition" />
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="flex items-center space-x-2 overflow-x-auto pb-3 mb-5 border-b border-slate-100 text-xs">
            <button @click="activeCategory = 'all'" 
                    :class="activeCategory === 'all' ? 'bg-slate-900 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3.5 py-1.5 rounded-lg transition whitespace-nowrap">
                {{ __('All Categories') }}
            </button>
            <button @click="activeCategory = 'recommendation'" 
                    :class="activeCategory === 'recommendation' ? 'bg-nepal-blue text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3.5 py-1.5 rounded-lg transition whitespace-nowrap">
                {{ __('Recommendations') }}
            </button>
            <button @click="activeCategory = 'vital_registration'" 
                    :class="activeCategory === 'vital_registration' ? 'bg-emerald-700 text-white font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                    class="px-3.5 py-1.5 rounded-lg transition whitespace-nowrap">
                {{ __('Vital Registration') }}
            </button>
        </div>

        <!-- Interactive Service Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($allServices as $svc)
                @php
                    $svcName = app()->getLocale() === 'ne' ? ($svc->name_ne ?? $svc->name_en) : ($svc->name_en ?? $svc->name_ne);
                @endphp
                <div x-show="matches('{{ addslashes($svcName) }}', '{{ $svc->category }}')" 
                     class="p-4 rounded-xl border border-slate-200 hover:border-nepal-blue/60 hover:shadow-md transition flex flex-col justify-between group bg-slate-50/50 hover:bg-white">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $svc->category === 'recommendation' ? 'bg-blue-100 text-nepal-blue' : ($svc->category === 'vital_registration' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ $svc->category === 'recommendation' ? __('Recommendations') : ($svc->category === 'vital_registration' ? __('Vital Registration') : __('Services')) }}
                            </span>
                            <span class="text-[11px] font-bold text-slate-500">
                                {{ $svc->turnaround_days }} {{ app()->getLocale() === 'ne' ? 'दिन' : 'days' }}
                            </span>
                        </div>

                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-nepal-blue transition leading-snug">
                            {{ $svcName }}
                        </h3>

                        <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                            <span>
                                <strong>{{ __('Fee:') }}</strong> 
                                {{ $svc->fee > 0 ? (app()->getLocale() === 'ne' ? 'रु. ' . number_format($svc->fee, 0) : 'NPR ' . number_format($svc->fee, 0)) : __('Free') }}
                            </span>
                            <span>
                                {{ count($svc->required_documents ?? []) }} {{ app()->getLocale() === 'ne' ? 'प्रमाण' : 'Docs' }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100">
                        <a href="{{ route('citizen.applications.create', ['service' => $svc->code]) }}" class="w-full inline-flex items-center justify-center px-3 py-2 bg-slate-900 group-hover:bg-nepal-crimson text-white text-xs font-bold rounded-lg transition shadow-sm">
                            <span>{{ __('Apply Now') }}</span>
                            <span class="ml-1 group-hover:translate-x-1 transition">&rarr;</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 5. Application Tracking Pipeline & Lifecycle View -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">
                    {{ __('Application Tracking Pipeline') }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ __('Track live processing stages of your submitted citizen recommendations.') }}
                </p>
            </div>
            <a href="{{ route('citizen.applications.index') }}" class="text-xs font-bold text-nepal-blue hover:underline">
                {{ __('View All') }} &rarr;
            </a>
        </div>

        @if($recentApplications->isEmpty())
            <div class="text-center py-12 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-xs text-slate-500 font-medium">
                    {{ app()->getLocale() === 'ne' ? 'तपाईँले हालसम्म कुनै पनि निवेदन पेश गर्नुभएको छैन।' : 'You have not submitted any applications yet.' }}
                </p>
                <a href="{{ route('citizen.applications.create') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-nepal-crimson text-white text-xs font-bold rounded-lg shadow-sm">
                    {{ __('New Application') }}
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($recentApplications as $app)
                    @php
                        $isApproved = $app->status === 'approved';
                        $isRejected = $app->status === 'rejected';
                        $isDocsReq = $app->status === 'documents_requested';
                        $isReview = $app->status === 'under_review';
                        $isSubmitted = $app->status === 'submitted';
                    @endphp
                    <div class="p-5 rounded-xl border border-slate-200 hover:border-slate-300 transition bg-slate-50/40">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200/80">
                            <div>
                                <span class="font-mono font-bold text-xs text-nepal-blue">
                                    {{ $app->application_number }}
                                </span>
                                <h3 class="text-base font-extrabold text-slate-900 mt-0.5">
                                    {{ app()->getLocale() === 'ne' ? ($app->serviceType->name_ne ?? $app->serviceType->name_en) : ($app->serviceType->name_en ?? $app->serviceType->name_ne) }}
                                </h3>
                                <span class="text-[11px] text-slate-500">
                                    {{ __('Date') }}: {{ $app->created_at->format('M d, Y') }}
                                </span>
                            </div>

                            <div class="flex items-center space-x-2 shrink-0">
                                @if($isApproved)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        {{ __('Approved') }}
                                    </span>
                                    <a href="{{ route('citizen.applications.show', $app->id) }}" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition shadow-sm">
                                        {{ __('Download Certificate') }}
                                    </a>
                                @elseif($isDocsReq)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        {{ __('Documents Requested') }}
                                    </span>
                                    <a href="{{ route('citizen.applications.show', $app->id) }}" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg transition shadow-sm">
                                        {{ __('Upload Documents') }}
                                    </a>
                                @elseif($isReview)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        {{ __('Under Review') }}
                                    </span>
                                    <a href="{{ route('citizen.applications.show', $app->id) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-lg transition">
                                        {{ __('View Status') }}
                                    </a>
                                @elseif($isRejected)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                        {{ __('Rejected') }}
                                    </span>
                                    <a href="{{ route('citizen.applications.show', $app->id) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-lg transition">
                                        {{ __('View Status') }}
                                    </a>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                        {{ __('Submitted') }}
                                    </span>
                                    <a href="{{ route('citizen.applications.show', $app->id) }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-lg transition">
                                        {{ __('View Status') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- 5-Step Visual Progress Stepper -->
                        <div class="mt-4 pt-1">
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-center text-[10px] font-bold">
                                <!-- Step 1: Submission -->
                                <div class="p-2 rounded-lg {{ $isSubmitted || $isReview || $isDocsReq || $isApproved ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-500' }}">
                                    {{ __('Step 1: Submission') }}
                                </div>
                                <!-- Step 2: Document Check -->
                                <div class="p-2 rounded-lg {{ $isReview || $isDocsReq || $isApproved ? ($isDocsReq ? 'bg-amber-100 text-amber-800 font-extrabold' : 'bg-emerald-100 text-emerald-800') : 'bg-slate-200 text-slate-500' }}">
                                    {{ __('Step 2: Document Check') }}
                                </div>
                                <!-- Step 3: Verification -->
                                <div class="p-2 rounded-lg {{ $isReview || $isApproved ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-500' }}">
                                    {{ __('Step 3: Verification') }}
                                </div>
                                <!-- Step 4: Approval -->
                                <div class="p-2 rounded-lg {{ $isApproved ? 'bg-emerald-100 text-emerald-800' : ($isRejected ? 'bg-red-100 text-red-800' : 'bg-slate-200 text-slate-500') }}">
                                    {{ __('Step 4: Approval') }}
                                </div>
                                <!-- Step 5: Certificate Issued -->
                                <div class="p-2 rounded-lg col-span-2 sm:col-span-1 {{ $isApproved ? 'bg-emerald-600 text-white font-extrabold shadow-sm' : 'bg-slate-200 text-slate-500' }}">
                                    {{ __('Step 5: Certificate Issued') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 6. Local Ward Leadership & Official Ward Bulletin Desk (2-Column Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Local Ward Leadership & Office Desk (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900 tracking-tight">
                            {{ __('Your Ward Representatives') }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            @if($citizen->ward)
                                {{ app()->getLocale() === 'ne' ? ($citizen->ward->palika->name_ne ?? $citizen->ward->palika->name_en) : ($citizen->ward->palika->name_en ?? $citizen->ward->palika->name_ne) }} - {{ __('Ward No.') }} {{ $citizen->ward->ward_number }}
                            @endif
                        </p>
                    </div>
                    <span class="px-2.5 py-1 bg-nepal-blue/10 text-nepal-blue text-xs font-bold rounded-lg">
                        {{ __('Office Hours') }}
                    </span>
                </div>

                <!-- Office Operating Info -->
                <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-200/80 mb-5 flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-2 text-slate-700">
                        <svg class="w-4 h-4 text-nepal-blue shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-bold">{{ __('Sunday to Friday: 10:00 AM - 5:00 PM') }}</span>
                    </div>
                    <span class="text-slate-500 text-[11px] font-semibold">
                        {{ __('Official Contact:') }} {{ $citizen->ward?->phone ?? '01-4601234' }}
                    </span>
                </div>

                <!-- Staff Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($wardOfficials as $official)
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex items-start space-x-3.5">
                            <div class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-sm shrink-0">
                                {{ mb_substr($official->name, 0, 1) }}
                            </div>
                            <div class="truncate">
                                <h4 class="text-sm font-bold text-slate-900 truncate">{{ $official->name }}</h4>
                                <span class="inline-block text-[11px] font-bold text-nepal-crimson mt-0.5">
                                    {{ $official->role === 'ward_chair' ? __('Ward Chairperson') : ($official->role === 'secretary' ? __('Ward Secretary') : __('Front Desk Assistant')) }}
                                </span>
                                <div class="text-[11px] text-slate-500 font-mono mt-1">
                                    {{ $official->phone }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-6 text-xs text-slate-400">
                            {{ app()->getLocale() === 'ne' ? 'यस वडाका कर्मचारी विवरण अद्यावधिक भइरहेको छ।' : 'Ward official contact details are being updated.' }}
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-medium">
                    {{ __('Need to meet with ward leadership?') }}
                </span>
                <a href="{{ route('citizen.appointments.create') }}" class="px-4 py-2 bg-slate-900 hover:bg-nepal-blue text-white text-xs font-bold rounded-lg transition shadow-sm">
                    {{ __('Book Appointment with Ward') }} &rarr;
                </a>
            </div>
        </div>

        <!-- Official Ward Bulletin & Notices (1 col) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h2 class="text-base font-extrabold text-slate-900 tracking-tight">
                        {{ __('Official Bulletins & Notices') }}
                    </h2>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>

                @if($wardNotices->isEmpty())
                    <p class="text-xs text-slate-400 py-6 text-center">
                        {{ __('No new notices published at this moment.') }}
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach($wardNotices as $notice)
                            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 text-xs hover:border-slate-200 transition">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-nepal-crimson">
                                        {{ $notice->category ?? __('Notices') }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-mono">
                                        {{ $notice->created_at->format('M d') }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-slate-900 mt-1 leading-snug">
                                    {{ $notice->title }}
                                </h3>
                                <p class="text-slate-600 mt-1 line-clamp-2">
                                    {{ $notice->content }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                <span class="text-[11px] text-slate-400 font-medium">
                    {{ __('WardSewa Official Municipal Broadcast') }}
                </span>
            </div>
        </div>
    </div>

</div>
@endsection
