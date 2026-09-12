@extends('layouts.staff')

@section('page_title', $palika->name_ne . ' का सम्पूर्ण वडाहरू (Wards Directory)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">{{ $palika->name_ne }} का सम्पूर्ण वडाहरू</h1>
            <p class="text-xs text-slate-500 mt-0.5">यस पालिका भित्र रहेका सबै {{ $palika->wards->count() }} वडा कार्यालयहरूको पूर्ण विवरण तथा कार्यसम्पादन।</p>
        </div>
        <a href="{{ route('staff.localgovt.dashboard') }}" class="text-xs font-bold text-nepal-blue hover:underline">
            &larr; पालिका ड्यासबोर्डमा फर्कनुहोस्
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($wards as $ward)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-blue-100 text-nepal-blue">
                            वडा नं. {{ $ward->ward_number }}
                        </span>
                        @if($ward->ward_number == 32 && $palika->code === 'KMC')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700">पाइलट वडा</span>
                        @endif
                    </div>

                    <h2 class="text-base font-bold text-slate-900 mt-1">{{ $ward->office_address }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5 font-mono">फोन: {{ $ward->office_phone ?? '01-40000' . $ward->ward_number }}</p>
                    <p class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $ward->office_email }}</p>

                    <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-2 gap-2 text-xs">
                        <div class="p-2 bg-slate-50 rounded">
                            <span class="text-slate-400 text-[10px] block">निवेदन संख्या</span>
                            <strong class="text-slate-900">{{ $ward->applications_count }}</strong>
                        </div>
                        <div class="p-2 bg-slate-50 rounded">
                            <span class="text-slate-400 text-[10px] block">नागरिक संख्या</span>
                            <strong class="text-slate-900">{{ $ward->citizens_count }}</strong>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100">
                    <a href="{{ route('staff.localgovt.applications', ['ward_id' => $ward->id]) }}" class="w-full inline-flex items-center justify-center px-3 py-2 bg-slate-900 hover:bg-nepal-darkblue text-white rounded-lg text-xs font-bold transition">
                        यस वडाका निवेदन &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="p-4 bg-white rounded-xl border border-slate-200">
        {{ $wards->links() }}
    </div>
</div>
@endsection
