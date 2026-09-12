@extends('layouts.staff')

@section('page_title', $district->name_ne . ' जिल्ला अनुगमन ड्यासबोर्ड (District Overview)')

@section('content')
<div class="space-y-6">
    <!-- District Header Banner -->
    <div class="bg-gradient-to-r from-indigo-900 via-slate-900 to-slate-900 rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase bg-indigo-500/30 text-indigo-200 border border-indigo-400/40">
                    जिल्ला समन्वय तथा अनुगमन (District Admin)
                </span>
                <span class="text-xs text-indigo-200">{{ $district->province->name_ne ?? 'बागमती प्रदेश' }}</span>
            </div>
            <h1 class="text-2xl font-black mt-2">{{ $district->name_ne }} ({{ $district->name_en }} District)</h1>
            <p class="text-xs text-slate-300 mt-0.5">जिल्लाभरका सम्पूर्ण स्थानीय तहहरू तथा वडा कार्यालयहरूको कार्यसम्पादन अनुगमन।</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('staff.district.palikas') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow transition">
                सबै स्थानीय तहहरू ({{ $stats['total_palikas'] }}) &rarr;
            </a>
        </div>
    </div>

    <!-- Counters Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">स्थानीय तह (Palikas)</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_palikas'] }}</div>
            <span class="text-[10px] text-indigo-600 font-semibold mt-1 inline-block">जिल्ला भित्र</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">कुल वडाहरू (Wards)</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_wards'] }}</div>
            <span class="text-[10px] text-slate-500 mt-1 inline-block">कार्यालयहरू</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">जिल्लाभरका निवेदनहरू</span>
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

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-purple-600 block uppercase">भेटघाट (Appointments)</span>
            <div class="text-2xl font-black text-purple-600 mt-1">{{ $stats['total_appointments'] }}</div>
            <span class="text-[10px] text-slate-500 mt-1 inline-block">{{ $stats['upcoming_appointments'] }} आगामी</span>
        </div>
    </div>

    <!-- Local Governments under this District -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900">यस जिल्लाका सम्पूर्ण स्थानीय तहहरू (Local Governments in {{ $district->name_en }})</h2>
                <p class="text-xs text-slate-500 mt-0.5">महानगरपालिका, नगरपालिका तथा गाउँपालिकाहरूको कार्यसम्पादन अवस्था</p>
            </div>
            <a href="{{ route('staff.district.palikas') }}" class="text-xs font-bold text-nepal-blue hover:underline">
                विस्तृत सूची &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                    <tr>
                        <th class="p-3">स्थानीय तहको नाम</th>
                        <th class="p-3">प्रकार (Type)</th>
                        <th class="p-3">वडा संख्या</th>
                        <th class="p-3">कुल निवेदनहरू</th>
                        <th class="p-3">पालिका प्रशासक स्थिति</th>
                        <th class="p-3 text-right">कार्य</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($palikas as $palika)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3">
                                <strong class="text-slate-900 text-sm block">{{ $palika->name_ne }}</strong>
                                <span class="text-slate-400 text-[11px]">{{ $palika->name_en }} ({{ $palika->code }})</span>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $palika->type === 'metropolitan' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $palika->type)) }}
                                </span>
                            </td>
                            <td class="p-3 font-bold text-slate-800">{{ $palika->wards_count }} वडाहरू</td>
                            <td class="p-3 font-bold text-slate-900">{{ $palika->applications_count }}</td>
                            <td class="p-3">
                                @if($palika->staff->isNotEmpty())
                                    <span class="inline-flex items-center text-emerald-600 font-bold text-[11px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        {{ $palika->staff->first()->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px]">admin.{{ strtolower($palika->code) }}@wardsewa.gov.np</span>
                                @endif
                            </td>
                            <td class="p-3 text-right">
                                <a href="{{ route('staff.district.applications', ['palika_id' => $palika->id]) }}" class="px-3 py-1.5 bg-slate-900 hover:bg-nepal-darkblue text-white rounded text-xs font-semibold transition">
                                    निवेदन अनुगमन &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent District Applications -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-900">जिल्लाभरका हालैका निवेदनहरू (Recent Submissions)</h2>
            <a href="{{ route('staff.district.applications') }}" class="text-xs font-bold text-nepal-blue hover:underline">सबै हेर्नुहोस् &rarr;</a>
        </div>

        @if($recentApplications->isEmpty())
            <p class="p-6 text-center text-slate-400 text-xs">हाल कुनै नयाँ निवेदन छैन।</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">निवेदन नम्बर</th>
                            <th class="p-3">नागरिकको नाम</th>
                            <th class="p-3">स्थानीय तह तथा वडा</th>
                            <th class="p-3">सेवाको प्रकार</th>
                            <th class="p-3">स्थिति</th>
                            <th class="p-3 text-right">विवरण</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentApplications as $app)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $app->application_number }}</td>
                                <td class="p-3 font-semibold text-slate-800">{{ $app->citizen->full_name }}</td>
                                <td class="p-3 text-slate-700">{{ $app->ward->palika->name_ne ?? '' }} - वडा {{ $app->ward->ward_number }}</td>
                                <td class="p-3 text-slate-700">{{ $app->serviceType->name_ne }}</td>
                                <td class="p-3">
                                    @if($app->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">स्वीकृत</span>
                                    @elseif($app->status === 'under_review')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">अध्ययनमा</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">दर्ता</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('staff.applications.show', $app->id) }}" class="text-nepal-blue font-bold hover:underline">हेर्नुहोस् &rarr;</a>
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
