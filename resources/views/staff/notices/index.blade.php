@extends('layouts.staff')

@section('page_title', 'वडा सूचना व्यवस्थापन')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">वडा सूचना व्यवस्थापन</h1>
            <p class="text-xs text-slate-500 mt-0.5">सार्वजनिक सूचना पाटीमा देखिने सूचना तथा सूचना पत्रहरू</p>
        </div>
        <a href="{{ route('staff.notices.create') }}" class="px-4 py-2.5 bg-nepal-darkblue hover:bg-nepal-blue text-white text-xs font-bold rounded-xl shadow transition">
            + नयाँ सूचना प्रकाशित गर्नुहोस्
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        @if($notices->isEmpty())
            <p class="p-8 text-center text-slate-400 text-xs">कुनै सूचना प्रकाशित गरिएको छैन।</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">शीर्षक</th>
                            <th class="p-3">विधा (Category)</th>
                            <th class="p-3">प्रकाशित मिति</th>
                            <th class="p-3 text-right">कार्य</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($notices as $notice)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-bold text-slate-900">{{ $notice->title }}</td>
                                <td class="p-3 capitalize">{{ $notice->category }}</td>
                                <td class="p-3 text-slate-500">{{ $notice->published_at ? $notice->published_at->format('M d, Y') : '-' }}</td>
                                <td class="p-3 text-right">
                                    <form method="POST" action="{{ route('staff.notices.destroy', $notice->id) }}" onsubmit="return confirm('के तपाईँ यो सूचना मेटाउन चाहनुहुन्छ?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold text-xs">मेटाउनुहोस्</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $notices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
