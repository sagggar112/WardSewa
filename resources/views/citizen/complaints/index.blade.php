@extends('layouts.citizen')

@section('title', 'सार्वजनिक गुनासो तथा उजुरीहरू')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">सार्वजनिक गुनासो तथा उजुरीहरू</h1>
            <p class="text-xs text-slate-500 mt-1">सडक, ढल, फोहोरमैला, बिजुली वा वडा कार्यालय सेवा सम्बन्धी समस्या दर्ता गर्नुहोस्।</p>
        </div>
        <a href="{{ route('citizen.complaints.create') }}" class="px-4 py-2.5 bg-nepal-crimson hover:bg-nepal-red text-white text-xs font-bold rounded-lg shadow transition">
            + नयाँ गुनासो दर्ता गर्नुहोस्
        </a>
    </div>

    @if($complaints->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 text-sm">
            कुनै गुनासो दर्ता भएको छैन।
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">टिकट नम्बर</th>
                            <th class="p-3">विधा (Category)</th>
                            <th class="p-3">विषय (Subject)</th>
                            <th class="p-3">मिति</th>
                            <th class="p-3">स्थिति</th>
                            <th class="p-3 text-right">कार्य</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($complaints as $c)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $c->ticket_number }}</td>
                                <td class="p-3 capitalize">{{ $c->category }}</td>
                                <td class="p-3 font-semibold text-slate-800">{{ $c->subject }}</td>
                                <td class="p-3 text-slate-500">{{ $c->created_at->format('M d, Y') }}</td>
                                <td class="p-3">
                                    @if($c->status === 'resolved')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">समाधान भयो</span>
                                    @elseif($c->status === 'in_progress')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">कारबाही हुँदै</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">दर्ता भएको</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('citizen.complaints.show', $c->id) }}" class="text-nepal-blue font-bold hover:underline">
                                        विवरण &rarr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $complaints->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
