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

    $defaultDistrict = $districts->firstWhere('code', 'KTM');
    $defaultPalika = $defaultDistrict?->palikas->firstWhere('code', 'KMC');
    $defaultWard = $defaultPalika?->wards->firstWhere('ward_number', 32);

    $initDistId = old('district_id', $defaultDistrict?->id);
    $initPalikaId = old('palika_id', $defaultPalika?->id);
    $initWardId = old('ward_id', $defaultWard?->id);
@endphp

<div class="min-h-[75vh] flex items-center justify-center px-4 py-12"
     x-data="registerWardPicker(@js($hierarchyData), '{{ $initDistId }}', '{{ $initPalikaId }}', '{{ $initWardId }}')">
    <div class="max-w-2xl w-full bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-nepal-darkblue via-nepal-blue to-nepal-darkblue text-white p-6 sm:p-8">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 bg-nepal-crimson text-white text-xs font-bold rounded-full uppercase tracking-wider shadow-sm">
                    {{ __('नागरिक नयाँ दर्ता (Citizen Registration)') }}
                </span>
                <span class="text-xs text-slate-300">
                    काठमाडौँ, ललितपुर, भक्तपुर
                </span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white mt-3">
                {{ __('नयाँ नागरिक खाता सिर्जना गर्नुहोस्') }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-200 mt-1">
                {{ __('सिफारिस पत्र, घटना दर्ता र नागरिक सेवाहरू घरैबाट प्राप्त गर्न आफ्नो आधिकारिक विवरण भर्नुहोस्।') }}
            </p>
        </div>

        <form method="POST" action="{{ route('citizen.register.submit') }}" class="p-6 sm:p-8 space-y-5">
            @csrf

            <!-- Section 1: Personal Details -->
            <div>
                <h3 class="text-xs font-bold uppercase text-slate-500 tracking-wider mb-3 pb-1 border-b border-slate-100 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-nepal-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    १. व्यक्तिगत विवरण (Personal Details)
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="full_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            {{ __('पूरा नाम (Full Name)') }} *
                        </label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" placeholder="उदा. राम बहादुर श्रेष्ठ" required
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none transition">
                        @error('full_name')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            {{ __('मोबाइल नम्बर (Mobile Number)') }} *
                        </label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-semibold text-xs">
                                +977
                            </div>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="98XXXXXXXX" maxlength="10" required
                                   class="block w-full pl-14 pr-3 py-2.5 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-nepal-blue font-mono text-sm font-semibold transition">
                        </div>
                        @error('phone')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            {{ __('इमेल ठेगाना (Email - Optional)') }}
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="citizen@example.com"
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none transition">
                        @error('email')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="citizenship_no" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            {{ __('नागरिकता नम्बर (Citizenship No. - Optional)') }}
                        </label>
                        <input type="text" name="citizenship_no" id="citizenship_no" value="{{ old('citizenship_no') }}" placeholder="उदा. २७-०१-७५-१२३४५"
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none transition">
                        @error('citizenship_no')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Account Security / Password -->
            <div>
                <h3 class="text-xs font-bold uppercase text-slate-500 tracking-wider mb-3 pb-1 border-b border-slate-100 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-nepal-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    २. खाता सुरक्षा (Password Setup)
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            {{ __('पासवर्ड (Password)') }} *
                        </label>
                        <input type="password" name="password" id="password" required minlength="6" placeholder="कम्तिमा ६ अक्षर"
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none transition">
                        @error('password')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            {{ __('पासवर्ड पुष्टि गर्नुहोस् (Confirm Password)') }} *
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="6" placeholder="पासवर्ड पुनः टाइप गर्नुहोस्"
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Section 3: Geographic & Ward Selection -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 sm:p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                    <h3 class="text-xs font-bold uppercase text-nepal-darkblue tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-nepal-crimson" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        ३. वडा तथा स्थान छनौट (District, Palika & Ward)
                    </h3>
                    <span class="text-[11px] text-slate-500 font-medium">उपत्यकाका २४७ वडाहरू</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- District -->
                    <div>
                        <label for="district_id" class="block text-xs font-semibold text-slate-700 mb-1">
                            १. जिल्ला (District) *
                        </label>
                        <select id="district_id" name="district_id" x-model="selectedDistrictId" @change="onDistrictChange()"
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-nepal-blue focus:outline-none">
                            <option value="">-- जिल्ला छनौट --</option>
                            <template x-for="dist in districts" :key="dist.id">
                                <option :value="dist.id" x-text="dist.name_ne + ' (' + dist.name_en + ')'"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Palika -->
                    <div>
                        <label for="palika_id" class="block text-xs font-semibold text-slate-700 mb-1">
                            २. स्थानीय तह (Palika) *
                        </label>
                        <select id="palika_id" name="palika_id" x-model="selectedPalikaId" @change="onPalikaChange()" :disabled="!availablePalikas.length"
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-nepal-blue focus:outline-none disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="">-- स्थानीय तह छनौट --</option>
                            <template x-for="p in availablePalikas" :key="p.id">
                                <option :value="p.id" x-text="p.name_ne"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Ward -->
                    <div>
                        <label for="ward_id" class="block text-xs font-semibold text-slate-700 mb-1">
                            ३. वडा नं. (Ward) *
                        </label>
                        <select id="ward_id" name="ward_id" x-model="selectedWardId" @change="onWardChange()" :disabled="!availableWards.length" required
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-nepal-blue focus:outline-none disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="">-- वडा छनौट --</option>
                            <template x-for="w in availableWards" :key="w.id">
                                <option :value="w.id" x-text="'वडा नं. ' + w.number + ' (' + (w.address ? w.address.split(',')[0] : 'कार्यालय') + ')'"></option>
                            </template>
                        </select>
                    </div>
                </div>
                @error('ward_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror

                <!-- Active Ward Info Preview -->
                <div x-show="activeWard" x-cloak class="mt-2 bg-white p-3 rounded-lg border border-slate-200 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <span class="font-bold text-nepal-blue" x-text="activePalika?.name_ne + ' - वडा नं. ' + activeWard?.number"></span>
                        <p class="text-slate-600 text-[11px]" x-text="'कार्यालय: ' + (activeWard?.address || 'वडा कार्यालय')"></p>
                    </div>
                    <div class="text-slate-500 text-[11px] sm:text-right">
                        <span x-show="activeWard?.phone" x-text="'फोन: ' + activeWard?.phone"></span>
                    </div>
                </div>

                <!-- Tole / Street Address -->
                <div>
                    <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        {{ __('टोल / सडक ठेगाना (Tole / Street Address)') }} *
                    </label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" placeholder="उदा. कोटेश्वर, काठमाडौँ वा कुमारीपाटी, ललितपुर" required
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none transition">
                    @error('address')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3.5 px-4 bg-nepal-crimson hover:bg-nepal-red text-white font-bold rounded-xl shadow-md hover:shadow-lg transition text-sm flex items-center justify-center gap-2">
                    <span>{{ __('नागरिक खाता दर्ता गर्नुहोस् (Complete Registration)') }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>

            <!-- Login Link -->
            <div class="text-center pt-2">
                <p class="text-xs text-slate-600">
                    {{ __('पहिले नै खाता छ? (Already have an account?)') }}
                    <a href="{{ route('citizen.login') }}" class="text-nepal-blue font-bold hover:underline">
                        {{ __('यहाँ लगइन गर्नुहोस् (Log In Here)') }} &rarr;
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
function registerWardPicker(districtsData, initialDistId, initialPalikaId, initialWardId) {
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
                this.selectedDistrictId = this.districts[0].id;
                this.updatePalikas();
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
