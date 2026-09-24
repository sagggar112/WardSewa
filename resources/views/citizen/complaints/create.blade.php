@extends('layouts.citizen')

@section('title', app()->getLocale() === 'ne' ? 'नयाँ गुनासो दर्ता' : 'Submit New Grievance')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-black text-slate-900">{{ app()->getLocale() === 'ne' ? 'नयाँ गुनासो तथा उजुरी दर्ता' : 'Submit Grievance / Complaint' }}</h1>
        <p class="text-xs text-slate-500 mt-1">
            {{ (app()->getLocale() === 'ne' ? ($citizen->ward?->palika?->name_ne ?? 'काठमाडौँ महानगरपालिका') : ($citizen->ward?->palika?->name_en ?? 'Kathmandu Metropolitan City')) . ' - ' . __('Ward No.') . ' ' . ($citizen->ward->ward_number ?? '32') }}
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form method="POST" action="{{ route('citizen.complaints.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ app()->getLocale() === 'ne' ? 'समस्याको विधा' : 'Category' }} *</label>
                <select name="category" required class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
                    <option value="sanitation">{{ app()->getLocale() === 'ne' ? 'फोहोरमैला तथा सरसफाई' : 'Sanitation & Waste Management' }}</option>
                    <option value="roads">{{ app()->getLocale() === 'ne' ? 'सडक, खाल्डाखुल्डी तथा ढल' : 'Roads & Drainage' }}</option>
                    <option value="electricity">{{ app()->getLocale() === 'ne' ? 'सडक बत्ती तथा विद्युत' : 'Streetlights & Electricity' }}</option>
                    <option value="water">{{ app()->getLocale() === 'ne' ? 'खानेपानी आपूर्ति' : 'Drinking Water Supply' }}</option>
                    <option value="corruption">{{ app()->getLocale() === 'ne' ? 'सेवा ढिलासुस्ती तथा अनियमितता' : 'Service Delay / Grievance' }}</option>
                    <option value="other">{{ app()->getLocale() === 'ne' ? 'अन्य समस्या' : 'Other Grievance' }}</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ app()->getLocale() === 'ne' ? 'समस्याको शीर्षक' : 'Subject' }} *</label>
                <input type="text" name="subject" required 
                       placeholder="{{ app()->getLocale() === 'ne' ? 'उदा. कोटेश्वर चोक नजिक सडक बत्ती नबलेको बारे' : 'e.g. Broken streetlight near chowk' }}"
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ app()->getLocale() === 'ne' ? 'स्थान / चोक' : 'Location / Landmark' }}</label>
                <input type="text" name="location" 
                       placeholder="{{ app()->getLocale() === 'ne' ? 'उदा. कोटेश्वर महादेवस्थान नजिक' : 'e.g. Near Mahadevsthan, Ward 32' }}"
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ app()->getLocale() === 'ne' ? 'विस्तृत विवरण' : 'Description' }} *</label>
                <textarea name="description" rows="4" required 
                          placeholder="{{ app()->getLocale() === 'ne' ? 'समस्या कहिलेदेखि छ, के असर परेको छ स्पष्ट लेख्नुहोस्...' : 'Describe the issue clearly, since when, and how it impacts the locality...' }}"
                          class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm"></textarea>
            </div>

            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_anonymous" value="1" class="rounded border-slate-300 text-nepal-crimson">
                    <span class="text-xs font-semibold text-slate-700">{{ app()->getLocale() === 'ne' ? 'गुमनाम रूपमा दर्ता गर्नुहोस्' : 'Submit Anonymously' }}</span>
                </label>
                <p class="text-[11px] text-slate-500 mt-1 pl-6">
                    {{ app()->getLocale() === 'ne' ? 'गुमनाम दर्ता गर्दा तपाईँको नाम वडा प्रतिनिधिहरूलाई देखाइने छैन।' : 'Your name will not be shown to ward reviewers when submitted anonymously.' }}
                </p>
            </div>

            <div class="pt-3 flex justify-end space-x-3">
                <a href="{{ route('citizen.complaints.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600">{{ app()->getLocale() === 'ne' ? 'रद्द' : 'Cancel' }}</a>
                <button type="submit" class="px-6 py-2.5 bg-nepal-crimson hover:bg-nepal-red text-white text-xs font-bold rounded-lg shadow transition">
                    {{ app()->getLocale() === 'ne' ? 'गुनासो दर्ता गर्नुहोस्' : 'Submit Grievance' }} &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
