@extends('layouts.citizen')

@section('title', $biller->name_ne . ' महसुल भुक्तानी')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <span class="text-xs font-bold text-nepal-crimson uppercase tracking-wider">{{ $biller->category }}</span>
        <h1 class="text-2xl font-black text-slate-900 mt-0.5">{{ $biller->name_ne }}</h1>
        <p class="text-xs text-slate-500">{{ $biller->name_en }}</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form method="POST" action="{{ route('citizen.bills.pay', $biller->id) }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    ग्राहक / उपभोक्ता नम्बर (Consumer ID / SC No) *
                </label>
                <input type="text" name="consumer_id" value="{{ request('consumer_id') }}" required placeholder="उदा. 012.34.567"
                       class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm font-mono font-bold focus:ring-2 focus:ring-nepal-crimson">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    भुक्तानी रकम (Amount in NPR) *
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-sm">रु.</span>
                    <input type="number" step="0.01" min="10" name="amount" required placeholder="500.00"
                           class="w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg text-base font-mono font-bold">
                </div>
                <span class="text-[11px] text-slate-400 mt-1 block">सेवा शुल्क: रु. ५.०० लागु हुनेछ।</span>
            </div>

            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="save_account" value="1" checked class="rounded border-slate-300 text-nepal-crimson">
                    <span class="text-xs font-semibold text-slate-700">भविष्यको लागि यो ग्राहक नम्बर सुरक्षित गर्नुहोस्</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-purple-700 hover:bg-purple-800 text-white font-bold rounded-xl shadow transition text-sm flex items-center justify-center space-x-2">
                    <span>खल्ती (Khalti) बाट महसुल तिर्नुहोस् &rarr;</span>
                </button>
            </div>
        </form>
    </div>

    <div class="text-center">
        <a href="{{ route('citizen.bills.index') }}" class="text-xs text-slate-600 hover:underline">&larr; अन्य बिलरहरूमा फर्कनुहोस्</a>
    </div>
</div>
@endsection
