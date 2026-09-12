@extends('layouts.staff')

@section('page_title', $palika->name_ne . ' का सम्पूर्ण वडाहरू (Wards Directory)')

@section('content')
<div class="space-y-6" x-data="{ showAddWardModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <h1 class="text-2xl font-black text-slate-900">{{ $palika->name_ne }} का सम्पूर्ण वडाहरू</h1>
                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 text-nepal-blue">
                    {{ ucfirst(str_replace('_', ' ', $palika->type)) }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">यस पालिका भित्र रहेका सबै {{ $palika->wards->count() }} वडा कार्यालयहरूको पूर्ण विवरण, सम्पर्क तथा नयाँ वडा दर्ता।</p>
        </div>
        <div class="flex items-center space-x-3">
            <button @click="showAddWardModal = true" class="px-4 py-2 bg-nepal-red hover:bg-red-700 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>नयाँ वडा थप्नुहोस्</span>
            </button>
            <a href="{{ route('staff.localgovt.dashboard') }}" class="text-xs font-bold text-nepal-blue hover:underline">
                &larr; ड्यासबोर्डमा फर्कनुहोस्
            </a>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
            <strong class="font-bold block">कृपया फारमका त्रुटिहरू सच्याउनुहोस्:</strong>
            <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Wards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($wards as $ward)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2.5 py-0.5 rounded text-xs font-bold bg-blue-100 text-nepal-blue">
                            वडा नं. {{ $ward->ward_number }}
                        </span>
                        <div class="flex items-center space-x-1.5">
                            @if($ward->ward_number == 32 && $palika->code === 'KMC')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700">पाइलट वडा</span>
                            @endif
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1"></span>
                                सक्रिय
                            </span>
                        </div>
                    </div>

                    <h2 class="text-base font-bold text-slate-900 mt-1">{{ $ward->office_address }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5 font-mono">फोन: {{ $ward->office_phone ?? '01-40000' . $ward->ward_number }}</p>
                    <p class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $ward->office_email }}</p>

                    @php
                        $chair = $ward->staff->where('role', 'ward_chair')->first();
                    @endphp
                    @if($chair)
                        <div class="mt-3 p-2.5 bg-blue-50/60 border border-blue-100 rounded-lg text-xs">
                            <span class="text-[10px] font-bold text-nepal-blue uppercase tracking-wider block">वडा अध्यक्ष (Chairperson)</span>
                            <div class="font-bold text-slate-900 mt-0.5">{{ $chair->name }}</div>
                            <div class="text-[11px] text-slate-500 font-mono flex items-center justify-between mt-0.5">
                                <span>{{ $chair->email }}</span>
                                <span>{{ $chair->phone }}</span>
                            </div>
                        </div>
                    @endif

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
        @empty
            <div class="col-span-3 p-12 text-center bg-white rounded-2xl border border-dashed border-slate-200">
                <p class="text-sm font-bold text-slate-600">यस पालिकामा कुनै वडा दर्ता भएको छैन।</p>
                <button @click="showAddWardModal = true" class="mt-3 px-4 py-2 bg-nepal-red text-white text-xs font-bold rounded-xl shadow">
                    पहिलो वडा थप्नुहोस्
                </button>
            </div>
        @endforelse
    </div>

    @if($wards->hasPages())
        <div class="p-4 bg-white rounded-xl border border-slate-200">
            {{ $wards->links() }}
        </div>
    @endif

    <!-- Modal: Add New Ward to this Municipality -->
    <div x-show="showAddWardModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showAddWardModal = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-black text-slate-900">नयाँ वडा कार्यालय दर्ता (Add Ward)</h3>
                    <p class="text-[11px] text-slate-500">{{ $palika->name_ne }} कार्यक्षेत्र</p>
                </div>
                <button @click="showAddWardModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form action="{{ route('staff.localgovt.wards.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">वडा नम्बर (Ward Number) *</label>
                        <input type="number" name="ward_number" value="{{ $nextWardNumber ?? '' }}" min="1" max="99" required class="w-full text-xs font-bold rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                        <p class="text-[10px] text-slate-400 mt-0.5">सुझावित नम्बर: {{ $nextWardNumber ?? 1 }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">कार्यालय फोन (Office Phone)</label>
                        <input type="text" name="office_phone" placeholder="उदा: 01-4601234" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">कार्यालयको ठेगाना / मुख्य स्थान (Address) *</label>
                    <input type="text" name="office_address" placeholder="उदा: हाँडीगाउँ, काठमाडौँ" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">कार्यालय इमेल (Office Email)</label>
                    <input type="text" name="office_email" placeholder="ward{{ $nextWardNumber ?? 1 }}@{{ strtolower($palika->code) }}.gov.np" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                </div>

                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                    <span class="text-[11px] font-bold text-slate-700 block">वडा अध्यक्ष विवरण (Chairperson Account Provisioning)</span>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] text-slate-500 mb-1">अध्यक्षको नाम (Full Name)</label>
                            <input type="text" name="chairperson_name" placeholder="उदा: राम कृष्ण श्रेष्ठ" class="w-full text-xs rounded-lg border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 mb-1">अध्यक्षको मोबाइल (Phone)</label>
                            <input type="text" name="chairperson_phone" placeholder="उदा: 9851000000" class="w-full text-xs rounded-lg border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400">स्वचालित लगइन <code>chair.{{ strtolower($palika->code) }}&lt;वडा&gt;@wardsewa.gov.np</code> सिर्जना हुनेछ (पासवर्ड: <code>password123</code>)।</p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" @click="showAddWardModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition">रद्द गर्नुहोस्</button>
                    <button type="submit" class="px-5 py-2 bg-nepal-red hover:bg-red-700 text-white rounded-xl text-xs font-bold transition">वडा थप्नुहोस्</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
