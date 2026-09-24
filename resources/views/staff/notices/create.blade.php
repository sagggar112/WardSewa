@extends('layouts.staff')

@section('page_title', __('Notice Management'))

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-black text-slate-900">
            {{ app()->getLocale() === 'ne' ? 'नयाँ सूचना प्रकाशित गर्नुहोस्' : 'Publish New Notice' }}
        </h1>
        <p class="text-xs text-slate-500 mt-0.5">
            {{ app()->getLocale() === 'ne' ? 'नागरिकहरूलाई सार्वजनिक जानकारी गराउन सूचना फारम भर्नुहोस्।' : 'Fill in the notice details to broadcast to citizens on the public notice board.' }}
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form method="POST" action="{{ route('staff.notices.store') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">
                    {{ app()->getLocale() === 'ne' ? 'सूचनाको शीर्षक' : 'Notice Title' }} *
                </label>
                <input type="text" name="title" required 
                       placeholder="{{ app()->getLocale() === 'ne' ? 'उदा. वडा नं. ३२ मा घरजग्गा कर छुट सम्बन्धी जरूरी सूचना' : 'E.g., Property tax rebate circular for current fiscal year' }}"
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs font-semibold text-slate-900">
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">
                    {{ app()->getLocale() === 'ne' ? 'सूचनाको विधा' : 'Category' }} *
                </label>
                <select name="category" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
                    <option value="general">{{ app()->getLocale() === 'ne' ? 'सामान्य जानकारी' : 'General Notice' }}</option>
                    <option value="tax">{{ app()->getLocale() === 'ne' ? 'कर तथा राजस्व' : 'Tax & Revenue' }}</option>
                    <option value="procurement">{{ app()->getLocale() === 'ne' ? 'खरिद तथा बोलपत्र' : 'Procurement' }}</option>
                    <option value="emergency">{{ app()->getLocale() === 'ne' ? 'आपतकालीन सूचना' : 'Emergency Alert' }}</option>
                    <option value="health">{{ app()->getLocale() === 'ne' ? 'स्वास्थ्य तथा खोप' : 'Health & Vaccination' }}</option>
                    <option value="event">{{ app()->getLocale() === 'ne' ? 'सार्वजनिक कार्यक्रम' : 'Public Event' }}</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">
                    {{ app()->getLocale() === 'ne' ? 'सूचनाको पूर्ण व्यहोरा' : 'Notice Content' }} *
                </label>
                <textarea name="content" rows="6" required 
                          placeholder="{{ app()->getLocale() === 'ne' ? 'सूचनाको पूर्ण व्यहोरा यहाँ टाइप गर्नुहोस्...' : 'Type full notice text here...' }}"
                          class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs text-slate-900"></textarea>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">
                    {{ app()->getLocale() === 'ne' ? 'संलग्न फाइल' : 'Attachment' }} (PDF / JPG / PNG)
                </label>
                <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png"
                       class="block w-full text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-white">
            </div>

            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_pinned" value="1" class="rounded border-slate-300 text-nepal-darkblue">
                    <span class="font-semibold text-slate-700">
                        {{ app()->getLocale() === 'ne' ? 'यो सूचनालाई शीर्ष स्थानमा पिन गर्नुहोस्' : 'Pin this notice to top' }}
                    </span>
                </label>
            </div>

            <div class="pt-3 flex justify-end space-x-3">
                <a href="{{ route('staff.notices.index') }}" class="px-4 py-2 text-slate-600 font-semibold">
                    {{ app()->getLocale() === 'ne' ? 'रद्द' : 'Cancel' }}
                </a>
                <button type="submit" class="px-6 py-2.5 bg-nepal-darkblue hover:bg-nepal-blue text-white font-bold rounded-lg shadow transition">
                    {{ app()->getLocale() === 'ne' ? 'सूचना प्रकाशित गर्नुहोस्' : 'Publish Notice' }} &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
