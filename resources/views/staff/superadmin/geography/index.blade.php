@extends('layouts.staff')

@section('page_title', 'प्रशासनिक भूगोल व्यवस्थापन (Districts, Palikas & Wards)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">प्रशासनिक भूगोल तथा संरचना</h1>
            <p class="text-xs text-slate-500 mt-0.5">काठमाडौँ उपत्यकाका ३ जिल्ला, २१ स्थानीय तह तथा २४७ वडाहरूको पूर्ण सूची।</p>
        </div>
        <div class="flex items-center space-x-2 text-xs">
            <span class="px-3 py-1 bg-purple-100 text-purple-800 font-bold rounded-lg">३ जिल्ला</span>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 font-bold rounded-lg">२१ स्थानीय तह</span>
            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-bold rounded-lg">२४७ वडा कार्यालय</span>
        </div>
    </div>

    <!-- Tree Structure by District -->
    <div class="space-y-8">
        @foreach($districts as $district)
            <div id="district-{{ $district->id }}" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <!-- District Header -->
                <div class="bg-slate-900 text-white p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-purple-500 text-white">
                                जिल्ला (DISTRICT)
                            </span>
                            <span class="text-xs text-slate-400 font-mono">{{ $district->code }}</span>
                        </div>
                        <h2 class="text-xl font-bold mt-1">{{ $district->name_ne }} ({{ $district->name_en }} District)</h2>
                    </div>

                    <div class="text-right text-xs text-slate-300">
                        <div>स्थानीय तह: <strong class="text-white">{{ $district->palikas->count() }}</strong> | कुल वडा: <strong class="text-white">{{ $district->palikas->sum(fn($p) => $p->wards->count()) }}</strong></div>
                        <div class="mt-0.5 text-purple-300">
                            प्रशासक: {{ $district->districtAdmin() ? $district->districtAdmin()->name : 'admin.' . strtolower($district->code) . '@wardsewa.gov.np' }}
                        </div>
                    </div>
                </div>

                <!-- Palikas Grid inside this District -->
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($district->palikas as $palika)
                            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50 hover:bg-white hover:border-nepal-blue hover:shadow-sm transition flex flex-col justify-between"
                                 x-data="{ showWards: false }">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $palika->type === 'metropolitan' ? 'bg-purple-100 text-purple-800' : ($palika->type === 'rural_municipality' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $palika->type)) }}
                                        </span>
                                        <span class="text-xs font-bold text-slate-700 font-mono">{{ $palika->wards->count() }} Wards</span>
                                    </div>

                                    <h3 class="text-base font-bold text-slate-900 leading-snug">{{ $palika->name_ne }}</h3>
                                    <p class="text-xs text-slate-500">{{ $palika->name_en }}</p>

                                    <div class="mt-2 text-xs text-slate-600">
                                        <span class="text-[11px] text-slate-400">कोड: {{ $palika->code }}</span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between">
                                    <button @click="showWards = !showWards" class="text-xs font-bold text-nepal-blue hover:underline flex items-center space-x-1">
                                        <span x-text="showWards ? 'वडाहरू लुकाउनुहोस्' : 'वडाहरू हेर्नुहोस् (' + {{ $palika->wards->count() }} + ')'"></span>
                                    </button>
                                </div>

                                <!-- Collapsible Wards List -->
                                <div x-show="showWards" x-cloak class="mt-3 pt-3 border-t border-slate-200 space-y-1.5 max-h-56 overflow-y-auto pr-1">
                                    @foreach($palika->wards as $w)
                                        <div class="p-1.5 bg-white rounded border border-slate-100 text-[11px] flex items-center justify-between">
                                            <div>
                                                <strong class="text-slate-900">वडा नं. {{ $w->ward_number }}</strong>
                                                <span class="text-slate-400 block text-[10px] truncate max-w-[140px]">{{ $w->office_address }}</span>
                                            </div>
                                            <span class="text-[10px] text-slate-400 font-mono">{{ $w->office_phone }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
