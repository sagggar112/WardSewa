@extends('layouts.staff')

@section('page_title', __('Geography Management'))

@section('content')
<div class="space-y-6" x-data="{ showDistrictModal: false, showPalikaModal: false, selectedProvinceFilter: '', searchQuery: '' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">{{ __('Geography Management') }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">{{ app()->getLocale() === 'ne' ? 'नेपाल सरकारको संघीय संरचना अनुसार जिल्ला, स्थानीय तह र वडा व्यवस्थापन।' : 'Nationwide administrative hierarchy covering provinces, districts, municipalities, and ward offices.' }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button @click="showDistrictModal = true" class="px-3.5 py-2 bg-purple-700 hover:bg-purple-800 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>{{ app()->getLocale() === 'ne' ? 'नयाँ जिल्ला थप्नुहोस्' : 'Add District' }}</span>
            </button>
            <button @click="showPalikaModal = true" class="px-3.5 py-2 bg-nepal-red hover:bg-red-700 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>{{ app()->getLocale() === 'ne' ? 'नयाँ स्थानीय तह थप्नुहोस्' : 'Add Local Government' }}</span>
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
            <strong class="font-bold block">{{ app()->getLocale() === 'ne' ? 'कृपया फारमका त्रुटिहरू सच्याउनुहोस्:' : 'Please correct the following errors:' }}</strong>
            <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- System Totals & Filters -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex items-center space-x-2 text-xs">
            <span class="px-3 py-1 bg-purple-100 text-purple-800 font-bold rounded-lg">{{ $districts->count() }} {{ app()->getLocale() === 'ne' ? 'जिल्ला' : 'Districts' }}</span>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 font-bold rounded-lg">{{ $districts->sum(fn($d) => $d->palikas->count()) }} {{ app()->getLocale() === 'ne' ? 'स्थानीय तह' : 'Palikas' }}</span>
            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-bold rounded-lg">{{ $districts->sum(fn($d) => $d->palikas->sum(fn($p) => $p->wards->count())) }} {{ app()->getLocale() === 'ne' ? 'वडा कार्यालय' : 'Wards' }}</span>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center space-x-2">
                <label class="text-xs font-semibold text-slate-600">{{ __('Province') }}:</label>
                <select x-model="selectedProvinceFilter" class="text-xs rounded-lg border-slate-300 focus:ring-purple-500 focus:border-purple-500 py-1.5 px-2.5 border bg-slate-50 font-medium">
                    <option value="">{{ app()->getLocale() === 'ne' ? 'सबै प्रदेश' : 'All Provinces' }}</option>
                    @foreach($provinces as $prov)
                        <option value="{{ $prov->id }}">{{ (app()->getLocale() === 'ne' ? ($prov->name_ne ?? $prov->name_en) : ($prov->name_en ?? $prov->name_ne)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-48 sm:w-56">
                <input type="text" x-model="searchQuery" placeholder="{{ app()->getLocale() === 'ne' ? 'जिल्ला खोज्नुहोस्...' : 'Search districts...' }}"
                       class="w-full text-xs rounded-lg border-slate-300 focus:ring-purple-500 focus:border-purple-500 py-1.5 px-2.5 border bg-slate-50">
            </div>
        </div>
    </div>

    <!-- Tree Structure by District -->
    <div class="space-y-8">
        @foreach($districts as $district)
            <div id="district-{{ $district->id }}"
                 x-show="(!selectedProvinceFilter || '{{ $district->province_id }}' === selectedProvinceFilter) && (!searchQuery || '{{ strtolower($district->name_en) }}'.includes(searchQuery.toLowerCase()) || '{{ $district->name_ne }}'.includes(searchQuery) || '{{ strtolower($district->code) }}'.includes(searchQuery.toLowerCase()))"
                 class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <!-- District Header -->
                <div class="bg-slate-900 text-white p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-purple-500 text-white">
                                {{ __('District') }}
                            </span>
                            <span class="text-xs text-slate-400 font-mono">{{ $district->code }}</span>
                            @if($district->province)
                                <span class="px-2 py-0.5 rounded text-[10px] bg-slate-800 text-slate-300 font-bold">
                                    {{ (app()->getLocale() === 'ne' ? ($district->province->name_ne ?? $district->province->name_en) : ($district->province->name_en ?? $district->province->name_ne)) }}
                                </span>
                            @endif
                        </div>
                        <h2 class="text-xl font-bold mt-1">
                            {{ (app()->getLocale() === 'ne' ? ($district->name_ne ?? $district->name_en) : ($district->name_en ?? $district->name_ne)) }}
                        </h2>
                    </div>

                    <div class="text-right text-xs text-slate-300">
                        <div>{{ app()->getLocale() === 'ne' ? 'स्थानीय तह:' : 'Palikas:' }} <strong class="text-white">{{ $district->palikas->count() }}</strong> | {{ app()->getLocale() === 'ne' ? 'कुल वडा:' : 'Total Wards:' }} <strong class="text-white">{{ $district->palikas->sum(fn($p) => $p->wards->count()) }}</strong></div>
                        <div class="mt-0.5 text-purple-300">
                            {{ app()->getLocale() === 'ne' ? 'प्रशासक:' : 'Admin:' }} {{ $district->districtAdmin() ? $district->districtAdmin()->name : 'admin.' . strtolower($district->code) . '@wardsewa.gov.np' }}
                        </div>
                    </div>
                </div>

                <!-- Palikas Grid inside this District -->
                <div class="p-6 space-y-6">
                    @if($district->palikas->isEmpty())
                        <div class="p-8 text-center bg-slate-50 border border-dashed border-slate-200 rounded-xl text-slate-400 text-xs">
                            {{ app()->getLocale() === 'ne' ? 'यस जिल्लामा कुनै स्थानीय तह थपिएको छैन। माथिको बटन थिची थप्न सक्नुहुन्छ।' : 'No local governments added to this district yet.' }}
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($district->palikas as $palika)
                                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50 hover:bg-white hover:border-nepal-blue hover:shadow-sm transition flex flex-col justify-between"
                                     x-data="{ showWards: false }">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $palika->type === 'metropolitan' ? 'bg-purple-100 text-purple-800' : ($palika->type === 'sub_metropolitan' ? 'bg-indigo-100 text-indigo-800' : ($palika->type === 'rural_municipality' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800')) }}">
                                                {{ app()->getLocale() === 'ne' ? ($palika->type === 'metropolitan' ? 'महानगरपालिका' : ($palika->type === 'sub_metropolitan' ? 'उपमहानगरपालिका' : ($palika->type === 'rural_municipality' ? 'गाउँपालिका' : 'नगरपालिका'))) : ucfirst(str_replace('_', ' ', $palika->type)) }}
                                            </span>
                                            <span class="text-xs font-bold text-slate-700 font-mono">{{ $palika->wards->count() }} {{ app()->getLocale() === 'ne' ? 'वडा' : 'Wards' }}</span>
                                        </div>

                                        <h3 class="text-base font-bold text-slate-900 leading-snug">
                                            {{ app()->getLocale() === 'ne' ? ($palika->name_ne ?? $palika->name_en) : ($palika->name_en ?? $palika->name_ne) }}
                                        </h3>
                                        <p class="text-xs text-slate-500">
                                            {{ app()->getLocale() === 'ne' ? $palika->name_en : $palika->name_ne }}
                                        </p>

                                        <div class="mt-2 text-xs text-slate-600 flex items-center justify-between">
                                            <span class="text-[11px] text-slate-400 font-mono">{{ app()->getLocale() === 'ne' ? 'कोड:' : 'Code:' }} {{ $palika->code }}</span>
                                            <span class="text-[10px] text-nepal-blue font-mono">admin.{{ strtolower($palika->code) }}@wardsewa</span>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between">
                                        <button @click="showWards = !showWards" class="text-xs font-bold text-nepal-blue hover:underline flex items-center space-x-1">
                                            <span x-text="showWards ? '{{ app()->getLocale() === 'ne' ? 'वडाहरू लुकाउनुहोस्' : 'Hide Wards' }}' : '{{ app()->getLocale() === 'ne' ? 'वडाहरू हेर्नुहोस्' : 'View Wards' }} (' + {{ $palika->wards->count() }} + ')'"></span>
                                        </button>
                                    </div>

                                    <!-- Collapsible Wards List -->
                                    <div x-show="showWards" x-cloak class="mt-3 pt-3 border-t border-slate-200 space-y-1.5 max-h-56 overflow-y-auto pr-1">
                                        @forelse($palika->wards as $w)
                                            <div class="p-2 bg-white rounded border border-slate-100 text-[11px] flex items-center justify-between">
                                                <div>
                                                    <div class="flex items-center space-x-1.5">
                                                        <strong class="text-slate-900">{{ __('Ward No.') }} {{ $w->ward_number }}</strong>
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500" title="Active"></span>
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
                                                {{ app()->getLocale() === 'ne' ? 'कुनै वडा दर्ता भएको छैन।' : 'No wards registered yet.' }}
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
                <h3 class="text-lg font-black text-slate-900">{{ app()->getLocale() === 'ne' ? 'नयाँ जिल्ला दर्ता' : 'Add New District' }}</h3>
                <button @click="showDistrictModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form action="{{ route('staff.superadmin.districts.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('Province') }} *</label>
                    <select name="province_id" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                        @foreach($provinces as $prov)
                            <option value="{{ $prov->id }}">{{ (app()->getLocale() === 'ne' ? ($prov->name_ne ?? $prov->name_en) : ($prov->name_en ?? $prov->name_ne)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ app()->getLocale() === 'ne' ? 'जिल्लाको नाम - नेपाली' : 'District Name - Nepali' }} *</label>
                        <input type="text" name="name_ne" placeholder="{{ app()->getLocale() === 'ne' ? 'उदा: कास्की' : 'e.g. कास्की' }}" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ app()->getLocale() === 'ne' ? 'जिल्लाको नाम - अंग्रेजी' : 'District Name - English' }} *</label>
                        <input type="text" name="name_en" placeholder="e.g. Kaski" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ app()->getLocale() === 'ne' ? 'जिल्ला कोड' : 'District Code' }} *</label>
                    <input type="text" name="code" placeholder="KAS" required maxlength="10" class="w-full text-xs uppercase font-mono rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ app()->getLocale() === 'ne' ? 'अद्वितिय ३-४ अक्षरको कोड' : 'Unique 3-4 letter code' }}</p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" @click="showDistrictModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                        {{ app()->getLocale() === 'ne' ? 'रद्द गर्नुहोस्' : 'Cancel' }}
                    </button>
                    <button type="submit" class="px-5 py-2 bg-purple-700 hover:bg-purple-800 text-white rounded-xl text-xs font-bold transition">
                        {{ app()->getLocale() === 'ne' ? 'जिल्ला थप्नुहोस्' : 'Add District' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Add Palika (Local Government) -->
    <div x-show="showPalikaModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showPalikaModal = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-900">{{ app()->getLocale() === 'ne' ? 'नयाँ स्थानीय तह दर्ता' : 'Add New Local Government' }}</h3>
                <button @click="showPalikaModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form action="{{ route('staff.superadmin.palikas.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('District') }} *</label>
                        <select name="district_id" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                            @foreach($districts as $d)
                                <option value="{{ $d->id }}">{{ (app()->getLocale() === 'ne' ? ($d->name_ne ?? $d->name_en) : ($d->name_en ?? $d->name_ne)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ app()->getLocale() === 'ne' ? 'तहको प्रकार' : 'Government Type' }} *</label>
                        <select name="type" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                            <option value="metropolitan">{{ app()->getLocale() === 'ne' ? 'महानगरपालिका' : 'Metropolitan City' }}</option>
                            <option value="sub_metropolitan">{{ app()->getLocale() === 'ne' ? 'उपमहानगरपालिका' : 'Sub-Metropolitan City' }}</option>
                            <option value="municipality" selected>{{ app()->getLocale() === 'ne' ? 'नगरपालिका' : 'Municipality' }}</option>
                            <option value="rural_municipality">{{ app()->getLocale() === 'ne' ? 'गाउँपालिका' : 'Rural Municipality' }}</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ app()->getLocale() === 'ne' ? 'पालिकाको नाम - नेपाली' : 'Palika Name - Nepali' }} *</label>
                        <input type="text" name="name_ne" placeholder="{{ app()->getLocale() === 'ne' ? 'उदा: पोखरा महानगरपालिका' : 'e.g. पोखरा महानगरपालिका' }}" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ app()->getLocale() === 'ne' ? 'पालिकाको नाम - अंग्रेजी' : 'Palika Name - English' }} *</label>
                        <input type="text" name="name_en" placeholder="e.g. Pokhara Metropolitan City" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ app()->getLocale() === 'ne' ? 'पालिका कोड' : 'Palika Code' }} *</label>
                    <input type="text" name="code" placeholder="POK" required maxlength="10" class="w-full text-xs uppercase font-mono rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    <p class="text-[10px] text-slate-400 mt-0.5">
                        {{ app()->getLocale() === 'ne' ? 'स्वचालित प्रशासक खाता' : 'Automatic administrator account' }} <code>admin.&lt;code&gt;@wardsewa.gov.np</code> {{ app()->getLocale() === 'ne' ? 'सिर्जना हुनेछ (पासवर्ड: password123)' : 'will be created (password: password123)' }}
                    </p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" @click="showPalikaModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                        {{ app()->getLocale() === 'ne' ? 'रद्द गर्नुहोस्' : 'Cancel' }}
                    </button>
                    <button type="submit" class="px-5 py-2 bg-nepal-red hover:bg-red-700 text-white rounded-xl text-xs font-bold transition">
                        {{ app()->getLocale() === 'ne' ? 'पालिका थप्नुहोस्' : 'Add Palika' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
