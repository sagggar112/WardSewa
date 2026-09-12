@extends('layouts.staff')

@section('page_title', $district->name_ne . ' का स्थानीय तहहरू (Local Governments in District)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">{{ $district->name_ne }} का सम्पूर्ण स्थानीय तहहरू</h1>
            <p class="text-xs text-slate-500 mt-0.5">जिल्ला भित्र रहेका सबै महानगरपालिका, नगरपालिका तथा गाउँपालिकाहरूको विवरण।</p>
        </div>
        <a href="{{ route('staff.district.dashboard') }}" class="text-xs font-bold text-nepal-blue hover:underline">
            &larr; जिल्ला ड्यासबोर्डमा फर्कनुहोस्
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($palikas as $palika)
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $palika->type === 'metropolitan' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $palika->type)) }}
                        </span>
                        <span class="font-mono text-xs text-slate-500 font-bold">{{ $palika->code }}</span>
                    </div>

                    <h2 class="text-lg font-bold text-slate-900 leading-snug">{{ $palika->name_ne }}</h2>
                    <p class="text-xs text-slate-500">{{ $palika->name_en }}</p>

                    <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-2 gap-3 text-xs">
                        <div class="p-2.5 bg-slate-50 rounded-lg">
                            <span class="text-slate-400 block text-[10px]">वडा संख्या</span>
                            <strong class="text-slate-900 text-sm">{{ $palika->wards_count }} वडा</strong>
                        </div>
                        <div class="p-2.5 bg-slate-50 rounded-lg">
                            <span class="text-slate-400 block text-[10px]">कुल निवेदन</span>
                            <strong class="text-slate-900 text-sm">{{ $palika->applications_count }}</strong>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ route('staff.district.applications', ['palika_id' => $palika->id]) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-900 hover:bg-nepal-darkblue text-white rounded-lg text-xs font-bold transition">
                        यस पालिकाका निवेदन हेर्नुहोस् &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
