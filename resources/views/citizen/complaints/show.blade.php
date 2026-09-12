@extends('layouts.citizen')

@section('title', 'गुनासो विवरण - ' . $complaint->ticket_number)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <span class="text-xs text-slate-500">टिकट नम्बर:</span>
                <span class="font-mono font-bold text-slate-900 ml-1">{{ $complaint->ticket_number }}</span>
            </div>
            <div>
                @if($complaint->status === 'resolved')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">समाधान भयो</span>
                @elseif($complaint->status === 'in_progress')
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">कारबाही हुँदै</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">दर्ता भएको</span>
                @endif
            </div>
        </div>

        <div class="mt-4 space-y-3 text-sm">
            <h1 class="text-xl font-bold text-slate-900">{{ $complaint->subject }}</h1>
            <div class="text-xs text-slate-500">
                विधा: <span class="font-semibold text-slate-700 capitalize">{{ $complaint->category }}</span> |
                स्थान: <span class="font-semibold text-slate-700">{{ $complaint->location ?? 'खुलाइएको छैन' }}</span> |
                मिति: <span class="font-semibold text-slate-700">{{ $complaint->created_at->format('M d, Y') }}</span>
            </div>

            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-slate-700 whitespace-pre-line text-xs">
                {{ $complaint->description }}
            </div>
        </div>

        @if($complaint->response)
            <div class="mt-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                <span class="text-xs font-bold text-emerald-800 uppercase block">वडा कार्यालयको जवाफ (Official Response):</span>
                <p class="text-xs text-emerald-900 mt-1 whitespace-pre-line">{{ $complaint->response }}</p>
                @if($complaint->resolved_at)
                    <span class="text-[10px] text-emerald-600 block mt-2">समाधान मिति: {{ $complaint->resolved_at->format('Y-m-d H:i') }}</span>
                @endif
            </div>
        @endif
    </div>

    <div class="text-center">
        <a href="{{ route('citizen.complaints.index') }}" class="text-xs text-slate-600 hover:text-slate-900 font-semibold">&larr; गुनासो सूचीमा फर्कनुहोस्</a>
    </div>
</div>
@endsection
