@extends('layouts.citizen')

@section('title', __('Dashboard'))

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-nepal-darkblue via-slate-900 to-slate-900 rounded-2xl p-6 text-white shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="text-xs text-nepal-gold font-bold uppercase tracking-wider">{{ __('Welcome') }}</div>
            <h1 class="text-2xl font-extrabold mt-0.5">{{ $citizen->full_name }}</h1>
            <p class="text-xs text-slate-300 mt-1">
                @if($citizen->ward)
                    {{ app()->getLocale() === 'ne' ? ($citizen->ward->palika->name_ne ?? $citizen->ward->palika->name_en) : ($citizen->ward->palika->name_en ?? $citizen->ward->palika->name_ne) }} - {{ __('Ward No.') }} {{ $citizen->ward->ward_number }} |
                @endif
                {{ __('Phone:') }} {{ $citizen->phone }}
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('citizen.applications.create') }}" class="px-4 py-2.5 bg-nepal-red hover:bg-nepal-crimson text-white text-xs font-bold rounded-lg shadow transition flex items-center space-x-1.5">
                <span>{{ __('+ New Application') }}</span>
            </a>
            <a href="{{ route('citizen.complaints.create') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-lg border border-white/20 transition">
                {{ __('Grievance') }}
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-medium">{{ __('Total Applications') }}</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_applications'] }}</div>
            <a href="{{ route('citizen.applications.index') }}" class="text-[11px] text-nepal-blue font-semibold mt-2 inline-block hover:underline">{{ __('View All') }} &rarr;</a>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-medium">{{ __('Approved Recommendations') }}</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['approved_certificates'] }}</div>
            <a href="{{ route('citizen.applications.index', ['status' => 'approved']) }}" class="text-[11px] text-emerald-600 font-semibold mt-2 inline-block hover:underline">{{ __('Download Certificate') }} &rarr;</a>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-medium">{{ __('Pending Reviews') }}</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $stats['pending_reviews'] }}</div>
            <a href="{{ route('citizen.applications.index', ['status' => 'under_review']) }}" class="text-[11px] text-amber-600 font-semibold mt-2 inline-block hover:underline">{{ __('View Status') }} &rarr;</a>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-medium">{{ __('Open Grievances') }}</span>
            <div class="text-2xl font-black text-nepal-crimson mt-1">{{ $stats['open_complaints'] }}</div>
            <a href="{{ route('citizen.complaints.index') }}" class="text-[11px] text-nepal-blue font-semibold mt-2 inline-block hover:underline">{{ __('View Status') }} &rarr;</a>
        </div>
    </div>

    <!-- Quick Services Grid -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-900">{{ __('Popular Online Recommendations & Services') }}</h2>
            <a href="{{ route('citizen.applications.create') }}" class="text-xs text-nepal-blue font-semibold hover:underline">{{ __('All Services List') }} &rarr;</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($featuredServices as $svc)
                <a href="{{ route('citizen.applications.create', ['service' => $svc->code]) }}" class="p-4 rounded-xl border border-slate-200 hover:border-nepal-blue hover:shadow-sm transition flex items-center justify-between group bg-slate-50 hover:bg-white">
                    <div>
                        <div class="text-sm font-bold text-slate-900 group-hover:text-nepal-blue">
                            {{ app()->getLocale() === 'ne' ? ($svc->name_ne ?? $svc->name_en) : ($svc->name_en ?? $svc->name_ne) }}
                        </div>
                        <div class="text-[11px] text-slate-500 mt-1">
                            {{ __('Fee:') }} {{ $svc->fee > 0 ? 'NPR ' . number_format($svc->fee, 0) : __('Free') }}
                        </div>
                    </div>
                    <span class="text-slate-400 group-hover:text-nepal-blue group-hover:translate-x-1 transition">&rarr;</span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Applications Table (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-slate-900">{{ __('Recent Applications') }}</h2>
                <a href="{{ route('citizen.applications.index') }}" class="text-xs text-nepal-blue font-semibold hover:underline">{{ __('View All') }}</a>
            </div>

            @if($recentApplications->isEmpty())
                <div class="text-center py-8 text-slate-400 text-xs">
                    {{ app()->getLocale() === 'ne' ? 'तपाईँले हालसम्म कुनै पनि निवेदन पेश गर्नुभएको छैन।' : 'You have not submitted any applications yet.' }}
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="p-2.5">{{ __('Application No') }}</th>
                                <th class="p-2.5">{{ __('Service') }}</th>
                                <th class="p-2.5">{{ __('Date') }}</th>
                                <th class="p-2.5">{{ __('Status') }}</th>
                                <th class="p-2.5 text-right">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentApplications as $app)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-2.5 font-mono font-bold text-slate-900">{{ $app->application_number }}</td>
                                    <td class="p-2.5 text-slate-700">
                                        {{ app()->getLocale() === 'ne' ? ($app->serviceType->name_ne ?? $app->serviceType->name_en) : ($app->serviceType->name_en ?? $app->serviceType->name_ne) }}
                                    </td>
                                    <td class="p-2.5 text-slate-500">{{ $app->created_at->format('M d, Y') }}</td>
                                    <td class="p-2.5">
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
                                    <td class="p-2.5 text-right">
                                        <a href="{{ route('citizen.applications.show', $app->id) }}" class="text-nepal-blue font-bold hover:underline">
                                            {{ __('View Status') }} &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Ward Notices Widget (1 col) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 mb-3">{{ __('Notices') }}</h2>
            @if($wardNotices->isEmpty())
                <p class="text-xs text-slate-400">{{ __('No new notices published at this moment.') }}</p>
            @else
                <div class="space-y-3">
                    @foreach($wardNotices as $notice)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs">
                            <span class="text-[10px] font-bold uppercase text-nepal-crimson block">{{ $notice->category }}</span>
                            <h3 class="font-bold text-slate-900 mt-0.5 leading-snug">{{ $notice->title }}</h3>
                            <p class="text-slate-600 mt-1 line-clamp-2">{{ $notice->content }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
