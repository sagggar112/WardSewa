@extends('layouts.staff')

@section('page_title', __('Ward Team'))

@section('content')
<div class="space-y-6 max-w-6xl mx-auto" x-data="{ 
    showAddModal: false, 
    editModalOpen: false, 
    editStaff: {} 
}">
    <!-- Header -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-nepal-blue uppercase">
                    {{ __('Ward No.') }} {{ $ward->ward_number }}
                </span>
                <span class="text-xs text-slate-500 font-medium">
                    {{ app()->getLocale() === 'ne' ? 'कर्मचारी कार्यविभाजन तथा टोली' : 'Staff Delegation & Roster' }}
                </span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 mt-1">
                {{ app()->getLocale() === 'ne' ? 'वडा कर्मचारी तथा कार्यविभाजन' : 'Ward Staff & Roles Delegation' }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ app()->getLocale() === 'ne' 
                    ? 'यस वडा कार्यालयका सचिव, सहायक र प्राविधिक कर्मचारीहरूको विवरण, पद तथा जिम्मेवारी व्यवस्थापन।' 
                    : 'Manage ward secretaries, assistant clerks, and technical administrators for this ward.' }}
            </p>
        </div>

        <div class="flex items-center space-x-3">
            @if(in_array($staff->role, ['ward_chair', 'ward_admin', 'super_admin']))
                <button @click="showAddModal = true" class="px-4 py-2 bg-nepal-red hover:bg-red-700 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>{{ app()->getLocale() === 'ne' ? 'नयाँ कर्मचारी थप्नुहोस्' : 'Add New Staff' }}</span>
                </button>
            @endif
            <a href="{{ route('staff.dashboard') }}" class="text-xs font-bold text-nepal-blue hover:underline">
                &larr; {{ app()->getLocale() === 'ne' ? 'ड्यासबोर्डमा फर्कनुहोस्' : 'Back to Dashboard' }}
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
            <strong class="font-bold block">{{ app()->getLocale() === 'ne' ? 'कृपया फारमका त्रुटिहरू सच्याउनुहोस्:' : 'Please correct the errors below:' }}</strong>
            <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Role Responsibility Overview Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50/50 border border-blue-200/70 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                <h3 class="text-xs font-black uppercase text-blue-900 tracking-wider">
                    {{ __('Ward Chairperson') }}
                </h3>
            </div>
            <p class="text-xs font-bold text-slate-800 mt-2">
                {{ app()->getLocale() === 'ne' ? 'अन्तिम निर्णय तथा सिफारिस प्रमाणीकरण' : 'Executive Approval & Digital Signature' }}
            </p>
            <ul class="text-[11px] text-slate-600 mt-2 space-y-1 list-disc list-inside">
                <li>{{ app()->getLocale() === 'ne' ? 'सिफारिस पत्रहरूमा डिजिटल हस्ताक्षर तथा प्रमाणीकरण' : 'Digital signature on issued certificates' }}</li>
                <li>{{ app()->getLocale() === 'ne' ? 'अस्वीकृत गरिएका निवेदनहरूको अन्तिम समीक्षा' : 'Review and decision on disputed applications' }}</li>
                <li>{{ app()->getLocale() === 'ne' ? 'समग्र वडा कार्यसम्पादन तथा कर्मचारी अनुगमन' : 'Overall ward governance and supervision' }}</li>
            </ul>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-orange-50/50 border border-amber-200/70 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span>
                <h3 class="text-xs font-black uppercase text-amber-900 tracking-wider">
                    {{ __('Ward Secretary') }}
                </h3>
            </div>
            <p class="text-xs font-bold text-slate-800 mt-2">
                {{ app()->getLocale() === 'ne' ? 'कागजात रुजु, सिफारिस जाँच तथा सिफारिस ड्राफ्ट' : 'Scrutiny, Verification & Document Review' }}
            </p>
            <ul class="text-[11px] text-slate-600 mt-2 space-y-1 list-disc list-inside">
                <li>{{ app()->getLocale() === 'ne' ? 'नागरिकद्वारा पेश कागजातहरूको प्रारम्भिक रुजु' : 'Initial verification of citizen documents' }}</li>
                <li>{{ app()->getLocale() === 'ne' ? 'अपुग कागजातहरूको माग' : 'Requesting additional documents' }}</li>
                <li>{{ app()->getLocale() === 'ne' ? 'सिफारिस तयार गरी अध्यक्ष समक्ष पेश गर्ने' : 'Preparing drafts for executive sign-off' }}</li>
            </ul>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-teal-50/50 border border-emerald-200/70 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                <h3 class="text-xs font-black uppercase text-emerald-900 tracking-wider">
                    {{ __('Ward Clerk') }}
                </h3>
            </div>
            <p class="text-xs font-bold text-slate-800 mt-2">
                {{ app()->getLocale() === 'ne' ? 'फ्रन्ट डेस्क, दर्ता तथा भेटघाट सहजीकरण' : 'Front Desk, Intake & Appointment Check-in' }}
            </p>
            <ul class="text-[11px] text-slate-600 mt-2 space-y-1 list-disc list-inside">
                <li>{{ app()->getLocale() === 'ne' ? 'नागरिक भेटघाट टोकन चेक-इन' : 'Citizen appointment check-in and queue intake' }}</li>
                <li>{{ app()->getLocale() === 'ne' ? 'काउन्टरबाट सिधा निवेदन दर्ता सहायता' : 'Walk-in citizen application assistance' }}</li>
                <li>{{ app()->getLocale() === 'ne' ? 'नागरिक सोधपुछ तथा स्थिति जानकारी' : 'Inquiries and status tracking' }}</li>
            </ul>
        </div>
    </div>

    <!-- Ward Team Roster Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">
                    {{ app()->getLocale() === 'ne' ? 'वडा कर्मचारी विवरण' : 'Ward Team Roster' }}
                </h2>
                <p class="text-xs text-slate-500">
                    {{ app()->getLocale() === 'ne' ? 'कुल ' . $teamMembers->count() . ' जना कर्मचारी कार्यरत हुनुहुन्छ।' : 'Total ' . $teamMembers->count() . ' active staff members.' }}
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                    <tr>
                        <th class="p-3.5">{{ app()->getLocale() === 'ne' ? 'कर्मचारीको नाम' : 'Staff Name' }}</th>
                        <th class="p-3.5">{{ app()->getLocale() === 'ne' ? 'भूमिका' : 'Role' }}</th>
                        <th class="p-3.5">{{ app()->getLocale() === 'ne' ? 'पदनाम' : 'Designation' }}</th>
                        <th class="p-3.5">{{ app()->getLocale() === 'ne' ? 'इमेल' : 'Email' }}</th>
                        <th class="p-3.5">{{ app()->getLocale() === 'ne' ? 'सम्पर्क फोन' : 'Phone' }}</th>
                        <th class="p-3.5">{{ __('Status') }}</th>
                        <th class="p-3.5 text-right">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($teamMembers as $member)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3.5 font-bold text-slate-900">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-xs">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                    <span>{{ $member->name }}</span>
                                </div>
                            </td>
                            <td class="p-3.5">
                                @if($member->role === 'ward_chair')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 uppercase">{{ __('Ward Chairperson') }}</span>
                                @elseif($member->role === 'secretary')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">{{ __('Ward Secretary') }}</span>
                                @elseif($member->role === 'clerk')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">{{ __('Ward Clerk') }}</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">{{ __('Ward Admin') }}</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-slate-700 font-medium">{{ $member->designation ?? '-' }}</td>
                            <td class="p-3.5 font-mono text-slate-600">{{ $member->email }}</td>
                            <td class="p-3.5 font-mono text-slate-600">{{ $member->phone }}</td>
                            <td class="p-3.5">
                                @if($member->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1"></span>
                                        {{ app()->getLocale() === 'ne' ? 'सक्रिय' : 'Active' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1"></span>
                                        {{ app()->getLocale() === 'ne' ? 'निष्क्रिय' : 'Inactive' }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-right space-x-1">
                                @if(in_array($staff->role, ['ward_chair', 'ward_admin', 'super_admin']))
                                    <button 
                                        @click="editStaff = {{ json_encode($member) }}; editModalOpen = true"
                                        class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded text-[11px] font-semibold transition"
                                    >
                                        {{ app()->getLocale() === 'ne' ? 'सम्पादन' : 'Edit' }}
                                    </button>

                                    @if($member->id !== $staff->id && $member->role !== 'ward_chair')
                                        <form method="POST" action="{{ route('staff.team.destroy', $member->id) }}" class="inline-block" onsubmit="return confirm('{{ app()->getLocale() === 'ne' ? 'के तपाईँ यो कर्मचारीलाई निष्क्रिय गर्न निश्चित हुनुहुन्छ?' : 'Are you sure you want to deactivate this staff member?' }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded text-[11px] font-semibold transition">
                                                {{ app()->getLocale() === 'ne' ? 'निष्क्रिय' : 'Deactivate' }}
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-[11px] text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">
                                {{ app()->getLocale() === 'ne' ? 'कुनै कर्मचारी फेला परेन।' : 'No team members found.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Add New Team Member -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="showAddModal = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-black text-slate-900">
                        {{ app()->getLocale() === 'ne' ? 'नयाँ कर्मचारी थप्नुहोस्' : 'Add New Ward Staff' }}
                    </h3>
                    <p class="text-[11px] text-slate-500">
                        {{ __('Ward No.') }} {{ $ward->ward_number }} {{ __('Ward Jurisdiction') }}
                    </p>
                </div>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form action="{{ route('staff.team.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            {{ app()->getLocale() === 'ne' ? 'कर्मचारीको नाम' : 'Full Name' }} *
                        </label>
                        <input type="text" name="name" required placeholder="{{ app()->getLocale() === 'ne' ? 'उदा: बिमल श्रेष्ठ' : 'E.g., Bimal Shrestha' }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            {{ app()->getLocale() === 'ne' ? 'भूमिका' : 'Role' }} *
                        </label>
                        <select name="role" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue font-bold">
                            <option value="secretary">{{ __('Ward Secretary') }}</option>
                            <option value="clerk">{{ __('Ward Clerk') }}</option>
                            <option value="ward_admin">{{ __('Ward Admin') }}</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            {{ app()->getLocale() === 'ne' ? 'पदनाम' : 'Designation' }} *
                        </label>
                        <input type="text" name="designation" required placeholder="{{ app()->getLocale() === 'ne' ? 'उदा: वडा सचिव / खरिदार' : 'E.g., Ward Secretary / Assistant' }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            {{ __('Phone:') }} *
                        </label>
                        <input type="text" name="phone" required placeholder="98XXXXXXXX" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ app()->getLocale() === 'ne' ? 'इमेल ठेगाना' : 'Login Email' }} *
                    </label>
                    <input type="email" name="email" required placeholder="secretary.ward{{ $ward->ward_number }}@wardsewa.gov.np" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        {{ app()->getLocale() === 'ne' ? 'प्रारम्भिक पासवर्ड' : 'Initial Password' }}
                    </label>
                    <input type="password" name="password" placeholder="default: password123" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    <p class="text-[10px] text-slate-400 mt-0.5">
                        {{ app()->getLocale() === 'ne' ? 'खाली छाडेमा पूर्वनिर्धारित पासवर्ड: password123 रहनेछ।' : 'Leave empty to set default password: password123' }}
                    </p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                        {{ app()->getLocale() === 'ne' ? 'रद्द गर्नुहोस्' : 'Cancel' }}
                    </button>
                    <button type="submit" class="px-5 py-2 bg-nepal-red hover:bg-red-700 text-white rounded-xl text-xs font-bold transition">
                        {{ app()->getLocale() === 'ne' ? 'कर्मचारी थप्नुहोस्' : 'Add Staff' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Team Member -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.away="editModalOpen = false" class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-black text-slate-900">
                        {{ app()->getLocale() === 'ne' ? 'कर्मचारी विवरण सम्पादन' : 'Edit Staff Details' }}
                    </h3>
                    <p class="text-[11px] text-slate-500" x-text="editStaff.name + ' (' + editStaff.email + ')'"></p>
                </div>
                <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form :action="'{{ url('/staff/team') }}/' + editStaff.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-3">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ app()->getLocale() === 'ne' ? 'कर्मचारीको नाम *' : 'Staff Name *' }}</label>
                        <input type="text" name="name" x-model="editStaff.name" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('Phone:') }} *</label>
                        <input type="text" name="phone" x-model="editStaff.phone" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ app()->getLocale() === 'ne' ? 'पदनाम *' : 'Designation *' }}</label>
                        <input type="text" name="designation" x-model="editStaff.designation" required class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('Status') }} *</label>
                        <select name="is_active" x-model="editStaff.is_active" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue font-bold">
                            <option :value="1">{{ app()->getLocale() === 'ne' ? 'सक्रिय' : 'Active' }}</option>
                            <option :value="0">{{ app()->getLocale() === 'ne' ? 'निष्क्रिय' : 'Inactive' }}</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('Reset Password') }} ({{ app()->getLocale() === 'ne' ? 'ऐच्छिक' : 'Optional' }})</label>
                    <input type="password" name="password" placeholder="{{ app()->getLocale() === 'ne' ? 'नयाँ पासवर्ड राख्न मात्र भर्नुहोस्' : 'Leave empty to keep current password' }}" class="w-full text-xs rounded-xl border-slate-300 focus:ring-nepal-blue focus:border-nepal-blue">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                        {{ app()->getLocale() === 'ne' ? 'रद्द गर्नुहोस्' : 'Cancel' }}
                    </button>
                    <button type="submit" class="px-5 py-2 bg-nepal-blue hover:bg-nepal-darkblue text-white rounded-xl text-xs font-bold transition">
                        {{ app()->getLocale() === 'ne' ? 'विवरण अद्यावधिक गर्नुहोस्' : 'Update Details' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
