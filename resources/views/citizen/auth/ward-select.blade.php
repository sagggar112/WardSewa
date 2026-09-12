@extends('layouts.app')

@section('content')
@php
    $hierarchyData = $districts->map(function($d) {
        return [
            'id' => $d->id,
            'code' => $d->code,
            'name_en' => $d->name_en,
            'name_ne' => $d->name_ne,
            'palikas' => $d->palikas->map(function($p) {
                return [
                    'id' => $p->id,
                    'code' => $p->code,
                    'type' => $p->type,
                    'name_en' => $p->name_en,
                    'name_ne' => $p->name_ne,
                    'wards' => $p->wards->map(function($w) {
                        return [
                            'id' => $w->id,
                            'number' => $w->ward_number,
                            'address' => $w->office_address,
                            'phone' => $w->office_phone,
                            'email' => $w->office_email,
                        ];
                    })->values(),
                ];
            })->values(),
        ];
    })->values();

    $defaultWardId = old('ward_id', $citizen->ward_id);
    $defaultDistrictId = null;
    $defaultPalikaId = null;

    if ($citizen->ward) {
        $defaultWardId = $citizen->ward->id;
        $defaultPalikaId = $citizen->ward->palika_id;
        $defaultDistrictId = $citizen->ward->palika?->district_id;
    } else {
        // Default to Kathmandu District (KTM) & Kathmandu Metro (KMC) Ward 32
        $ktmDist = $districts->firstWhere('code', 'KTM');
        if ($ktmDist) {
            $defaultDistrictId = $ktmDist->id;
            $kmcPalika = $ktmDist->palikas->firstWhere('code', 'KMC');
            if ($kmcPalika) {
                $defaultPalikaId = $kmcPalika->id;
                $w32 = $kmcPalika->wards->firstWhere('ward_number', 32);
                if ($w32 && !$defaultWardId) {
                    $defaultWardId = $w32->id;
                }
            }
        }
    }
@endphp

