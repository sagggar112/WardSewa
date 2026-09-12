@extends('layouts.staff')

@section('page_title', $ward->palika->name_ne . ' - वडा नं. ' . $ward->ward_number . ' कर्मचारी व्यवस्थापन')

@section('content')
<div class="space-y-6" x-data="{ showAddModal: false, editModalOpen: false, editStaff: {} }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('staff.localgovt.wards') }}" class="text-xs font-bold text-nepal-blue hover:underline">
                    &larr; {{ $palika->name_ne }} का वडाहरू
                </a>
                <span class="text-xs text-slate-400">/</span>
                <span class="px-2.5 py-0.5 rounded text-xs font-black bg-blue-100 text-nepal-blue">
                    वडा नं. {{ $ward->ward_number }} ({{ $ward->office_address }})
                </span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 mt-1">वडा कर्मचारी तथा पद विवरण (Ward Staff Directory)</h1>
            <p class="text-xs text-slate-500 mt-0.5">पालिका प्रशासक स्तरबाट यस वडाका अध्यक्ष, सचिव, तथा सहायक कर्मचारीहरूको पूर्ण विवरण व्यवस्थापन तथा अद्यावधिक।</p>
        </div>

        <div class="flex items-center space-x-3">
            <button @click="showAddModal = true" class="px-4 py-2 bg-nepal-red hover:bg-red-700 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <span>यस वडामा कर्मचारी थप्नुहोस्</span>
            </button>
            <a href="{{ route('staff.localgovt.wards') }}" class="text-xs font-bold text-nepal-blue hover:underline">
                वडाहरूको सूची &rarr;
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

    <!-- Ward Quick Summary Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">वडा कार्यालय विवरण</span>
            <div class="text-base font-bold text-slate-900">{{ $ward->office_address }}</div>
            <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 font-mono">
                <span>फोन: {{ $ward->office_phone ?? 'उपलब्ध छैन' }}</span>
                <span>•</span>
                <span>इमेल: {{ $ward->office_email }}</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-center">
                <span class="text-[10px] text-slate-400 font-bold block uppercase">कुल कर्मचारी</span>
                <span class="text-lg font-black text-slate-900">{{ $teamMembers->count() }} जना</span>
            </div>
            <a href="{{ route('staff.localgovt.applications', ['ward_id' => $ward->id]) }}" class="px-4 py-2.5 bg-slate-900 hover:bg-nepal-darkblue text-white rounded-xl text-xs font-bold transition">
                यस वडाका निवेदनहरू &rarr;
            </a>
        </div>
    </div>

    <!-- Staff Roster Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">वडा कर्मचारी तथा पद विवरण तालिका</h2>
                <p class="text-xs text-slate-500">वडा अध्यक्ष, सचिव, दर्ता सहायक तथा अन्य कर्मचारीहरू</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                    <tr>
                        <th class="p-3.5">कर्मचारीको नाम</th>
                        <th class="p-3.5">भूमिका (Role)</th>
                        <th class="p-3.5">पदनाम (Designation)</th>
                        <th class="p-3.5">लगइन इमेल</th>
                        <th class="p-3.5">सम्पर्क फोन</th>
                        <th class="p-3.5">स्थिति</th>
                        <th class="p-3.5 text-right">कार्य</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($teamMembers as $member)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3.5 font-bold text-slate-900">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 rounded-full bg-nepal-darkblue text-white flex items-center justify-center font-bold text-xs">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                    <span>{{ $member->name }}</span>
                                </div>
                            </td>
                            <td class="p-3.5">
                                @if($member->role === 'ward_chair')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 uppercase">वडा अध्यक्ष</span>
                                @elseif($member->role === 'secretary')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">वडा सचिव</span>
                                @elseif($member->role === 'clerk')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">सहायक / Clerk</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">{{ $member->role_title }}</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-slate-700 font-medium">{{ $member->designation ?? '-' }}</td>
                            <td class="p-3.5 font-mono text-slate-600">{{ $member->email }}</td>
                            <td class="p-3.5 font-mono text-slate-600">{{ $member->phone }}</td>
                            <td class="p-3.5">
                                @if($member->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1"></span>
                                        सक्रिय
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1"></span>
                                        निष्क्रिय
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-right">
                                <button 
                                    @click="editStaff = {{ json_encode($member) }}; editModalOpen = true"
                                    class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded text-xs font-semibold transition"
                                >
                                    विवरण सम्पादन &rarr;
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">यस वडामा कुनै कर्मचारी दर्ता भएको छैन।</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Add New Staff Member for this Ward -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showAddModal = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-black text-slate-900">वडामा नयाँ कर्मचारी थप्नुहोस्</h3>
                    <p class="text-[11px] text-slate-500">{{ $palika->name_ne }} - वडा नं. {{ $ward->ward_number }}</p>
                </div>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form action="{{ route('staff.localgovt.wards.staff.store', $ward->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">कर्मचारीको नाम *</label>
                        <input type="text" name="name" required placeholder="उदा: सन्तोष अधिकारी" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">भूमिका (Role) *</label>
                        <select name="role" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue font-bold">
                            <option value="secretary">वडा सचिव (Ward Secretary)</option>
                            <option value="clerk">सहायक कर्मचारी (Clerk / Front Desk)</option>
                            <option value="ward_admin">वडा प्रशासक (Ward Admin)</option>
                            <option value="ward_chair">वडा अध्यक्ष (Ward Chairperson)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">पदनाम (Designation) *</label>
                        <input type="text" name="designation" required placeholder="उदा: वडा सचिव / नायब सुब्बा" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">सम्पर्क फोन (Phone) *</label>
                        <input type="text" name="phone" required placeholder="उदा: 9841000000" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">लगइन इमेल ठेगाना *</label>
                    <input type="email" name="email" required placeholder="secretary.{{ strtolower($palika->code) }}{{ $ward->ward_number }}@wardsewa.gov.np" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">प्रारम्भिक पासवर्ड (Initial Password)</label>
                    <input type="password" name="password" placeholder="खाली छाडेमा default: password123 रहनेछ" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition">रद्द गर्नुहोस्</button>
                    <button type="submit" class="px-5 py-2 bg-nepal-red hover:bg-red-700 text-white rounded-xl text-xs font-bold transition">कर्मचारी दर्ता गर्नुहोस्</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Staff Member by Municipal Admin -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="editModalOpen = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-black text-slate-900">कर्मचारी विवरण सम्पादन (Edit Staff)</h3>
                    <p class="text-[11px] text-slate-500" x-text="editStaff.name + ' (' + editStaff.email + ')'"></p>
                </div>
                <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form :action="'{{ url('/staff/localgovt/wards/' . $ward->id . '/staff') }}/' + editStaff.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">कर्मचारीको नाम *</label>
                        <input type="text" name="name" x-model="editStaff.name" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">सम्पर्क फोन *</label>
                        <input type="text" name="phone" x-model="editStaff.phone" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">पदनाम (Designation) *</label>
                        <input type="text" name="designation" x-model="editStaff.designation" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">भूमिका (Role)</label>
                        <select name="role" x-model="editStaff.role" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue font-bold">
                            <option value="ward_chair">वडा अध्यक्ष (Ward Chairperson)</option>
                            <option value="secretary">वडा सचिव (Ward Secretary)</option>
                            <option value="clerk">सहायक कर्मचारी (Clerk / Front Desk)</option>
                            <option value="ward_admin">वडा प्रशासक (Ward Admin)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">खाता स्थिति (Status) *</label>
                    <select name="is_active" x-model="editStaff.is_active" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue font-bold">
                        <option :value="1">सक्रिय (Active)</option>
                        <option :value="0">निष्क्रिय (Inactive)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">नयाँ पासवर्ड (Password Reset - Optional)</label>
                    <input type="password" name="password" placeholder="पासवर्ड परिवर्तन गर्न मात्र भर्नुहोस्" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition">रद्द गर्नुहोस्</button>
                    <button type="submit" class="px-5 py-2 bg-nepal-blue hover:bg-nepal-darkblue text-white rounded-xl text-xs font-bold transition">विवरण अद्यावधिक गर्नुहोस्</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
