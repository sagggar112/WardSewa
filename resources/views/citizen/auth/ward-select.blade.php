@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="max-w-xl w-full bg-white rounded-2xl border border-slate-200 shadow-lg p-8">
        <div class="mb-6">
            <span class="text-xs font-bold text-nepal-crimson uppercase tracking-wider">चरण २: वडा प्रोफाइल सेटअप</span>
            <h2 class="text-2xl font-bold text-slate-900 mt-1">आफ्नो स्थायी / बसोबास वडा चयन गर्नुहोस्</h2>
            <p class="text-xs text-slate-500 mt-1">तपाईँले पेश गर्ने सिफारिस तथा सेवा निवेदनहरू सम्बन्धित वडा कार्यालयमा प्रेषित हुनेछन्।</p>
        </div>

        <form method="POST" action="{{ route('citizen.ward-select.save') }}" class="space-y-4">
            @csrf

            <div>
                <label for="full_name" class="block text-xs font-bold text-slate-700 uppercase mb-1">पूरा नाम (Full Name) *</label>
                <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $citizen->full_name) }}" required
                       class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-crimson focus:outline-none">
                @error('full_name')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="citizenship_no" class="block text-xs font-bold text-slate-700 uppercase mb-1">नागरिकता नम्बर (Citizenship No.)</label>
                <input type="text" name="citizenship_no" id="citizenship_no" value="{{ old('citizenship_no', $citizen->citizenship_no) }}" placeholder="उदा. २७-०१-७५-१२३४५"
                       class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-crimson focus:outline-none">
                @error('citizenship_no')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="ward_id" class="block text-xs font-bold text-slate-700 uppercase mb-1">स्थानीय तह तथा वडा (Palika & Ward) *</label>
                <select name="ward_id" id="ward_id" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-crimson focus:outline-none">
                    <option value="">-- वडा छनौट गर्नुहोस् --</option>
                    @foreach($palikas as $palika)
                        <optgroup label="{{ $palika->name_ne }} ({{ $palika->name_en }})">
                            @foreach($palika->wards as $ward)
                                <option value="{{ $ward->id }}" {{ (old('ward_id', $citizen->ward_id) == $ward->id || ($palika->code === 'KMC' && $ward->ward_number === 32)) ? 'selected' : '' }}>
                                    {{ $palika->name_ne }} - वडा नं. {{ $ward->ward_number }} ({{ $ward->office_address ?? 'वडा कार्यालय' }})
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('ward_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-slate-700 uppercase mb-1">टोल / सडक ठेगाना (Tole / Street Address) *</label>
                <input type="text" name="address" id="address" value="{{ old('address', $citizen->address) }}" placeholder="उदा. कोटेश्वर, काठमाडौँ" required
                       class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-crimson focus:outline-none">
                @error('address')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 px-4 bg-nepal-crimson hover:bg-nepal-red text-white font-bold rounded-lg shadow transition text-sm">
                    वडा सुरक्षित गरी ड्यासबोर्डमा जानुहोस् &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
