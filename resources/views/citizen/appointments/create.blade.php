@extends('layouts.citizen')

@section('title', 'नयाँ अपोइन्टमेन्ट लिनुहोस् (Book Appointment)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Link & Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('citizen.appointments.index') }}" class="text-xs font-semibold text-nepal-blue hover:underline flex items-center">
            &larr; अपोइन्टमेन्ट सूचीमा फर्कनुहोस्
        </a>
        <span class="text-xs text-slate-500 font-medium">वडा कार्यालय समय: आइतबार - शुक्रबार (१०:०० AM - ५:०० PM)</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <div class="border-b border-slate-100 pb-4 mb-6">
                <div class="text-xs font-bold text-nepal-crimson uppercase tracking-wider">वडा सेवा बुकिङ (Direct Ward Visit)</div>
                <h1 class="text-xl font-black text-slate-900 mt-1">वडा कार्यालय भ्रमण समय तालिका बुक गर्नुहोस्</h1>
                <p class="text-xs text-slate-500 mt-1">
                    कागजात प्रमाणीकरण, सिफारिस संकलन वा पदाधिकारीहरूसँग भेट्नका लागि समय छनोट गर्नुहोस्।
                </p>
            </div>

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl mb-6 text-xs">
                    <p class="font-bold mb-1">कृपया फारममा निम्न विवरणहरू सच्याउनुहोस्:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('citizen.appointments.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Registered Ward Badge -->
                <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-200 flex items-center justify-between text-xs">
                    <div>
                        <span class="text-slate-500 block text-[11px]">तपाईँको तोकिएको वडा कार्यालय:</span>
                        <span class="font-bold text-slate-800">
                            {{ $citizen->ward->palika->name_ne ?? 'काठमाडौँ महानगरपालिका' }} - वडा नं. {{ $citizen->ward->ward_number ?? '३२' }}
                        </span>
                    </div>
                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold">स्वतः छनोट</span>
                </div>

                <!-- Purpose / Subject -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        भ्रमणको मुख्य विषय / उद्देश्य (Purpose of Visit) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="purpose" value="{{ old('purpose') }}" required
                        placeholder="उदाहरण: चारकिल्ला सिफारिसका लागि कागजात बुझाउन, वडा अध्यक्ष भेटघाट"
                        class="w-full text-xs rounded-lg border-slate-300 focus:border-nepal-blue focus:ring focus:ring-blue-100 p-2.5 border">
                    <p class="text-[11px] text-slate-400 mt-1">भ्रमण गर्नुको उद्देश्य प्रष्ट उल्लेख गर्नुहोस्।</p>
                </div>

                <!-- Related Service Type -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        सम्बन्धित सेवा (Related Service - Optional)
                    </label>
                    <select name="service_type_id" class="w-full text-xs rounded-lg border-slate-300 focus:border-nepal-blue focus:ring focus:ring-blue-100 p-2.5 border bg-white">
                        <option value="">-- सामान्य सोधपुछ / अन्य विषय (General Inquiry) --</option>
                        @foreach($serviceTypes as $svc)
                            <option value="{{ $svc->id }}" {{ old('service_type_id') == $svc->id ? 'selected' : '' }}>
                                {{ $svc->name_ne }} ({{ $svc->name_en }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date & Slot Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            भ्रमण मिति (Preferred Date) <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="appointment_date" value="{{ old('appointment_date', date('Y-m-d', strtotime('+1 day'))) }}"
                            min="{{ date('Y-m-d') }}" required
                            class="w-full text-xs rounded-lg border-slate-300 focus:border-nepal-blue focus:ring focus:ring-blue-100 p-2.5 border">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            समय तालिका (Time Slot) <span class="text-red-500">*</span>
                        </label>
                        <select name="time_slot" required class="w-full text-xs rounded-lg border-slate-300 focus:border-nepal-blue focus:ring focus:ring-blue-100 p-2.5 border bg-white font-mono">
                            <option value="">-- समय छनोट गर्नुहोस् --</option>
                            <option value="10:00 AM - 11:00 AM" {{ old('time_slot') == '10:00 AM - 11:00 AM' ? 'selected' : '' }}>10:00 AM - 11:00 AM</option>
                            <option value="11:00 AM - 12:00 PM" {{ old('time_slot') == '11:00 AM - 12:00 PM' ? 'selected' : '' }}>11:00 AM - 12:00 PM</option>
                            <option value="12:00 PM - 01:00 PM" {{ old('time_slot') == '12:00 PM - 01:00 PM' ? 'selected' : '' }}>12:00 PM - 01:00 PM</option>
                            <option value="01:30 PM - 02:30 PM" {{ old('time_slot') == '01:30 PM - 02:30 PM' ? 'selected' : '' }}>01:30 PM - 02:30 PM</option>
                            <option value="02:30 PM - 03:30 PM" {{ old('time_slot') == '02:30 PM - 03:30 PM' ? 'selected' : '' }}>02:30 PM - 03:30 PM</option>
                            <option value="03:30 PM - 04:30 PM" {{ old('time_slot') == '03:30 PM - 04:30 PM' ? 'selected' : '' }}>03:30 PM - 04:30 PM</option>
                        </select>
                    </div>
                </div>

                <!-- Additional Remarks -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        थप विवरण / कैफियत (Additional Remarks)
                    </label>
                    <textarea name="remarks" rows="3" placeholder="कुनै विशेष जानकारी भए यहाँ खुलाउनुहोस्..."
                        class="w-full text-xs rounded-lg border-slate-300 focus:border-nepal-blue focus:ring focus:ring-blue-100 p-2.5 border">{{ old('remarks') }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-nepal-blue hover:bg-nepal-darkblue text-white font-bold text-sm rounded-xl shadow transition flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>अपोइन्टमेन्ट सुरक्षित गर्नुहोस् (Confirm & Book Appointment)</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Instructions & Guidelines Widget (1 col) -->
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">कार्यालय भ्रमण गर्दा ल्याउनुपर्ने कागजातहरू:</h3>
                
                <ul class="text-xs text-slate-600 space-y-2.5">
                    <li class="flex items-start space-x-2">
                        <span class="w-4 h-4 rounded-full bg-blue-100 text-nepal-blue font-bold flex items-center justify-center text-[10px] flex-shrink-0 mt-0.5">१</span>
                        <span>सक्कल नेपाली नागरिकताको प्रमाणपत्र र २ प्रति फोटोकपी।</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="w-4 h-4 rounded-full bg-blue-100 text-nepal-blue font-bold flex items-center justify-center text-[10px] flex-shrink-0 mt-0.5">२</span>
                        <span>हालसालै खिचिएको २ प्रति पासपोर्ट साइजको फोटो।</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="w-4 h-4 rounded-full bg-blue-100 text-nepal-blue font-bold flex items-center justify-center text-[10px] flex-shrink-0 mt-0.5">३</span>
                        <span>घर/जग्गा सम्बन्धी सिफारिस भए लालपुर्जा र चालु आ.व. को सम्पत्ति कर तिरेको रसिद।</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="w-4 h-4 rounded-full bg-blue-100 text-nepal-blue font-bold flex items-center justify-center text-[10px] flex-shrink-0 mt-0.5">४</span>
                        <span>अनलाइन पेश गरिएको निवेदन नम्बर (यदि पहिले नै दर्ता छ भने)।</span>
                    </li>
                </ul>
            </div>

            <div class="bg-amber-50 rounded-2xl border border-amber-200 p-5 text-xs text-amber-900 space-y-2">
                <div class="font-bold flex items-center space-x-1.5 text-amber-800">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>महत्वपूर्ण सूचना:</span>
                </div>
                <p class="text-slate-600 leading-relaxed">
                    सार्वजनिक बिदाका दिनहरूमा वडा कार्यालय बन्द रहनेछ। यदि कुनै कारणवश आउन नसकेमा सोही दिन बिहान ९:०० बजेभित्र प्रणालीमार्फत जानकारी दिनुहोला।
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