<div class="min-h-[75vh] flex items-center justify-center px-4 py-10"
     x-data="wardPicker(@js($hierarchyData), '{{ $defaultDistrictId }}', '{{ $defaultPalikaId }}', '{{ $defaultWardId }}')">
    <div class="max-w-2xl w-full bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-nepal-darkblue via-nepal-blue to-nepal-darkblue text-white p-6 sm:p-8">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 bg-nepal-crimson text-white text-xs font-bold rounded-full uppercase tracking-wider shadow-sm">
                    {{ __('Step 2: Ward Profile Setup') }}
                </span>
                <span class="text-xs text-slate-300">
                    काठमाडौँ उपत्यका (३ जिल्ला, २१ पालिका)
                </span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white mt-3">
                {{ __('Select Your Palika & Ward') }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-200 mt-1">
                {{ __('Your applications, recommendations, and public service requests will be routed directly to your chosen Ward Office.') }}
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
                        स्थान विवरण (District, Palika & Ward Selection)
                    </h3>
                    <span class="text-[11px] text-slate-500 font-medium">उपत्यकाका २४७ वडाहरू समावेश</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                    <!-- 1. District Selector -->
                    <div>
                        <label for="district_select" class="block text-xs font-semibold text-slate-700 mb-1">
                            १. जिल्ला (District) *
                        </label>
                        <select id="district_select" x-model="selectedDistrictId" @change="onDistrictChange()"
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm font-medium focus:ring-2 focus:ring-nepal-blue focus:outline-none">
                            <option value="">-- जिल्ला छनौट --</option>
                            <template x-for="dist in districts" :key="dist.id">
                                <option :value="dist.id" x-text="dist.name_ne + ' (' + dist.name_en + ')'"></option>
                            </template>
                        </select>
                    </div>

                    <!-- 2. Palika Selector -->
                    <div>
                        <label for="palika_select" class="block text-xs font-semibold text-slate-700 mb-1">
                            २. स्थानीय तह (Palika) *
                        </label>
                        <select id="palika_select" x-model="selectedPalikaId" @change="onPalikaChange()" :disabled="!availablePalikas.length"
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm font-medium focus:ring-2 focus:ring-nepal-blue focus:outline-none disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="">-- स्थानीय तह छनौट --</option>
                            <template x-for="p in availablePalikas" :key="p.id">
                                <option :value="p.id" x-text="p.name_ne"></option>
                            </template>
                        </select>
                    </div>

                    <!-- 3. Ward Selector -->
                    <div>
                        <label for="ward_select" class="block text-xs font-semibold text-slate-700 mb-1">
                            ३. वडा नं. (Ward) *
                        </label>
                        <select id="ward_select" x-model="selectedWardId" @change="onWardChange()" :disabled="!availableWards.length"
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-xs sm:text-sm font-medium focus:ring-2 focus:ring-nepal-blue focus:outline-none disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="">-- वडा छनौट --</option>
                            <template x-for="w in availableWards" :key="w.id">
                                <option :value="w.id" x-text="'वडा नं. ' + w.number + ' (' + (w.address ? w.address.split(',')[0] : 'कार्यालय') + ')'"></option>
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
                            <span x-text="activePalika?.name_ne + ' - वडा नं. ' + activeWard?.number"></span>
                        </span>
                        <p class="text-slate-600 mt-0.5" x-text="'कार्यालय: ' + (activeWard?.address || 'वडा कार्यालय')"></p>
                    </div>
                    <div class="text-slate-500 text-[11px] sm:text-right space-y-0.5">
                        <p x-show="activeWard?.phone" x-text="'फोन: ' + activeWard?.phone"></p>
                        <p x-show="activeWard?.email" x-text="'इमेल: ' + activeWard?.email"></p>
                    </div>
                </div>
            </div>

            <!-- Tole & Street Address -->
            <div>
                <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    {{ __('Tole / Street Address (टोल वा सडक ठेगाना)') }} *
                </label>
                <input type="text" name="address" id="address" value="{{ old('address', $citizen->address) }}" placeholder="उदा. कोटेश्वर, काठमाडौँ" required
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
function wardPicker(districtsData, initialDistId, initialPalikaId, initialWardId) {
    return {
        districts: districtsData,
        selectedDistrictId: initialDistId || '',
        selectedPalikaId: initialPalikaId || '',
        selectedWardId: initialWardId || '',
        availablePalikas: [],
        availableWards: [],
        activePalika: null,
        activeWard: null,

        init() {
            if (this.selectedDistrictId) {
                this.updatePalikas();
                if (this.selectedPalikaId) {
                    this.updateWards();
                    if (this.selectedWardId) {
                        this.updateActiveWard();
                    }
                }
            } else if (this.districts.length > 0) {
                // Default to first district (Kathmandu)
                this.selectedDistrictId = this.districts[0].id;
                this.updatePalikas();
                if (this.availablePalikas.length > 0) {
                    this.selectedPalikaId = this.availablePalikas[0].id;
                    this.updateWards();
                }
            }
        },

        onDistrictChange() {
            this.selectedPalikaId = '';
            this.selectedWardId = '';
            this.activePalika = null;
            this.activeWard = null;
            this.updatePalikas();
        },

        onPalikaChange() {
            this.selectedWardId = '';
            this.activeWard = null;
            this.updateWards();
        },

        onWardChange() {
            this.updateActiveWard();
        },

        updatePalikas() {
            const dist = this.districts.find(d => String(d.id) === String(this.selectedDistrictId));
            this.availablePalikas = dist ? dist.palikas : [];
            this.availableWards = [];
            if (this.availablePalikas.length > 0 && !this.selectedPalikaId) {
                this.selectedPalikaId = this.availablePalikas[0].id;
                this.updateWards();
            }
        },

        updateWards() {
            const palika = this.availablePalikas.find(p => String(p.id) === String(this.selectedPalikaId));
            this.activePalika = palika || null;
            this.availableWards = palika ? palika.wards : [];
            if (this.availableWards.length > 0 && !this.selectedWardId) {
                this.selectedWardId = this.availableWards[0].id;
                this.updateActiveWard();
            }
        },

        updateActiveWard() {
            const ward = this.availableWards.find(w => String(w.id) === String(this.selectedWardId));
            this.activeWard = ward || null;
        }
    };
}
</script>
@endsection
