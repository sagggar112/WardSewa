@extends('layouts.staff')

@section('page_title', (app()->getLocale() === 'ne' ? ($palika->name_ne ?? $palika->name_en) : ($palika->name_en ?? $palika->name_ne)) . ' - ' . __('Palika-Wide Applications'))

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">
                {{ (app()->getLocale() === 'ne' ? ($palika->name_ne ?? $palika->name_en) : ($palika->name_en ?? $palika->name_ne)) }} - {{ __('Palika-Wide Applications') }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ app()->getLocale() === 'ne' ? 'यस पालिकाका सम्पूर्ण ' . $palika->wards->count() . ' वडा कार्यालयहरूबाट प्राप्त निवेदनहरूको एकीकृत सूची।' : 'Consolidated view of citizen applications across all ' . $palika->wards->count() . ' ward offices.' }}
            </p>
        </div>
    </div>

    <!-- Search and Ward Filter -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <form method="GET" action="{{ route('staff.localgovt.applications') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="{{ app()->getLocale() === 'ne' ? 'निवेदन नं., नागरिकको नाम वा फोन...' : 'Application No, Citizen Name or Phone...' }}"
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
            </div>

            <div>
                <select name="ward_id" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
                    <option value="">{{ app()->getLocale() === 'ne' ? 'सबै वडाहरू' : 'All Wards' }}</option>
                    @foreach($wards as $w)
                        <option value="{{ $w->id }}" {{ request('ward_id') == $w->id ? 'selected' : '' }}>
                            {{ __('Ward No.') }} {{ $w->ward_number }} @if($w->office_address) - {{ $w->office_address }} @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>{{ __('Submitted') }}</option>
                    <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>{{ __('Under Review') }}</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Applications Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        @if($applications->isEmpty())
            <p class="p-8 text-center text-slate-400 text-xs">{{ app()->getLocale() === 'ne' ? 'कुनै निवेदन फेला परेन।' : 'No applications found.' }}</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">{{ __('Application Number') }}</th>
                            <th class="p-3">{{ __('Full Name') }}</th>
                            <th class="p-3">{{ __('Ward') }}</th>
                            <th class="p-3">{{ app()->getLocale() === 'ne' ? 'सेवाको प्रकार' : 'Service Type' }}</th>
                            <th class="p-3">{{ __('Date') }}</th>
                            <th class="p-3">{{ __('Status') }}</th>
                            <th class="p-3 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($applications as $app)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $app->application_number }}</td>
                                <td class="p-3 font-semibold text-slate-800">{{ $app->citizen->full_name }}</td>
                                <td class="p-3 font-bold text-slate-700">{{ __('Ward No.') }} {{ $app->ward->ward_number }}</td>
                                <td class="p-3 text-slate-700">
                                    {{ app()->getLocale() === 'ne' ? ($app->serviceType->name_ne ?? $app->serviceType->name_en) : ($app->serviceType->name_en ?? $app->serviceType->name_ne) }}
                                </td>
                                <td class="p-3 text-slate-500">{{ $app->created_at->format('M d, Y') }}</td>
                                <td class="p-3">
                                    @if($app->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">{{ __('Approved') }}</span>
                                    @elseif($app->status === 'under_review')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">{{ __('Under Review') }}</span>
                                    @elseif($app->status === 'rejected')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">{{ __('Rejected') }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">{{ __('Submitted') }}</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('staff.applications.show', $app->id) }}" class="text-nepal-blue font-bold hover:underline">
                                        {{ app()->getLocale() === 'ne' ? 'समीक्षा' : 'View' }} &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
