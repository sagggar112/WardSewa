@extends('layouts.app')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12"
     x-data="registerWardPicker(@js($provinces), '{{ old('province_id', 3) }}', '{{ old('district_id') }}', '{{ old('palika_id') }}', '{{ old('ward_id') }}', '{{ app()->getLocale() }}')">
    <div class="max-w-3xl w-full bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-nepal-darkblue via-nepal-blue to-nepal-darkblue text-white p-6 sm:p-8">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 bg-nepal-crimson text-white text-xs font-bold rounded-full uppercase tracking-wider shadow-sm">
                    {{ app()->getLocale() === 'ne' ? 'नागरिक नयाँ दर्ता' : 'Citizen Registration' }}
                </span>
                <span class="text-xs text-slate-300 font-medium">
                    {{ app()->getLocale() === 'ne' ? 'सम्पूर्ण नेपालभर मान्य' : 'Nationwide Service' }}
                </span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white mt-3">
                {{ app()->getLocale() === 'ne' ? 'नयाँ नागरिक खाता सिर्जना गर्नुहोस्' : 'Create New Citizen Account' }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-200 mt-1">
                {{ app()->getLocale() === 'ne' ? 'सिफारिस पत्र, घटना दर्ता र नागरिक सेवाहरू घरैबाट प्राप्त गर्न आफ्नो आधिकारिक विवरण भर्नुहोस्।' : 'Submit official details to access local government citizen services and online recommendations.' }}
            </p>
        </div>

        <form method="POST" action="{{ route('citizen.register.submit') }}" class="p-6 sm:p-8 space-y-5">
            @csrf

            <!-- Section 1: Personal Details -->
            <div>
                <h3 class="text-xs font-bold uppercase text-slate-500 tracking-wider mb-3 pb-1 border-b border-slate-100 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-nepal-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ app()->getLocale() === 'ne' ? '१. व्यक्तिगत विवरण' : '1. Personal Details' }}
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="full_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            {{ __('Full Name') }} *
                        </label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" 
                               placeholder="{{ app()->getLocale() === 'ne' ? 'उदा. राम बहादुर श्रेष्ठ' : 'e.g. Ram Bahadur Shrestha' }}" required
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none transition">
                        @error('full_name')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            {{ __('Mobile Number') }} *
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
                            {{ app()->getLocale() === 'ne' ? 'इमेल ठेगाना - ऐच्छिक' : 'Email Address - Optional' }}
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="citizen@example.com"
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none transition">
                        @error('email')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="citizenship_no" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            {{ app()->getLocale() === 'ne' ? 'नागरिकता नम्बर - ऐच्छिक' : 'Citizenship Number - Optional' }}
                        </label>
                        <input type="text" name="citizenship_no" id="citizenship_no" value="{{ old('citizenship_no') }}" placeholder="27-01-75-12345"
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none transition">
                        @error('citizenship_no')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Account Security / Password -->
            <div>
                <h3 class="text-xs font-bold uppercase text-slate-500 tracking-wider mb-3 pb-1 border-b border-slate-100 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-nepal-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    {{ app()->getLocale() === 'ne' ? '२. खाता सुरक्षा' : '2. Password Setup' }}
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            {{ __('Password') }} *
                        </label>
                        <input type="password" name="password" id="password" required minlength="6" 
                               placeholder="{{ app()->getLocale() === 'ne' ? 'कम्तिमा ६ अक्षर' : 'At least 6 characters' }}"
                               class="w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none transition">
                        @error('password')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            {{ app()->getLocale() === 'ne' ? 'पासवर्ड पुष्टि गर्नुहोस्' : 'Confirm Password' }} *
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="6" 
                               placeholder="{{ app()->getLocale() === 'ne' ? 'पासवर्ड पुनः टाइप गर्नुहोस्' : 'Re-type password' }}"
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
                        {{ app()->getLocale() === 'ne' ? '३. वडा तथा स्थान छनौट' : '3. Ward & Location Selection' }}
                    </h3>
                    <span class="text-[11px] text-slate-500 font-medium">{{ app()->getLocale() === 'ne' ? 'नेपालभरका ७५३ स्थानीय तह र वडाहरू' : 'Nationwide 753 Local Governments & Wards' }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <!-- Province -->
                    <div>
                        <label for="province_id" class="block text-xs font-semibold text-slate-700 mb-1">
                            {{ app()->getLocale() === 'ne' ? '१. प्रदेश' : '1. Province' }} *
                        </label>
                        <select id="province_id" name="province_id" x-model="selectedProvinceId" @change="onProvinceChange()"
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-nepal-blue focus:outline-none">
                            <option value="">{{ app()->getLocale() === 'ne' ? '-- प्रदेश छनौट --' : '-- Select Province --' }}</option>
                            <template x-for="prov in provinces" :key="prov.id">
                                <option :value="prov.id" x-text="locale === 'ne' ? (prov.name_ne || prov.name_en) : (prov.name_en || prov.name_ne)"></option>
                            </template>
                        </select>
                    </div>

                    <!-- District -->
                    <div>
                        <label for="district_id" class="block text-xs font-semibold text-slate-700 mb-1">
                            {{ app()->getLocale() === 'ne' ? '२. जिल्ला' : '2. District' }} *
                        </label>
                        <select id="district_id" name="district_id" x-model="selectedDistrictId" @change="onDistrictChange()" :disabled="!availableDistricts.length || loadingDistricts"
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-nepal-blue focus:outline-none disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="">{{ app()->getLocale() === 'ne' ? '-- जिल्ला छनौट --' : '-- Select District --' }}</option>
                            <template x-for="dist in availableDistricts" :key="dist.id">
                                <option :value="dist.id" x-text="locale === 'ne' ? (dist.name_ne || dist.name_en) : (dist.name_en || dist.name_ne)"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Palika -->
                    <div>
                        <label for="palika_id" class="block text-xs font-semibold text-slate-700 mb-1">
                            {{ app()->getLocale() === 'ne' ? '३. स्थानीय तह' : '3. Palika' }} *
                        </label>
                        <select id="palika_id" name="palika_id" x-model="selectedPalikaId" @change="onPalikaChange()" :disabled="!availablePalikas.length || loadingPalikas"
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-nepal-blue focus:outline-none disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="">{{ app()->getLocale() === 'ne' ? '-- स्थानीय तह छनौट --' : '-- Select Palika --' }}</option>
                            <template x-for="p in availablePalikas" :key="p.id">
                                <option :value="p.id" x-text="locale === 'ne' ? (p.name_ne || p.name_en) : (p.name_en || p.name_ne)"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Ward -->
                    <div>
                        <label for="ward_id" class="block text-xs font-semibold text-slate-700 mb-1">
                            {{ app()->getLocale() === 'ne' ? '४. वडा नं.' : '4. Ward No.' }} *
                        </label>
                        <select id="ward_id" name="ward_id" x-model="selectedWardId" @change="onWardChange()" :disabled="!availableWards.length || loadingWards" required
                                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-nepal-blue focus:outline-none disabled:bg-slate-100 disabled:text-slate-400">
                            <option value="">{{ app()->getLocale() === 'ne' ? '-- वडा छनौट --' : '-- Select Ward --' }}</option>
                            <template x-for="w in availableWards" :key="w.id">
                                <option :value="w.id" x-text="(locale === 'ne' ? 'वडा नं. ' : 'Ward ') + w.ward_number + (w.office_address ? ' - ' + w.office_address.split(',')[0] : '')"></option>
                            </template>
                        </select>
                    </div>
                </div>
                @error('ward_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror

                <!-- Active Ward Info Preview -->
                <div x-show="activeWard" x-cloak class="mt-2 bg-white p-3 rounded-lg border border-slate-200 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <span class="font-bold text-nepal-blue" x-text="(locale === 'ne' ? (activePalika?.name_ne || activePalika?.name_en) : (activePalika?.name_en || activePalika?.name_ne)) + ' - ' + (locale === 'ne' ? 'वडा नं. ' : 'Ward ') + activeWard?.ward_number"></span>
                        <p class="text-slate-600 text-[11px]" x-text="(locale === 'ne' ? 'कार्यालय: ' : 'Office: ') + (activeWard?.office_address || '')"></p>
                    </div>
                    <div class="text-slate-500 text-[11px] sm:text-right">
                        <span x-show="activeWard?.office_phone" x-text="(locale === 'ne' ? 'फोन: ' : 'Phone: ') + activeWard?.office_phone"></span>
                        <span x-show="activeWard?.office_email" class="block" x-text="(locale === 'ne' ? 'इमेल: ' : 'Email: ') + activeWard?.office_email"></span>
                    </div>
                </div>

                <!-- Tole / Street Address -->
                <div>
                    <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        {{ app()->getLocale() === 'ne' ? 'टोल / गाउँ / सडक ठेगाना' : 'Tole / Street Address' }} *
                    </label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" 
                           placeholder="{{ app()->getLocale() === 'ne' ? 'उदा. कोटेश्वर, काठमाडौँ' : 'e.g. Koteshwor, Kathmandu' }}" required
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-blue focus:outline-none transition">
                    @error('address')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3.5 px-4 bg-nepal-crimson hover:bg-nepal-red text-white font-bold rounded-xl shadow-md hover:shadow-lg transition text-sm flex items-center justify-center gap-2">
                    <span>{{ app()->getLocale() === 'ne' ? 'नागरिक खाता दर्ता गर्नुहोस्' : 'Complete Registration' }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>

            <!-- Login Link -->
            <div class="text-center pt-2">
                <p class="text-xs text-slate-600">
                    {{ app()->getLocale() === 'ne' ? 'पहिले नै खाता छ?' : 'Already have an account?' }}
                    <a href="{{ route('citizen.login') }}" class="text-nepal-blue font-bold hover:underline">
                        {{ app()->getLocale() === 'ne' ? 'यहाँ लगइन गर्नुहोस्' : 'Log In Here' }} &rarr;
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
function registerWardPicker(provincesData, initialProvId, initialDistId, initialPalikaId, initialWardId, locale) {
    return {
        provinces: provincesData,
        locale: locale || 'ne',
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
                } else if (this.availableDistricts.length > 0) {
                    const ktm = this.availableDistricts.find(d => d.code === 'KTM') || this.availableDistricts[0];
                    this.selectedDistrictId = ktm.id;
                    await this.fetchPalikas();
                    if (this.availablePalikas.length > 0) {
                        const kmc = this.availablePalikas.find(p => p.code === 'KMC') || this.availablePalikas[0];
                        this.selectedPalikaId = kmc.id;
                        await this.fetchWards();
                        if (this.availableWards.length > 0) {
                            const w32 = this.availableWards.find(w => w.ward_number === 32) || this.availableWards[0];
                            this.selectedWardId = w32.id;
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
