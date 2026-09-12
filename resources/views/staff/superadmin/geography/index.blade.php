@extends('layouts.staff')

@section('page_title', 'प्रशासनिक भूगोल व्यवस्थापन (Districts, Palikas & Wards)')

@section('content')
<div class="space-y-6" x-data="{ showDistrictModal: false, showPalikaModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">प्रशासनिक भूगोल तथा संरचना</h1>
            <p class="text-xs text-slate-500 mt-0.5">नेपाल सरकारको संघीय संरचना अनुसार जिल्ला, स्थानीय तह (महानगर/उपमहानगर/नगर/गाउँपालिका) र वडा व्यवस्थापन।</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button @click="showDistrictModal = true" class="px-3.5 py-2 bg-purple-700 hover:bg-purple-800 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>नयाँ जिल्ला थप्नुहोस्</span>
            </button>
            <button @click="showPalikaModal = true" class="px-3.5 py-2 bg-nepal-red hover:bg-red-700 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>नयाँ स्थानीय तह थप्नुहोस्</span>
            </button>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
            <strong class="font-bold block">कृपया फारमका त्रुटिहरू सच्याउनुहोस्:</strong>
            <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- System Totals -->
    <div class="flex items-center space-x-2 text-xs">
        <span class="px-3 py-1 bg-purple-100 text-purple-800 font-bold rounded-lg">{{ $districts->count() }} जिल्ला</span>
        <span class="px-3 py-1 bg-blue-100 text-blue-800 font-bold rounded-lg">{{ $districts->sum(fn($d) => $d->palikas->count()) }} स्थानीय तह</span>
        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-bold rounded-lg">{{ $districts->sum(fn($d) => $d->palikas->sum(fn($p) => $p->wards->count())) }} वडा कार्यालय</span>
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
                            @if($district->province)
                                <span class="px-2 py-0.5 rounded text-[10px] bg-slate-800 text-slate-300 font-bold">
                                    {{ $district->province->name_ne }}
                                </span>
                            @endif
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
                    @if($district->palikas->isEmpty())
                        <div class="p-8 text-center bg-slate-50 border border-dashed border-slate-200 rounded-xl text-slate-400 text-xs">
                            यस जिल्लामा कुनै स्थानीय तह थपिएको छैन। माथिको <strong>"नयाँ स्थानीय तह थप्नुहोस्"</strong> बटन थिची थप्न सक्नुहुन्छ।
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($district->palikas as $palika)
                                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50 hover:bg-white hover:border-nepal-blue hover:shadow-sm transition flex flex-col justify-between"
                                     x-data="{ showWards: false }">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $palika->type === 'metropolitan' ? 'bg-purple-100 text-purple-800' : ($palika->type === 'sub_metropolitan' ? 'bg-indigo-100 text-indigo-800' : ($palika->type === 'rural_municipality' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $palika->type)) }}
                                            </span>
                                            <span class="text-xs font-bold text-slate-700 font-mono">{{ $palika->wards->count() }} Wards</span>
                                        </div>

                                        <h3 class="text-base font-bold text-slate-900 leading-snug">{{ $palika->name_ne }}</h3>
                                        <p class="text-xs text-slate-500">{{ $palika->name_en }}</p>

                                        <div class="mt-2 text-xs text-slate-600 flex items-center justify-between">
                                            <span class="text-[11px] text-slate-400 font-mono">कोड: {{ $palika->code }}</span>
                                            <span class="text-[10px] text-nepal-blue font-mono">admin.{{ strtolower($palika->code) }}@wardsewa</span>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between">
                                        <button @click="showWards = !showWards" class="text-xs font-bold text-nepal-blue hover:underline flex items-center space-x-1">
                                            <span x-text="showWards ? 'वडाहरू लुकाउनुहोस्' : 'वडाहरू हेर्नुहोस् (' + {{ $palika->wards->count() }} + ')'"></span>
                                        </button>
                                    </div>

                                    <!-- Collapsible Wards List -->
                                    <div x-show="showWards" x-cloak class="mt-3 pt-3 border-t border-slate-200 space-y-1.5 max-h-56 overflow-y-auto pr-1">
                                        @forelse($palika->wards as $w)
                                            <div class="p-2 bg-white rounded border border-slate-100 text-[11px] flex items-center justify-between">
                                                <div>
                                                    <div class="flex items-center space-x-1.5">
                                                        <strong class="text-slate-900">वडा नं. {{ $w->ward_number }}</strong>
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500" title="सक्रिय कार्यालय"></span>
                                                    </div>
                                                    <span class="text-slate-400 block text-[10px] truncate max-w-[140px]">{{ $w->office_address }}</span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-[10px] text-slate-500 font-mono block">{{ $w->office_phone }}</span>
                                                    <span class="text-[9px] text-nepal-blue font-mono">chair.{{ strtolower($palika->code) }}{{ $w->ward_number }}@wardsewa</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="p-2 text-center text-slate-400 text-[10px]">
                                                कुनै वडा दर्ता भएको छैन। यस पालिकाका प्रशासकले वडा थप्न सक्नुहुन्छ।
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal 1: Add District -->
    <div x-show="showDistrictModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showDistrictModal = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-900">नयाँ जिल्ला दर्ता (Add District)</h3>
                <button @click="showDistrictModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form action="{{ route('staff.superadmin.districts.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">प्रदेश (Province) *</label>
                    <select name="province_id" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                        @foreach($provinces as $prov)
                            <option value="{{ $prov->id }}">{{ $prov->name_ne }} ({{ $prov->name_en }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">जिल्लाको नाम (Nepali) *</label>
                        <input type="text" name="name_ne" placeholder="उदा: कास्की" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">District Name (English) *</label>
                        <input type="text" name="name_en" placeholder="e.g. Kaski" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">जिल्ला कोड (District Code) *</label>
                    <input type="text" name="code" placeholder="उदा: KAS" required maxlength="10" class="w-full text-xs uppercase font-mono rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    <p class="text-[10px] text-slate-400 mt-0.5">अद्वितिय ३-४ अक्षरको कोड (Unique 3-4 letter code)</p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" @click="showDistrictModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition">रद्द गर्नुहोस्</button>
                    <button type="submit" class="px-5 py-2 bg-purple-700 hover:bg-purple-800 text-white rounded-xl text-xs font-bold transition">जिल्ला थप्नुहोस्</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Add Palika (Local Government) -->
    <div x-show="showPalikaModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showPalikaModal = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-900">नयाँ स्थानीय तह दर्ता (Add Local Government)</h3>
                <button @click="showPalikaModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form action="{{ route('staff.superadmin.palikas.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">जिल्ला (District) *</label>
                        <select name="district_id" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                            @foreach($districts as $d)
                                <option value="{{ $d->id }}">{{ $d->name_ne }} ({{ $d->name_en }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">तहको प्रकार (Type) *</label>
                        <select name="type" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                            <option value="metropolitan">महानगरपालिका (Metropolitan)</option>
                            <option value="sub_metropolitan">उपमहानगरपालिका (Sub-Metro)</option>
                            <option value="municipality" selected>नगरपालिका (Municipality)</option>
                            <option value="rural_municipality">गाउँपालिका (Rural Municipality)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">पालिकाको नाम (Nepali) *</label>
                        <input type="text" name="name_ne" placeholder="उदा: पोखरा महानगरपालिका" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Palika Name (English) *</label>
                        <input type="text" name="name_en" placeholder="e.g. Pokhara Metropolitan City" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">पालिका कोड (Palika Code) *</label>
                    <input type="text" name="code" placeholder="उदा: POK, BRT, HET" required maxlength="10" class="w-full text-xs uppercase font-mono rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    <p class="text-[10px] text-slate-400 mt-0.5">स्वचालित प्रशासक खाता <code>admin.&lt;code&gt;@wardsewa.gov.np</code> सिर्जना हुनेछ (पासवर्ड: <code>password123</code>)</p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" @click="showPalikaModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition">रद्द गर्नुहोस्</button>
                    <button type="submit" class="px-5 py-2 bg-nepal-red hover:bg-red-700 text-white rounded-xl text-xs font-bold transition">पालिका थप्नुहोस्</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
