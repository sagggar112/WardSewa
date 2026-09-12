@extends('layouts.staff')

@section('page_title', 'निवेदन तथा सिफारिस व्यवस्थापन')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">नागरिक निवेदन सूची</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ $staff->ward ? ($staff->ward->palika->name_ne . ' - वडा नं. ' . $staff->ward->ward_number) : 'पालिका स्तर' }} का सबै निवेदनहरू
            </p>
        </div>
    </div>

    <!-- Search and Filters Form -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <form method="GET" action="{{ route('staff.applications.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div class="sm:col-span-2">
                <label class="block text-slate-600 font-bold mb-1 uppercase">खोज्नुहोस् (Search)</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="निवेदन नं., नागरिकको नाम वा मोबाइल..."
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
            </div>

            <div>
                <label class="block text-slate-600 font-bold mb-1 uppercase">स्थिति (Status)</label>
                <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs">
                    <option value="">सबै स्थिति</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>दर्ता भएको (Submitted)</option>
                    <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>अध्ययनमा (Under Review)</option>
                    <option value="documents_requested" {{ request('status') === 'documents_requested' ? 'selected' : '' }}>कागजात मागिएको</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>स्वीकृत (Approved)</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>अस्वीकृत (Rejected)</option>
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-nepal-darkblue text-white font-bold rounded-lg transition">
                    फिल्टर गर्नुहोस्
                </button>
                <a href="{{ route('staff.applications.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg font-semibold text-center">
                    रिसेट
                </a>
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
                            <th class="p-3">नागरिकको नाम / फोन</th>
                            <th class="p-3">सेवाको प्रकार</th>
                            <th class="p-3">दस्तुर</th>
                            <th class="p-3">मिति</th>
                            <th class="p-3">स्थिति</th>
                            <th class="p-3 text-right">कार्य</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($applications as $app)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $app->application_number }}</td>
                                <td class="p-3">
                                    <div class="font-bold text-slate-900">{{ $app->citizen->full_name }}</div>
                                    <div class="font-mono text-slate-500 text-[11px]">{{ $app->citizen->phone }}</div>
                                </td>
                                <td class="p-3 text-slate-800 font-medium">{{ $app->serviceType->name_ne }}</td>
                                <td class="p-3">
                                    @if($app->payment_status === 'paid')
                                        <span class="text-emerald-700 font-bold">भुक्तान भयो</span>
                                    @elseif($app->payment_status === 'waived')
                                        <span class="text-slate-500">निःशुल्क</span>
                                    @else
                                        <span class="text-amber-700 font-bold">रु. {{ number_format($app->payment_amount, 0) }} बाँकी</span>
                                    @endif
                                </td>
                                <td class="p-3 text-slate-500">{{ $app->created_at->format('M d, Y') }}</td>
                                <td class="p-3">
                                    @if($app->status === 'approved')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">स्वीकृत</span>
                                    @elseif($app->status === 'under_review')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">अध्ययनमा</span>
                                    @elseif($app->status === 'documents_requested')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">कागजात मागिएको</span>
                                    @elseif($app->status === 'rejected')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">अस्वीकृत</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">दर्ता भएको</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('staff.applications.show', $app->id) }}" class="px-3 py-1.5 bg-slate-900 hover:bg-nepal-darkblue text-white rounded text-xs font-semibold transition">
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
