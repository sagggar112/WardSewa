@extends('layouts.citizen')

@section('title', 'मेरो प्रोफाइल')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-black text-slate-900">मेरो नागरिक प्रोफाइल</h1>
        <p class="text-xs text-slate-500 mt-1">व्यक्तिगत विवरण तथा वडा बसोबास अभिलेख व्यवस्थापन गर्नुहोस्।</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form method="POST" action="{{ route('citizen.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">पूरा नाम (Full Name) *</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $citizen->full_name) }}" required
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                    @error('full_name')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">मोबाइल नम्बर (Phone)</label>
                    <input type="text" value="{{ $citizen->phone }}" disabled
                           class="w-full px-3 py-2 border border-slate-200 bg-slate-100 rounded-lg text-sm text-slate-500">
                    <span class="text-[10px] text-slate-400">मोबाइल नम्बर लगइन पहिचान भएकाले परिवर्तन गर्न मिल्दैन</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">इमेल ठेगाना (Email)</label>
                    <input type="email" name="email" value="{{ old('email', $citizen->email) }}"
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                    @error('email')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">नागरिकता नम्बर (Citizenship No)</label>
                    <input type="text" name="citizenship_no" value="{{ old('citizenship_no', $citizen->citizenship_no) }}"
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                    @error('citizenship_no')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">राष्ट्रिय परिचयपत्र नम्बर (NID)</label>
                    <input type="text" name="national_id" value="{{ old('national_id', $citizen->national_id) }}"
                           class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">लिङ्ग (Gender)</label>
                    <select name="gender" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                        <option value="">छनौट गर्नुहोस्</option>
                        <option value="male" {{ old('gender', $citizen->gender) === 'male' ? 'selected' : '' }}>पुरुष (Male)</option>
                        <option value="female" {{ old('gender', $citizen->gender) === 'female' ? 'selected' : '' }}>महिला (Female)</option>
                        <option value="other" {{ old('gender', $citizen->gender) === 'other' ? 'selected' : '' }}>अन्य (Other)</option>
                    </select>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100">
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">स्थानीय तह तथा वडा (Palika & Ward) *</label>
                <select name="ward_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                    @foreach($palikas as $palika)
                        <optgroup label="{{ $palika->name_ne }}">
                            @foreach($palika->wards as $ward)
                                <option value="{{ $ward->id }}" {{ old('ward_id', $citizen->ward_id) == $ward->id ? 'selected' : '' }}>
                                    {{ $palika->name_ne }} - वडा नं. {{ $ward->ward_number }} ({{ $ward->office_address }})
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">टोल / सडक ठेगाना *</label>
                <input type="text" name="address" value="{{ old('address', $citizen->address) }}" required
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-nepal-crimson hover:bg-nepal-red text-white text-xs font-bold rounded-lg shadow transition">
                    विवरण सुरक्षित गर्नुहोस्
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
