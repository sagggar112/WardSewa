@extends('layouts.citizen')

@section('title', __('New Application'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-black text-slate-900">{{ __('New Application') }}</h1>
        <p class="text-xs text-slate-500 mt-1">
            @if($citizen->ward)
                @if(app()->getLocale() === 'ne')
                    {{ $citizen->ward->palika->name_ne ?? 'स्थानीय तह' }} - वडा नं. {{ $citizen->ward->ward_number }} कार्यालयका लागि निवेदन भर्दै हुनुहुन्छ।
                @else
                    Submitting application to {{ $citizen->ward->palika->name_en ?? 'Local Government' }} - Ward No. {{ $citizen->ward->ward_number }} Office.
                @endif
            @endif
        </p>
    </div>

    <!-- Service Selector -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <label for="service_select" class="block text-xs font-bold text-slate-700 uppercase mb-2">
            {{ app()->getLocale() === 'ne' ? 'सेवाको प्रकार छनौट गर्नुहोस्' : 'Select Service Type' }}
        </label>
        <select id="service_select" onchange="window.location.href = '{{ route('citizen.applications.create') }}?service=' + this.value"
                class="w-full px-4 py-3 border border-slate-300 rounded-xl text-base font-bold text-slate-900 focus:ring-2 focus:ring-nepal-crimson focus:outline-none">
            @foreach($serviceTypes as $svc)
                <option value="{{ $svc->code }}" {{ ($selectedService && $selectedService->id === $svc->id) ? 'selected' : '' }}>
                    {{ app()->getLocale() === 'ne' ? ($svc->name_ne ?? $svc->name_en) : ($svc->name_en ?? $svc->name_ne) }} - {{ $svc->fee > 0 ? (app()->getLocale() === 'ne' ? 'रु. ' . number_format($svc->fee, 0) : 'NPR ' . number_format($svc->fee, 0)) : __('Free') }}
                </option>
            @endforeach
        </select>
    </div>

    @if($selectedService)
    <form method="POST" action="{{ route('citizen.applications.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="service_type_id" value="{{ $selectedService->id }}">

        <!-- Service Summary Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold text-nepal-blue uppercase">
                    {{ app()->getLocale() === 'ne' ? 'चयन गरिएको सेवा' : 'Selected Service' }}
                </span>
                <h3 class="text-lg font-bold text-slate-900 mt-0.5">
                    {{ app()->getLocale() === 'ne' ? ($selectedService->name_ne ?? $selectedService->name_en) : ($selectedService->name_en ?? $selectedService->name_ne) }}
                </h3>
            </div>
            <div class="text-left sm:text-right shrink-0">
                <div class="text-xs text-slate-500">
                    {{ __('Fee:') }} <strong class="text-slate-900 text-sm">{{ $selectedService->fee > 0 ? (app()->getLocale() === 'ne' ? 'रु. ' . number_format($selectedService->fee, 2) : 'NPR ' . number_format($selectedService->fee, 2)) : __('Free') }}</strong>
                </div>
                <div class="text-xs text-slate-500 mt-0.5">
                    @if(app()->getLocale() === 'ne')
                        अनुमानित समय: <strong class="text-slate-900">{{ $selectedService->turnaround_days }} कार्यदिन</strong>
                    @else
                        Estimated Time: <strong class="text-slate-900">{{ $selectedService->turnaround_days }} Business Days</strong>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dynamic Form Fields -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">
                {{ app()->getLocale() === 'ne' ? '१. निवेदनका लागि आवश्यक विवरणहरू' : '1. Required Application Details' }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if(!empty($selectedService->form_fields))
                    @foreach($selectedService->form_fields as $field)
                        <div class="{{ ($field['type'] ?? 'text') === 'textarea' ? 'md:col-span-2' : '' }}">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                {{ app()->getLocale() === 'ne' ? ($field['label_ne'] ?? $field['name']) : ($field['label_en'] ?? $field['label_ne'] ?? $field['name']) }}
                                @if(!empty($field['required'])) <span class="text-rose-500">*</span> @endif
                            </label>

                            @if(($field['type'] ?? 'text') === 'textarea')
                                <textarea name="form_data[{{ $field['name'] }}]" rows="3" {{ !empty($field['required']) ? 'required' : '' }}
                                          class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-crimson focus:outline-none"></textarea>
                            @elseif(($field['type'] ?? 'text') === 'select' && !empty($field['options']))
                                <select name="form_data[{{ $field['name'] }}]" {{ !empty($field['required']) ? 'required' : '' }}
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-crimson focus:outline-none">
                                    @foreach($field['options'] as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="{{ $field['type'] ?? 'text' }}" name="form_data[{{ $field['name'] }}]" {{ !empty($field['required']) ? 'required' : '' }}
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-nepal-crimson focus:outline-none">
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                            {{ app()->getLocale() === 'ne' ? 'निवेदनको विस्तृत विवरण वा प्रयोजन' : 'Detailed Purpose of Application' }} *
                        </label>
                        <textarea name="form_data[purpose]" rows="4" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm"></textarea>
                    </div>
                @endif
            </div>
        </div>

        <!-- Required Documents File Uploads -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">
                {{ app()->getLocale() === 'ne' ? '२. प्रमाण कागजातहरू अपलोड गर्नुहोस्' : '2. Upload Supporting Documents' }}
            </h2>
            <p class="text-xs text-slate-500">
                {{ app()->getLocale() === 'ne' ? 'स्वीकृत फाइल प्रकार: PDF, JPG, PNG (प्रति फाइल अधिकतम ५ MB)' : 'Accepted formats: PDF, JPG, PNG (Max 5 MB per file)' }}
            </p>

            <div class="space-y-4">
                @if(!empty($selectedService->required_documents))
                    @foreach($selectedService->required_documents as $doc)
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-bold text-slate-800">
                                    {{ app()->getLocale() === 'ne' ? ($doc['label_ne'] ?? $doc['key']) : ($doc['label_en'] ?? $doc['label_ne'] ?? $doc['key']) }}
                                    @if(!empty($doc['required'])) <span class="text-rose-500">*</span> @endif
                                </label>
                                <span class="text-[10px] text-slate-500 font-semibold uppercase">
                                    {{ !empty($doc['required']) ? (app()->getLocale() === 'ne' ? 'अनिवार्य' : 'Mandatory') : (app()->getLocale() === 'ne' ? 'ऐच्छिक' : 'Optional') }}
                                </span>
                            </div>
                            <input type="file" name="documents[{{ $doc['key'] }}]" accept=".pdf,.jpg,.jpeg,.png" {{ !empty($doc['required']) ? 'required' : '' }}
                                   class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-nepal-blue file:text-white hover:file:bg-nepal-darkblue">
                        </div>
                    @endforeach
                @else
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                        <label class="text-xs font-bold text-slate-800 block mb-2">
                            {{ app()->getLocale() === 'ne' ? 'नागरिकता प्रमाणपत्र वा अन्य प्रमाण' : 'Citizenship Certificate or Supporting Document' }} *
                        </label>
                        <input type="file" name="documents[citizenship]" accept=".pdf,.jpg,.jpeg,.png" required class="block w-full text-xs text-slate-500">
                    </div>
                @endif
            </div>
        </div>

        <!-- Self Declaration -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <label class="flex items-start space-x-3 cursor-pointer">
                <input type="checkbox" required class="mt-1 h-4 w-4 rounded border-slate-300 text-nepal-crimson focus:ring-nepal-crimson">
                <span class="text-xs text-slate-600 leading-relaxed">
                    @if(app()->getLocale() === 'ne')
                        म प्रमाणित गर्दछु कि माथि उल्लिखित सबै विवरण तथा संलग्न गरिएका कागजातहरू सत्य र तथ्य छन्। झुट्ठा वा किर्ते ठहरिएमा प्रचलित कानुन बमोजिम सजाय भोग्न मञ्जुर छु।
                    @else
                        I hereby declare that all information and attached documents submitted are true, accurate, and valid. If found falsified, I agree to be subject to penalties under prevailing law.
                    @endif
                </span>
            </label>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('citizen.dashboard') }}" class="px-5 py-2.5 text-slate-600 hover:text-slate-900 text-xs font-semibold">
                    {{ app()->getLocale() === 'ne' ? 'रद्द गर्नुहोस्' : 'Cancel' }}
                </a>
                <button type="submit" class="px-8 py-3 bg-nepal-crimson hover:bg-nepal-red text-white text-sm font-bold rounded-xl shadow transition">
                    {{ app()->getLocale() === 'ne' ? 'वडा कार्यालयमा निवेदन पेश गर्नुहोस्' : 'Submit Application to Ward Office' }} &rarr;
                </button>
            </div>
        </div>
    </form>
    @endif
</div>
@endsection
