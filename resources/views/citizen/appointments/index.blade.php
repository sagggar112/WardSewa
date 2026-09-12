@extends('layouts.citizen')

@section('title', 'मेरो अपोइन्टमेन्टहरू (My Appointments)')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="text-xs font-bold text-nepal-blue uppercase tracking-wider">वडा कार्यालय भ्रमण सेवा (In-Person Office Visits)</div>
            <h1 class="text-2xl font-black text-slate-900 mt-1">मेरो अपोइन्टमेन्टहरू (My Appointments)</h1>
            <p class="text-xs text-slate-500 mt-1">
                वडा कार्यालयमा सिफारिस पत्र संकलन, कागजात प्रमाणीकरण वा वडा अध्यक्ष/सचिव भेटघाटका लागि समय तालिका।
            </p>
        </div>
        <div>
            <a href="{{ route('citizen.appointments.create') }}" class="inline-flex items-center px-4 py-2.5 bg-nepal-red hover:bg-nepal-crimson text-white text-xs font-bold rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                + नयाँ अपोइन्टमेन्ट लिनुहोस् (Book Appointment)
            </a>
        </div>
    </div>

    <!-- Informational Banner -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-xs text-nepal-darkblue flex items-start space-x-3">
        <svg class="w-5 h-5 text-nepal-blue flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="space-y-1">
            <p class="font-bold">अपोइन्टमेन्टको फाइदा (Queue-Free Ward Visit):</p>
            <p class="text-slate-600 leading-relaxed">
                तपाईँले तय गरेको समयमा वडा कार्यालय पुग्दा लाइन बस्नु पर्दैन। तोकिएको समय भन्दा १५ मिनेट अगावै सम्बन्धित सक्कल कागजातहरू (नागरिकता, जग्गाधनी प्रमाण, फोटो आदि) सहित कार्यालयमा उपस्थित हुनुहोस्।
            </p>
        </div>
    </div>

    <!-- Appointments Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex items-center justify-between">
            <h2 class="font-bold text-sm text-slate-800">अपोइन्टमेन्ट सूची (Appointments History)</h2>
            <span class="text-xs text-slate-500 font-mono">जम्मा: {{ $appointments->total() }}</span>
        </div>

        @if($appointments->isEmpty())
            <div class="text-center py-12 px-4">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-slate-400 mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-700">कुनै पनि अपोइन्टमेन्ट बुक गरिएको छैन</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    वडा कार्यालयमा प्रत्यक्ष कामका लागि अग्रिम समय तालिका बुक गर्नुहोस्।
                </p>
                <a href="{{ route('citizen.appointments.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-nepal-blue text-white text-xs font-semibold rounded-lg hover:bg-nepal-darkblue transition">
                    अहिले अपोइन्टमेन्ट लिनुहोस्
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">अपोइन्टमेन्ट नं.</th>
                            <th class="py-3 px-4">सम्बन्धित सेवा / उद्देश्य</th>
                            <th class="py-3 px-4">वडा कार्यालय</th>
                            <th class="py-3 px-4">भ्रमण मिति</th>
                            <th class="py-3 px-4">समय तालिका (Time Slot)</th>
                            <th class="py-3 px-4">स्थिति</th>
                            <th class="py-3 px-4">कैफियत</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($appointments as $apt)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                    {{ $apt->appointment_number }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-800">{{ $apt->purpose }}</div>
                                    @if($apt->serviceType)
                                        <div class="text-[11px] text-nepal-blue mt-0.5">{{ $apt->serviceType->name_ne }} ({{ $apt->serviceType->name_en }})</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-700">
                                    <span class="font-medium">{{ $apt->ward->palika->name_ne ?? 'पालिका' }}</span>
                                    <span class="text-slate-400 block text-[11px]">वडा नं. {{ $apt->ward->ward_number ?? $citizen->ward->ward_number }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">
                                    {{ $apt->appointment_date->format('Y-m-d') }}
                                    <span class="block text-[11px] text-slate-500 font-normal">({{ $apt->appointment_date->format('l') }})</span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-slate-700">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 font-semibold">
                                        {{ $apt->time_slot }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($apt->status === 'scheduled')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            तयार / तोकिएको (Scheduled)
                                        </span>
                                    @elseif($apt->status === 'completed')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            सम्पन्न (Completed)
                                        </span>
                                    @elseif($apt->status === 'cancelled')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            रद्द गरिएको (Cancelled)
                                        </span>
                                    @elseif($apt->status === 'no_show')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700 border border-slate-300">
                                            अनुपस्थित (No Show)
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                            {{ ucfirst($apt->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 text-[11px] max-w-[200px] truncate">
                                    {{ $apt->remarks ?: '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
