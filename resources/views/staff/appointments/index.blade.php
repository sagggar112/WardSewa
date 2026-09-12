@extends('layouts.staff')

@section('page_title', 'भेटघाट तथा समय तालिका व्यवस्थापन (Appointments Management)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">नागरिक भेटघाट तथा समय तालिका</h1>
            <p class="text-xs text-slate-500 mt-0.5">नागरिकहरूले सेवा तथा कागजात प्रमाणीकरणका लागि बुक गरेका अपोइन्टमेन्टहरू।</p>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <form method="GET" action="{{ route('staff.appointments.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <div>
                <label class="block text-slate-600 font-bold mb-1">मिति (Date)</label>
                <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
            </div>

            <div>
                <label class="block text-slate-600 font-bold mb-1">अवस्था (Status)</label>
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
                    <option value="">सबै स्थिति (All Status)</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>प्रस्तावित (Scheduled)</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>स्वीकृत (Confirmed)</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>सम्पन्न (Completed)</option>
                    <option value="rescheduled" {{ request('status') === 'rescheduled' ? 'selected' : '' }}>समय परिवर्तन (Rescheduled)</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>रद्द (Cancelled)</option>
                    <option value="no_show" {{ request('status') === 'no_show' ? 'selected' : '' }}>अनुपस्थित (No Show)</option>
                </select>
            </div>

            <div class="flex items-end">
                <a href="{{ route('staff.appointments.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg font-semibold text-center w-full">
                    फिल्टर रिसेट
                </a>
            </div>
        </form>
    </div>

    <!-- Appointments Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        @if($appointments->isEmpty())
            <p class="p-8 text-center text-slate-400 text-xs">कुनै भेटघाट फेला परेन।</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">अपोइन्टमेन्ट नं.</th>
                            <th class="p-3">नागरिकको नाम / फोन</th>
                            <th class="p-3">मिति तथा समय</th>
                            <th class="p-3">सेवा / प्रयोजन</th>
                            <th class="p-3">वडा</th>
                            <th class="p-3">अवस्था</th>
                            <th class="p-3 text-right">कार्य (Action)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($appointments as $apt)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $apt->appointment_number }}</td>
                                <td class="p-3">
                                    <div class="font-bold text-slate-900">{{ $apt->citizen->full_name }}</div>
                                    <div class="font-mono text-slate-500 text-[11px]">{{ $apt->citizen->phone }}</div>
                                </td>
                                <td class="p-3">
                                    <div class="font-bold text-slate-900">{{ $apt->appointment_date->format('Y-m-d') }}</div>
                                    <div class="text-[11px] text-slate-500 font-semibold">{{ $apt->time_slot }}</div>
                                </td>
                                <td class="p-3">
                                    <div class="font-medium text-slate-900">{{ $apt->serviceType->name_ne ?? 'सामान्य परामर्श' }}</div>
                                    <div class="text-[10px] text-slate-400 truncate max-w-[150px]">{{ $apt->purpose }}</div>
                                </td>
                                <td class="p-3 text-slate-700 font-medium">वडा {{ $apt->ward->ward_number }}</td>
                                <td class="p-3">
                                    @if($apt->status === 'confirmed')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">स्वीकृत</span>
                                    @elseif($apt->status === 'completed')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">सम्पन्न</span>
                                    @elseif($apt->status === 'no_show')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">अनुपस्थित</span>
                                    @elseif($apt->status === 'rescheduled')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">परिवर्तित</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-800">प्रस्तावित</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <form method="POST" action="{{ route('staff.appointments.status', $apt->id) }}" class="inline-flex space-x-1">
                                        @csrf
                                        @if($apt->status === 'scheduled')
                                            <button type="submit" name="status" value="confirmed" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-[11px] font-semibold">
                                                पुष्टि
                                            </button>
                                        @endif
                                        @if(in_array($apt->status, ['scheduled', 'confirmed']))
                                            <button type="submit" name="status" value="completed" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[11px] font-semibold">
                                                सम्पन्न
                                            </button>
                                            <button type="submit" name="status" value="no_show" class="px-2 py-1 bg-slate-200 hover:bg-red-100 text-slate-700 hover:text-red-700 rounded text-[11px]">
                                                No Show
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
