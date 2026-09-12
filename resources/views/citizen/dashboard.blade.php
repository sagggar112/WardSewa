@extends('layouts.citizen')

@section('title', 'नागरिक ड्यासबोर्ड')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-nepal-darkblue via-slate-900 to-slate-900 rounded-2xl p-6 text-white shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="text-xs text-nepal-gold font-bold uppercase tracking-wider">स्वागत छ (Namaste)</div>
            <h1 class="text-2xl font-extrabold mt-0.5">{{ $citizen->full_name }}</h1>
            <p class="text-xs text-slate-300 mt-1">
                {{ $citizen->ward->palika->name_ne ?? 'पालिका' }} - वडा नं. {{ $citizen->ward->ward_number ?? '३२' }} | मोबाइल: {{ $citizen->phone }}
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('citizen.applications.create') }}" class="px-4 py-2.5 bg-nepal-red hover:bg-nepal-crimson text-white text-xs font-bold rounded-lg shadow transition">
                + नयाँ सिफारिस लिनुहोस्
            </a>
            <a href="{{ route('citizen.complaints.create') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-lg border border-white/20 transition">
                गुनासो दर्ता
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-medium">कुल निवेदनहरू</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_applications'] }}</div>
            <a href="{{ route('citizen.applications.index') }}" class="text-[11px] text-nepal-blue font-semibold mt-2 inline-block hover:underline">सबै हेर्नुहोस् &rarr;</a>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-medium">स्वीकृत सिफारिस पत्रहरू</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['approved_certificates'] }}</div>
            <span class="text-[11px] text-slate-400 mt-2 inline-block">प्रमाणपत्र डाउनलोड उपलब्ध</span>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-medium">प्रक्रियामा रहेका (Review)</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $stats['pending_reviews'] }}</div>
            <span class="text-[11px] text-slate-400 mt-2 inline-block">वडा कार्यालयमा अध्ययन हुँदै</span>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <span class="text-xs text-slate-500 font-medium">खुला गुनासोहरू</span>
            <div class="text-2xl font-black text-nepal-crimson mt-1">{{ $stats['open_complaints'] }}</div>
            <a href="{{ route('citizen.complaints.index') }}" class="text-[11px] text-nepal-blue font-semibold mt-2 inline-block hover:underline">स्थिति हेर्नुहोस् &rarr;</a>
        </div>
    </div>

    <!-- Quick Services Grid -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-900">लोकप्रिय अनलाइन सिफारिस तथा सेवाहरू</h2>
            <a href="{{ route('citizen.applications.create') }}" class="text-xs text-nepal-blue font-semibold hover:underline">सबै सेवा सूची &rarr;</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($featuredServices as $svc)
                <a href="{{ route('citizen.applications.create', ['service' => $svc->code]) }}" class="p-4 rounded-xl border border-slate-200 hover:border-nepal-blue hover:shadow-sm transition flex items-center justify-between group bg-slate-50 hover:bg-white">
                    <div>
                        <div class="text-sm font-bold text-slate-900 group-hover:text-nepal-blue">{{ $svc->name_ne }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $svc->name_en }}</div>
                        <div class="text-[11px] text-slate-400 mt-1">दस्तुर: {{ $svc->fee > 0 ? 'रु. ' . number_format($svc->fee, 0) : 'निःशुल्क' }}</div>
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
                <h2 class="text-base font-bold text-slate-900">हालैका सिफारिस निवेदनहरू</h2>
                <a href="{{ route('citizen.applications.index') }}" class="text-xs text-nepal-blue font-semibold hover:underline">सबै हेर्नुहोस्</a>
            </div>

            @if($recentApplications->isEmpty())
                <div class="text-center py-8 text-slate-400 text-xs">
                    तपाईँले हालसम्म कुनै पनि निवेदन पेश गर्नुभएको छैन।
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="p-2.5">निवेदन नम्बर</th>
                                <th class="p-2.5">सेवा</th>
                                <th class="p-2.5">मिति</th>
                                <th class="p-2.5">स्थिति</th>
                                <th class="p-2.5 text-right">कार्य</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentApplications as $app)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-2.5 font-mono font-bold text-slate-900">{{ $app->application_number }}</td>
                                    <td class="p-2.5 text-slate-700">{{ $app->serviceType->name_ne }}</td>
                                    <td class="p-2.5 text-slate-500">{{ $app->created_at->format('M d, Y') }}</td>
                                    <td class="p-2.5">
                                        @if($app->status === 'approved')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">स्वीकृत (Approved)</span>
                                        @elseif($app->status === 'under_review')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">अध्ययन हुँदै</span>
                                        @elseif($app->status === 'documents_requested')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">कागजात मागिएको</span>
                                        @elseif($app->status === 'rejected')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">अस्वीकृत</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">दर्ता भएको</span>
                                        @endif
                                    </td>
                                    <td class="p-2.5 text-right">
                                        <a href="{{ route('citizen.applications.show', $app->id) }}" class="text-nepal-blue font-bold hover:underline">
                                            ट्रयाक / हेर्नुहोस् &rarr;
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
            <h2 class="text-base font-bold text-slate-900 mb-3">वडा सूचनाहरू</h2>
            @if($wardNotices->isEmpty())
                <p class="text-xs text-slate-400">हाल कुनै नयाँ सूचना छैन।</p>
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
