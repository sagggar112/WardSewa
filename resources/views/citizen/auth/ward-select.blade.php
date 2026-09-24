@extends('layouts.app')

@section('content')
@php
    $defaultWardId = old('ward_id', $citizen->ward_id);
    $defaultPalikaId = old('palika_id', $citizen->ward?->palika_id);
    $defaultDistrictId = old('district_id', $citizen->ward?->palika?->district_id);
    $defaultProvinceId = old('province_id', $citizen->ward?->palika?->district?->province_id ?? 3);
@endphp

<div class="min-h-[75vh] flex items-center justify-center px-4 py-10"
     x-data="wardPicker(@js($provinces), '{{ $defaultProvinceId }}', '{{ $defaultDistrictId }}', '{{ $defaultPalikaId }}', '{{ $defaultWardId }}')">
    <div class="max-w-3xl w-full bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-nepal-darkblue via-nepal-blue to-nepal-darkblue text-white p-6 sm:p-8">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 bg-nepal-crimson text-white text-xs font-bold rounded-full uppercase tracking-wider shadow-sm">
                    {{ __('वडा तथा स्थान छनौट (Ward Profile Setup)') }}
                </span>
                <span class="text-xs text-slate-300">
                    {{ __('नेपालभरका ७५३ स्थानीय तह') }}
                </span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white mt-3">
                {{ __('आफ्नो स्थानीय तह र वडा चयन गर्नुहोस्') }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-200 mt-1">
                {{ __('तपाईँका सबै सिफारिस पत्र, घटना दर्ता र नागरिक सेवाहरू सिधै सम्बन्धित वडा कार्यालयमा पठाइनेछ।') }}
            </p>
        </div>

        <form method="POST" action="{{ route('citizen.ward-select.save') }}" class="p-6 sm:p-8 space-y-5">
            @csrf

            <!-- Personal Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="full_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        {{ __('Full Name (पूरा नाम)') }} *
                    </label>
                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $citizen->full_name) }}" required
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:border-nepal-blue focus:outline-none transition">
                    @error('full_name')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="citizenship_no" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        {{ __('Citizenship No. (नागरिकता नं.)') }}
                    </label>
                    <input type="text" name="citizenship_no" id="citizenship_no" value="{{ old('citizenship_no', $citizen->citizenship_no) }}" placeholder="उदा. २७-०१-७५-१२३४५"
                           class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:border-nepal-blue focus:outline-none transition">
                    @error('citizenship_no')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Geographic Cascading Selector -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 sm:p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                    <h3 class="text-xs font-bold uppercase text-nepal-darkblue tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-nepal-crimson" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        स्थान विवरण (Province, District, Palika & Ward Selection)
                    </h3>
                    <span class="text-[11px] text-slate-500 font-medium">नेपालभरका ६,७००+ वडाहरू</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                    <!-- 1. Province Selector -->
                    <div>
                        <label for="province_select" class="block text-xs font-semibold text-slate-700 mb-1">
                            १. प्रदेश (Province) *
                        </label>
                        <select id="province_select" x-model="selectedProvinceId" @change="onProvinceChange()"
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm font-medium focus:ring-2 focus:ring-nepal-blue focus:outline-none">
                            <option value="">-- प्रदेश छनौट --</option>
                            <template x-for="prov in provinces" :key="prov.id">
                                <option :value="prov.id" x-text="prov.name_ne + ' (' + prov.name_en + ')'"></option>
                            </template>
                        </select>
                    </div>

                    <!-- 2. District Selector -->
                    <div>
                        <label for="district_select" class="block text-xs font-semibold text-slate-700 mb-1">
                            २. जिल्ला (District) *
                        </label>
                        <select id="district_select" x-model="selectedDistrictId" @change="onDistrictChange()" :disabled="!availableDistricts.length || loadingDistricts"
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm font-medium focus:ring-2 focus:ring-nepal-blue focus:outline-none disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="">-- जिल्ला छनौट --</option>
                            <template x-for="dist in availableDistricts" :key="dist.id">
                                <option :value="dist.id" x-text="dist.name_ne + ' (' + dist.name_en + ')'"></option>
                            </template>
                        </select>
                    </div>

                    <!-- 3. Palika Selector -->
                    <div>
                        <label for="palika_select" class="block text-xs font-semibold text-slate-700 mb-1">
                            ३. स्थानीय तह (Palika) *
                        </label>
                        <select id="palika_select" x-model="selectedPalikaId" @change="onPalikaChange()" :disabled="!availablePalikas.length || loadingPalikas"
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm font-medium focus:ring-2 focus:ring-nepal-blue focus:outline-none disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="">-- स्थानीय तह छनौट --</option>
                            <template x-for="p in availablePalikas" :key="p.id">
                                <option :value="p.id" x-text="p.name_ne"></option>
                            </template>
                        </select>
                    </div>

                    <!-- 4. Ward Selector -->
                    <div>
                        <label for="ward_select" class="block text-xs font-semibold text-slate-700 mb-1">
                            ४. वडा नं. (Ward) *
                        </label>
                        <select id="ward_select" x-model="selectedWardId" @change="onWardChange()" :disabled="!availableWards.length || loadingWards"
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm font-medium focus:ring-2 focus:ring-nepal-blue focus:outline-none disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="">-- वडा छनौट --</option>
                            <template x-for="w in availableWards" :key="w.id">
                                <option :value="w.id" x-text="'वडा नं. ' + w.ward_number + (w.office_address ? ' (' + w.office_address.split(',')[0] + ')' : '')"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <!-- Hidden Input Bound to Active Ward ID for Form Submission -->
                <input type="hidden" name="ward_id" :value="selectedWardId" required>
                @error('ward_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror

                <!-- Ward Office Information Card Preview -->
                <div x-show="activeWard" x-cloak class="mt-3 bg-white p-3.5 rounded-lg border border-slate-200 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2 shadow-sm">
                    <div>
                        <span class="font-bold text-nepal-blue flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-nepal-crimson" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span x-text="activePalika?.name_ne + ' - वडा नं. ' + activeWard?.ward_number"></span>
                        </span>
                        <p class="text-slate-600 mt-0.5" x-text="'कार्यालय: ' + (activeWard?.office_address || 'वडा कार्यालय')"></p>
                    </div>
                    <div class="text-slate-500 text-[11px] sm:text-right space-y-0.5">
                        <p x-show="activeWard?.office_phone" x-text="'फोन: ' + activeWard?.office_phone"></p>
                        <p x-show="activeWard?.office_email" x-text="'इमेल: ' + activeWard?.office_email"></p>
                    </div>
                </div>
            </div>

            <!-- Tole & Street Address -->
            <div>
                <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    {{ __('Tole / Street Address (टोल वा सडक ठेगाना)') }} *
                </label>
                <input type="text" name="address" id="address" value="{{ old('address', $citizen->address) }}" placeholder="उदा. कोटेश्वर, काठमाडौँ वा महेन्द्रनगर, कञ्चनपुर" required
                       class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:border-nepal-blue focus:outline-none transition">
                @error('address')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-3">
                <button type="submit" :disabled="!selectedWardId"
                        class="w-full py-3.5 px-4 bg-nepal-crimson hover:bg-nepal-red text-white font-bold rounded-xl shadow-md hover:shadow-lg transition text-sm flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span>वडा सुरक्षित गरी सेवा ड्यासबोर्डमा जानुहोस्</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function wardPicker(provincesData, initialProvId, initialDistId, initialPalikaId, initialWardId) {
    return {
        provinces: provincesData,
        selectedProvinceId: initialProvId || '',
        selectedDistrictId: initialDistId || '',
        selectedPalikaId: initialPalikaId || '',
        selectedWardId: initialWardId || '',
        availableDistricts: [],
        availablePalikas: [],
        availableWards: [],
        activePalika: null,
        activeWard: null,
        loadingDistricts: false,
        loadingPalikas: false,
        loadingWards: false,

        async init() {
            if (this.selectedProvinceId) {
                await this.fetchDistricts();
                if (this.selectedDistrictId) {
                    await this.fetchPalikas();
                    if (this.selectedPalikaId) {
                        await this.fetchWards();
                        if (this.selectedWardId) {
                            this.updateActiveWard();
                        }
                    }
                }
            }
        },

        async onProvinceChange() {
            this.selectedDistrictId = '';
            this.selectedPalikaId = '';
            this.selectedWardId = '';
            this.availableDistricts = [];
            this.availablePalikas = [];
            this.availableWards = [];
            this.activePalika = null;
            this.activeWard = null;

            if (this.selectedProvinceId) {
                await this.fetchDistricts();
            }
        },

        async onDistrictChange() {
            this.selectedPalikaId = '';
            this.selectedWardId = '';
            this.availablePalikas = [];
            this.availableWards = [];
            this.activePalika = null;
            this.activeWard = null;

            if (this.selectedDistrictId) {
                await this.fetchPalikas();
            }
        },

        async onPalikaChange() {
            this.selectedWardId = '';
            this.availableWards = [];
            this.activeWard = null;
            this.activePalika = this.availablePalikas.find(p => String(p.id) === String(this.selectedPalikaId)) || null;

            if (this.selectedPalikaId) {
                await this.fetchWards();
            }
        },

        onWardChange() {
            this.updateActiveWard();
        },

        async fetchDistricts() {
            this.loadingDistricts = true;
            try {
                const res = await fetch('/api/geography/districts/' + this.selectedProvinceId);
                this.availableDistricts = await res.json();
            } catch (e) {
                console.error('Error fetching districts:', e);
            } finally {
                this.loadingDistricts = false;
            }
        },

        async fetchPalikas() {
            this.loadingPalikas = true;
            try {
                const res = await fetch('/api/geography/palikas/' + this.selectedDistrictId);
                this.availablePalikas = await res.json();
                this.activePalika = this.availablePalikas.find(p => String(p.id) === String(this.selectedPalikaId)) || null;
            } catch (e) {
                console.error('Error fetching palikas:', e);
            } finally {
                this.loadingPalikas = false;
            }
        },

        async fetchWards() {
            this.loadingWards = true;
            try {
                const res = await fetch('/api/geography/wards/' + this.selectedPalikaId);
                this.availableWards = await res.json();
                this.updateActiveWard();
            } catch (e) {
                console.error('Error fetching wards:', e);
            } finally {
                this.loadingWards = false;
            }
        },

        updateActiveWard() {
            this.activeWard = this.availableWards.find(w => String(w.id) === String(this.selectedWardId)) || null;
        }
    };
}
</script>
@endsection
