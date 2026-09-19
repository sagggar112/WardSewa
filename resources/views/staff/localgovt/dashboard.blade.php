@extends('layouts.staff')

@section('page_title', $palika->name_ne . ' पालिका कार्यसम्पादन ड्यासबोर्ड (Local Government Overview)')

@section('content')
<div class="space-y-6">
    <!-- Local Govt Header Banner -->
    <div class="bg-gradient-to-r from-blue-900 via-slate-900 to-slate-900 rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase bg-blue-500/30 text-blue-200 border border-blue-400/40">
                    {{ $stats['palika_type'] }} प्रशासक (Local Govt Admin)
                </span>
                <span class="text-xs text-blue-200">{{ $palika->district->name_ne ?? 'काठमाडौँ जिल्ला' }}</span>
            </div>
            <h1 class="text-2xl font-black mt-2">{{ $palika->name_ne }}</h1>
            <p class="text-xs text-slate-300 mt-0.5">यस पालिका अन्तर्गतका सम्पूर्ण {{ $stats['total_wards'] }} वडा कार्यालयहरूको कार्यसम्पादन तथा सेवा व्यवस्थापन।</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('staff.localgovt.wards') }}" class="px-4 py-2 bg-nepal-blue hover:bg-nepal-darkblue text-white rounded-xl text-xs font-bold shadow transition">
                सम्पूर्ण वडाहरू ({{ $stats['total_wards'] }}) &rarr;
            </a>
        </div>
    </div>

    <!-- Counters Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">वडा कार्यालयहरू</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_wards'] }}</div>
            <span class="text-[10px] text-nepal-blue font-semibold mt-1 inline-block">सञ्चालनमा</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">वडा प्रशासकहरू</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_ward_admins'] }}</div>
            <span class="text-[10px] text-slate-500 mt-1 inline-block">अध्यक्ष / सचिव</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">पालिकाभरका निवेदन</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_applications'] }}</div>
            <span class="text-[10px] text-slate-500 mt-1 inline-block">कुल पेश</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-amber-600 block uppercase">प्रक्रियामा रहेका (Pending)</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $stats['pending_applications'] }}</div>
            <span class="text-[10px] text-amber-700 font-semibold mt-1 inline-block">अध्ययन तथा समीक्षा</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-emerald-600 block uppercase">स्वीकृत सिफारिसहरू</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['approved_applications'] }}</div>
            <span class="text-[10px] text-emerald-700 font-semibold mt-1 inline-block">सम्पन्न</span>
        </div>

        <a href="{{ route('staff.appointments.index') }}" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-nepal-blue transition block group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-purple-600 block uppercase">भेटघाट (Appointments)</span>
                <span class="text-[10px] text-nepal-blue font-bold group-hover:underline">सबै हेर्नुहोस् &rarr;</span>
            </div>
            <div class="text-2xl font-black text-purple-600 mt-1">{{ $stats['total_appointments'] }}</div>
            <span class="text-[10px] text-slate-500 mt-1 inline-block">{{ $stats['upcoming_appointments'] }} आगामी भेटघाट</span>
        </a>
    </div>

    <!-- Wards Overview inside this Palika -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900">यस पालिकाका सम्पूर्ण वडाहरू (Wards in {{ $palika->name_en }})</h2>
                <p class="text-xs text-slate-500 mt-0.5">वडा कार्यालयहरूको सम्पर्क, प्रशासक तथा निवेदन अवस्था</p>
            </div>
            <a href="{{ route('staff.localgovt.wards') }}" class="text-xs font-bold text-nepal-blue hover:underline">
                विस्तृत वडा तालिका &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                    <tr>
                        <th class="p-3">वडा नम्बर</th>
                        <th class="p-3">कार्यालय ठेगाना</th>
                        <th class="p-3">फोन नम्बर</th>
                        <th class="p-3">वडा प्रशासक / अध्यक्ष</th>
                        <th class="p-3">निवेदन संख्या</th>
                        <th class="p-3 text-right">कार्य</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($wards as $ward)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3">
                                <span class="font-bold text-slate-900 text-sm">वडा नं. {{ $ward->ward_number }}</span>
                                @if($ward->ward_number == 32 && $palika->code === 'KMC')
                                    <span class="ml-1.5 px-2 py-0.5 bg-rose-100 text-rose-700 text-[10px] font-bold rounded">पाइलट वडा</span>
                                @endif
                            </td>
                            <td class="p-3 text-slate-700">{{ $ward->office_address }}</td>
                            <td class="p-3 font-mono text-slate-600">{{ $ward->office_phone ?? '-' }}</td>
                            <td class="p-3">
                                @if($ward->staff->isNotEmpty())
                                    <span class="inline-flex items-center text-emerald-700 font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        {{ $ward->staff->first()->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400">admin.ward{{ $ward->ward_number }}@wardsewa.gov.np</span>
                                @endif
                            </td>
                            <td class="p-3 font-bold text-slate-900">{{ $ward->applications_count }}</td>
                            <td class="p-3 text-right">
                                <a href="{{ route('staff.localgovt.applications', ['ward_id' => $ward->id]) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded font-semibold transition">
                                    हेर्नुहोस् &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Appointments in this Palika -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900">यस पालिका अन्तर्गतका नागरिक भेटघाटहरू (Appointments in {{ $palika->name_en }})</h2>
                <p class="text-xs text-slate-500 mt-0.5">विभिन्न वडाहरूमा नागरिकहरूले बुक गरेका हालैका भेटघाट तथा सिफारिस समय तालिका</p>
            </div>
            <a href="{{ route('staff.appointments.index') }}" class="text-xs font-bold text-nepal-blue hover:underline">
                सबै भेटघाट हेर्नुहोस् &rarr;
            </a>
        </div>

        @if($recentAppointments->isEmpty())
            <div class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                <p class="text-xs text-slate-500 font-bold">यस पालिका अन्तर्गत हाल कुनै पनि भेटघाट तालिका दर्ता भएको छैन।</p>
                <p class="text-[11px] text-slate-400 mt-0.5">नागरिकहरूले अनलाइन अपोइन्टमेन्ट बुक गर्दा यहाँ देखिनेछ।</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">अपोइन्टमेन्ट नं.</th>
                            <th class="p-3">सेवाग्राही</th>
                            <th class="p-3">सम्पर्क</th>
                            <th class="p-3">वडा नं.</th>
                            <th class="p-3">भ्रमण मिति तथा समय</th>
                            <th class="p-3">सेवा / प्रयोजन</th>
                            <th class="p-3">स्थिति</th>
                            <th class="p-3 text-right">कार्य</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentAppointments as $apt)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $apt->appointment_number }}</td>
                                <td class="p-3 font-semibold text-slate-900">{{ $apt->citizen->full_name }}</td>
                                <td class="p-3 font-mono text-slate-600">{{ $apt->citizen->phone }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded font-bold bg-slate-100 text-slate-800 text-[11px]">
                                        वडा नं. {{ $apt->ward->ward_number ?? '-' }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    <div class="font-bold text-slate-800">{{ $apt->appointment_date->format('Y-m-d') }}</div>
                                    <div class="text-[11px] text-nepal-blue font-semibold">{{ $apt->time_slot }}</div>
                                </td>
                                <td class="p-3">
                                    <div class="font-medium text-slate-900">{{ $apt->serviceType->name_ne ?? 'सामान्य परामर्श' }}</div>
                                    <div class="text-[10px] text-slate-400 truncate max-w-[150px]">{{ $apt->purpose }}</div>
                                </td>
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
                                    <a href="{{ route('staff.appointments.index') }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded font-semibold transition">
                                        विवरण &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
