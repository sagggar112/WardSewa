@extends('layouts.staff')

@section('page_title', __('System Dashboard'))

@section('content')
<div class="space-y-6">
    <!-- Super Admin Header Banner -->
    <div class="bg-gradient-to-r from-purple-900 via-indigo-950 to-slate-900 rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase bg-purple-500/30 text-purple-200 border border-purple-400/40">
                    {{ __('Super Admin') }}
                </span>
                <span class="text-xs text-purple-200">
                    {{ app()->getLocale() === 'ne' ? 'समग्र नेपाल डिजिटल वडा प्रणाली' : 'Nationwide Digital Ward System' }}
                </span>
            </div>
            <h1 class="text-2xl font-black mt-2">
                {{ app()->getLocale() === 'ne' ? 'केन्द्रीय नियन्त्रण केन्द्र' : 'WardSewa Central Control Center' }}
            </h1>
            <p class="text-xs text-purple-200 mt-0.5">
                {{ app()->getLocale() === 'ne' ? '७ प्रदेश, ७७ जिल्ला, ७५३ स्थानीय तह तथा ६,६७६ वडाहरूको केन्द्रीय डिजिटल सेवा व्यवस्थापन।' : 'Nationwide digital administration across 7 provinces, 77 districts, 753 palikas, and 6,676 wards.' }}
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('staff.superadmin.geography') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold border border-white/20 transition">
                {{ __('Geography Management') }}
            </a>
            <a href="{{ route('staff.superadmin.admins') }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow transition">
                {{ __('Admins Management') }}
            </a>
        </div>
    </div>

    <!-- Master Statistics Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">{{ __('District') }}</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_districts'] }}</div>
            <span class="text-[10px] text-purple-600 font-semibold mt-1 inline-block">{{ app()->getLocale() === 'ne' ? '७७ जिल्ला' : '77 Districts' }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">{{ __('Palika') }}</span>
            <div class="text-2xl font-black text-nepal-blue mt-1">{{ $stats['total_palikas'] }}</div>
            <span class="text-[10px] text-slate-500 mt-1 inline-block">{{ app()->getLocale() === 'ne' ? '७५३ स्थानीय तह' : '753 Local Govts' }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">{{ __('Ward') }}</span>
            <div class="text-2xl font-black text-nepal-crimson mt-1">{{ $stats['total_wards'] }}</div>
            <span class="text-[10px] text-slate-500 mt-1 inline-block">{{ app()->getLocale() === 'ne' ? 'डिजिटल सेवा उपलब्ध' : 'Digital Services Active' }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">{{ app()->getLocale() === 'ne' ? 'दर्ता नागरिक' : 'Registered Citizens' }}</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['total_citizens'] }}</div>
            <span class="text-[10px] text-slate-500 mt-1 inline-block">{{ app()->getLocale() === 'ne' ? 'प्रमाणित प्रयोगकर्ता' : 'Verified Users' }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">{{ __('Total Applications') }}</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_applications'] }}</div>
            <span class="text-[10px] text-amber-600 font-semibold mt-1 inline-block">{{ $stats['pending_applications'] }} {{ __('In Review') }}</span>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-[10px] font-bold text-slate-400 block uppercase">{{ __('Admins Management') }}</span>
            <div class="text-2xl font-black text-indigo-600 mt-1">{{ $stats['total_admins'] }}</div>
            <span class="text-[10px] text-slate-500 mt-1 inline-block">{{ app()->getLocale() === 'ne' ? '४-तह प्रशासनिक संरचना' : '4-Tier Admin Structure' }}</span>
        </div>
    </div>

    <!-- Geographic Hierarchy & Valley Districts Breakdown -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900">
                    {{ app()->getLocale() === 'ne' ? 'प्रशासनिक क्षेत्र तथा जिल्लागत अवस्था' : 'Administrative Regions & Districts Overview' }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ app()->getLocale() === 'ne' ? 'जिल्ला, स्थानीय तह तथा वडाहरूको स्थिति' : 'Districts, local governments, and wards deployment status' }}
                </p>
            </div>
            <a href="{{ route('staff.superadmin.geography') }}" class="text-xs font-bold text-nepal-blue hover:underline">
                {{ app()->getLocale() === 'ne' ? 'विस्तृत भूगोल तालिका' : 'Full Geography Registry' }} &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($districts as $d)
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex flex-col justify-between hover:border-nepal-blue hover:bg-white transition">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-[11px] font-bold text-purple-700 bg-purple-100 px-2 py-0.5 rounded">{{ $d->code }}</span>
                            <span class="text-xs text-slate-500 font-semibold">{{ $d->palikas_count }} {{ __('Palika') }}</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mt-2">
                            {{ app()->getLocale() === 'ne' ? ($d->name_ne ?? $d->name_en) : ($d->name_en ?? $d->name_ne) }}
                        </h3>
                        <p class="text-xs text-slate-500">
                            {{ app()->getLocale() === 'ne' ? ($d->province->name_ne ?? 'बागमती प्रदेश') : ($d->province->name_en ?? 'Bagmati Province') }}
                        </p>

                        <div class="mt-3 pt-3 border-t border-slate-200 text-xs text-slate-600 space-y-1">
                            <div class="flex justify-between">
                                <span>{{ app()->getLocale() === 'ne' ? 'कुल वडाहरू:' : 'Total Wards:' }}</span>
                                <strong class="text-slate-900">{{ $d->palikas->sum(fn($p) => $p->wards->count()) }} {{ __('Ward') }}</strong>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('District Admin') }}:</span>
                                <span class="font-semibold text-indigo-700">
                                    {{ $d->districtAdmin() ? $d->districtAdmin()->name : (app()->getLocale() === 'ne' ? 'नियुक्त हुन बाँकी' : 'Unassigned') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between text-xs">
                        <span class="text-slate-400 text-[10px]">
                            {{ app()->getLocale() === 'ne' ? ($d->province->name_ne ?? '') : ($d->province->name_en ?? '') }}
                        </span>
                        <a href="{{ route('staff.superadmin.geography') }}#district-{{ $d->id }}" class="text-nepal-blue font-bold hover:underline">
                            {{ app()->getLocale() === 'ne' ? 'तहहरू हेर्नुहोस्' : 'View Palikas' }} &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Submissions and Audit Trail -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Applications Across All Wards -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-slate-900">
                    {{ app()->getLocale() === 'ne' ? 'हालैका निवेदनहरू' : 'Recent Submissions' }}
                </h2>
                <a href="{{ route('staff.applications.index') }}" class="text-xs font-bold text-nepal-blue hover:underline">
                    {{ __('View All') }}
                </a>
            </div>

            @if($recentApplications->isEmpty())
                <p class="p-6 text-center text-slate-400 text-xs">{{ app()->getLocale() === 'ne' ? 'हाल कुनै निवेदन छैन।' : 'No recent submissions.' }}</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                            <tr>
                                <th class="p-2.5">{{ __('Application No') }}</th>
                                <th class="p-2.5">{{ app()->getLocale() === 'ne' ? 'नागरिक' : 'Citizen' }}</th>
                                <th class="p-2.5">{{ __('Service') }}</th>
                                <th class="p-2.5">{{ __('Ward') }}</th>
                                <th class="p-2.5">{{ __('Status') }}</th>
                                <th class="p-2.5 text-right">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentApplications as $app)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-2.5 font-mono font-bold text-slate-900">{{ $app->application_number }}</td>
                                    <td class="p-2.5 font-semibold text-slate-800">{{ $app->citizen->full_name }}</td>
                                    <td class="p-2.5 text-slate-700">
                                        {{ app()->getLocale() === 'ne' ? ($app->serviceType->name_ne ?? $app->serviceType->name_en) : ($app->serviceType->name_en ?? $app->serviceType->name_ne) }}
                                    </td>
                                    <td class="p-2.5 text-slate-600">
                                        {{ app()->getLocale() === 'ne' ? ($app->ward->palika->name_ne ?? '') : ($app->ward->palika->name_en ?? '') }} - {{ $app->ward->ward_number }}
                                    </td>
                                    <td class="p-2.5">
                                        @if($app->status === 'approved')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">{{ __('Approved') }}</span>
                                        @elseif($app->status === 'under_review')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">{{ __('Under Review') }}</span>
                                        @elseif($app->status === 'rejected')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">{{ __('Rejected') }}</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">{{ __('Submitted') }}</span>
                                        @endif
                                    </td>
                                    <td class="p-2.5 text-right">
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

        <!-- Recent Audit Logs -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-slate-900">{{ __('Security & Audit Logs') }}</h2>
                <a href="{{ route('staff.superadmin.audit-logs') }}" class="text-xs font-bold text-nepal-blue hover:underline">
                    {{ app()->getLocale() === 'ne' ? 'पूर्ण लग' : 'All Logs' }} &rarr;
                </a>
            </div>

            @if($recentAuditLogs->isEmpty())
                <p class="p-4 text-center text-slate-400 text-xs">{{ app()->getLocale() === 'ne' ? 'कुनै अडिट गतिविधि फेला परेन।' : 'No audit activity recorded.' }}</p>
            @else
                <div class="space-y-3 text-xs">
                    @foreach($recentAuditLogs as $log)
                        <div class="p-2.5 rounded-lg border border-slate-100 bg-slate-50">
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="font-bold text-purple-800 uppercase">{{ $log->action }}</span>
                                <span class="text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-700 mt-1 font-medium leading-snug">{{ $log->description }}</p>
                            @if($log->staff)
                                <span class="text-[10px] text-slate-400 block mt-1">
                                    {{ app()->getLocale() === 'ne' ? 'द्वारा:' : 'By:' }} {{ $log->staff->name }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
