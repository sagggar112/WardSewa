@extends('layouts.staff')

@section('page_title', 'कर्मचारी कार्यसम्पादन ड्यासबोर्ड')

@section('content')
<div class="space-y-6">
    <!-- Header Welcome -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-black uppercase bg-nepal-blue text-white">
                    {{ str_replace('_', ' ', $staff->role) }}
                </span>
                <span class="text-xs text-slate-500">
                    {{ $staff->ward ? ($staff->ward->palika->name_ne . ' - वडा नं. ' . $staff->ward->ward_number) : 'काठमाडौँ महानगरपालिका' }}
                </span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 mt-2">{{ $staff->name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">नागरिक सिफारिस तथा उजुरी समीक्षा कार्यक्षेत्रमा स्वागत छ।</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('staff.applications.index') }}" class="px-4 py-2.5 bg-nepal-darkblue hover:bg-nepal-blue text-white text-xs font-bold rounded-xl shadow transition">
                सबै निवेदन हेर्नुहोस् &rarr;
            </a>
        </div>
    </div>

    <!-- Counters Grid -->
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

    <!-- Recent Submissions Table -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-900">हालै दर्ता भएका निवेदनहरू (Recent Submissions)</h2>
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
                                        समीक्षा गर्नुहोस् &rarr;
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
