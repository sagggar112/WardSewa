@extends('layouts.staff')

@section('page_title', (app()->getLocale() === 'ne' ? ($district->name_ne ?? $district->name_en) . ' जिल्ला अनुगमन ड्यासबोर्ड' : ($district->name_en ?? $district->name_ne) . ' District Oversight Dashboard'))

@section('content')
<div class="space-y-6">
    <!-- District Header Banner -->
    <div class="bg-gradient-to-r from-indigo-900 via-slate-900 to-slate-900 rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase bg-indigo-500/30 text-indigo-200 border border-indigo-400/40">
                    {{ __('District Admin') }}
                </span>
                <span class="text-xs text-indigo-200">
                    {{ app()->getLocale() === 'ne' ? ($district->province->name_ne ?? 'बागमती प्रदेश') : ($district->province->name_en ?? 'Bagmati Province') }}
                </span>
            </div>
            <h1 class="text-2xl font-black mt-2">
                {{ app()->getLocale() === 'ne' ? (($district->name_ne ?? $district->name_en) . ' जिल्ला कार्यक्षेत्र') : (($district->name_en ?? $district->name_ne) . ' District Jurisdiction') }}
            </h1>
            <p class="text-xs text-slate-300 mt-0.5">
                {{ app()->getLocale() === 'ne' ? 'जिल्लाभरका सम्पूर्ण स्थानीय तहहरू तथा वडा कार्यालयहरूको कार्यसम्पादन अनुगमन।' : 'Performance monitoring and consolidated oversight of all palikas and ward offices in this district.' }}
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('staff.district.palikas') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow transition">
                {{ __('Local Governments') }} ({{ $stats['total_palikas'] }}) &rarr;
            </a>
        </div>
    </div>

    <!-- Counters Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">{{ __('Palika') }}</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_palikas'] }}</div>
            <span class="text-[10px] text-indigo-600 font-semibold mt-1 inline-block">{{ app()->getLocale() === 'ne' ? 'जिल्ला भित्र' : 'In District' }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">{{ __('Ward') }}</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_wards'] }}</div>
            <span class="text-[10px] text-slate-500 mt-1 inline-block">{{ app()->getLocale() === 'ne' ? 'कार्यालयहरू' : 'Offices' }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">{{ __('District-Wide Applications') }}</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_applications'] }}</div>
            <span class="text-[10px] text-slate-500 mt-1 inline-block">{{ app()->getLocale() === 'ne' ? 'कुल पेश' : 'Total' }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-amber-600 block uppercase">{{ __('In Review') }}</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $stats['pending_applications'] }}</div>
            <span class="text-[10px] text-amber-700 font-semibold mt-1 inline-block">{{ app()->getLocale() === 'ne' ? 'अध्ययन तथा समीक्षा' : 'Under Scrutiny' }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-emerald-600 block uppercase">{{ __('Approved Recommendations') }}</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['approved_applications'] }}</div>
            <span class="text-[10px] text-emerald-700 font-semibold mt-1 inline-block">{{ __('Completed') }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-purple-600 block uppercase">{{ __('Appointments') }}</span>
            <div class="text-2xl font-black text-purple-600 mt-1">{{ $stats['total_appointments'] }}</div>
            <span class="text-[10px] text-slate-500 mt-1 inline-block">{{ $stats['upcoming_appointments'] }} {{ __('Scheduled') }}</span>
        </div>
    </div>

    <!-- Local Governments under this District -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900">
                    {{ app()->getLocale() === 'ne' ? 'यस जिल्लाका सम्पूर्ण स्थानीय तहहरू' : 'Local Governments in ' . ($district->name_en ?? $district->name_ne) }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ app()->getLocale() === 'ne' ? 'महानगरपालिका, उप-महानगरपालिका, नगरपालिका तथा गाउँपालिकाहरूको कार्यसम्पादन अवस्था' : 'Performance overview of metropolitan, municipal, and rural local governments.' }}
                </p>
            </div>
            <a href="{{ route('staff.district.palikas') }}" class="text-xs font-bold text-nepal-blue hover:underline">
                {{ app()->getLocale() === 'ne' ? 'विस्तृत सूची' : 'Full Roster' }} &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                    <tr>
                        <th class="p-3">{{ app()->getLocale() === 'ne' ? 'स्थानीय तहको नाम' : 'Local Government Name' }}</th>
                        <th class="p-3">{{ app()->getLocale() === 'ne' ? 'प्रकार' : 'Type' }}</th>
                        <th class="p-3">{{ app()->getLocale() === 'ne' ? 'वडा संख्या' : 'Total Wards' }}</th>
                        <th class="p-3">{{ __('Total Applications') }}</th>
                        <th class="p-3">{{ app()->getLocale() === 'ne' ? 'पालिका प्रशासक स्थिति' : 'Palika Admin' }}</th>
                        <th class="p-3 text-right">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($palikas as $palika)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3">
                                <strong class="text-slate-900 text-sm block">
                                    {{ app()->getLocale() === 'ne' ? ($palika->name_ne ?? $palika->name_en) : ($palika->name_en ?? $palika->name_ne) }}
                                </strong>
                                <span class="text-slate-400 text-[11px] font-mono">{{ $palika->code }}</span>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $palika->type === 'metropolitan' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $palika->type)) }}
                                </span>
                            </td>
                            <td class="p-3 font-bold text-slate-800">{{ $palika->wards_count }} {{ __('Ward') }}</td>
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
                                    {{ app()->getLocale() === 'ne' ? 'निवेदन अनुगमन' : 'Inspect' }} &rarr;
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
            <h2 class="text-base font-bold text-slate-900">
                {{ app()->getLocale() === 'ne' ? 'जिल्लाभरका हालैका निवेदनहरू' : 'Recent District Applications' }}
            </h2>
            <a href="{{ route('staff.district.applications') }}" class="text-xs font-bold text-nepal-blue hover:underline">
                {{ __('View All') }} &rarr;
            </a>
        </div>

        @if($recentApplications->isEmpty())
            <p class="p-6 text-center text-slate-400 text-xs">{{ app()->getLocale() === 'ne' ? 'हाल कुनै नयाँ निवेदन छैन।' : 'No recent applications recorded.' }}</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">{{ __('Application No') }}</th>
                            <th class="p-3">{{ app()->getLocale() === 'ne' ? 'नागरिकको नाम' : 'Citizen Name' }}</th>
                            <th class="p-3">{{ __('Palika') }} & {{ __('Ward') }}</th>
                            <th class="p-3">{{ __('Service') }}</th>
                            <th class="p-3">{{ __('Status') }}</th>
                            <th class="p-3 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentApplications as $app)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $app->application_number }}</td>
                                <td class="p-3 font-semibold text-slate-800">{{ $app->citizen->full_name }}</td>
                                <td class="p-3 text-slate-700">
                                    {{ app()->getLocale() === 'ne' ? ($app->ward->palika->name_ne ?? '') : ($app->ward->palika->name_en ?? '') }} - {{ __('Ward No.') }} {{ $app->ward->ward_number }}
                                </td>
                                <td class="p-3 text-slate-700">
                                    {{ app()->getLocale() === 'ne' ? ($app->serviceType->name_ne ?? $app->serviceType->name_en) : ($app->serviceType->name_en ?? $app->serviceType->name_ne) }}
                                </td>
                                <td class="p-3">
                                    @if($app->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">{{ __('Approved') }}</span>
                                    @elseif($app->status === 'under_review')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">{{ __('Under Review') }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">{{ __('Submitted') }}</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('staff.applications.show', $app->id) }}" class="text-nepal-blue font-bold hover:underline">
                                        {{ app()->getLocale() === 'ne' ? 'हेर्नुहोस्' : 'View' }} &rarr;
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
