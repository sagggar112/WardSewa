@extends('layouts.staff')

@section('page_title', $palika->name_ne . ' पालिकाभरका निवेदनहरू')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">{{ $palika->name_ne }} पालिकाभरका निवेदनहरू</h1>
            <p class="text-xs text-slate-500 mt-0.5">यस पालिकाका सम्पूर्ण {{ $palika->wards->count() }} वडा कार्यालयहरूबाट प्राप्त निवेदनहरूको एकीकृत सूची।</p>
        </div>
    </div>

    <!-- Search and Ward Filter -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <form method="GET" action="{{ route('staff.localgovt.applications') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="निवेदन नं., नागरिकको नाम वा फोन..."
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
            </div>

            <div>
                <select name="ward_id" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
                    <option value="">सबै वडाहरू (All Wards)</option>
                    @foreach($wards as $w)
                        <option value="{{ $w->id }}" {{ request('ward_id') == $w->id ? 'selected' : '' }}>
                            वडा नं. {{ $w->ward_number }} ({{ $w->office_address }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
                    <option value="">सबै स्थिति (All Status)</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>दर्ता भएको</option>
                    <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>अध्ययनमा</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>स्वीकृत</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>अस्वीकृत</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Applications Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        @if($applications->isEmpty())
            <p class="p-8 text-center text-slate-400 text-xs">कुनै निवेदन फेला परेन।</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">निवेदन नम्बर</th>
                            <th class="p-3">नागरिकको नाम</th>
                            <th class="p-3">वडा</th>
                            <th class="p-3">सेवाको प्रकार</th>
                            <th class="p-3">मिति</th>
                            <th class="p-3">स्थिति</th>
                            <th class="p-3 text-right">कार्य</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($applications as $app)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $app->application_number }}</td>
                                <td class="p-3 font-semibold text-slate-800">{{ $app->citizen->full_name }}</td>
                                <td class="p-3 font-bold text-slate-700">वडा नं. {{ $app->ward->ward_number }}</td>
                                <td class="p-3 text-slate-700">{{ $app->serviceType->name_ne }}</td>
                                <td class="p-3 text-slate-500">{{ $app->created_at->format('M d, Y') }}</td>
                                <td class="p-3">
                                    @if($app->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">स्वीकृत</span>
                                    @elseif($app->status === 'under_review')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">अध्ययनमा</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">दर्ता</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('staff.applications.show', $app->id) }}" class="text-nepal-blue font-bold hover:underline">
                                        समीक्षा &rarr;
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
