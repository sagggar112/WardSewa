@extends('layouts.staff')

@section('page_title', __('Notice Management'))

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">{{ __('Notice Management') }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ app()->getLocale() === 'ne' ? 'सार्वजनिक सूचना पाटीमा देखिने सूचना तथा सूचना पत्रहरू' : 'Official public notices and circulars for citizen portal notice board.' }}
            </p>
        </div>
        <a href="{{ route('staff.notices.create') }}" class="px-4 py-2.5 bg-nepal-darkblue hover:bg-nepal-blue text-white text-xs font-bold rounded-xl shadow transition">
            + {{ app()->getLocale() === 'ne' ? 'नयाँ सूचना प्रकाशित गर्नुहोस्' : 'Publish New Notice' }}
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        @if($notices->isEmpty())
            <p class="p-8 text-center text-slate-400 text-xs">
                {{ __('No new notices published at this moment.') }}
            </p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">{{ app()->getLocale() === 'ne' ? 'शीर्षक' : 'Title' }}</th>
                            <th class="p-3">{{ app()->getLocale() === 'ne' ? 'विधा' : 'Category' }}</th>
                            <th class="p-3">{{ __('Date') }}</th>
                            <th class="p-3 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($notices as $notice)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-bold text-slate-900">{{ $notice->title }}</td>
                                <td class="p-3 capitalize">{{ $notice->category }}</td>
                                <td class="p-3 text-slate-500">{{ $notice->published_at ? $notice->published_at->format('M d, Y') : '-' }}</td>
                                <td class="p-3 text-right">
                                    <form method="POST" action="{{ route('staff.notices.destroy', $notice->id) }}" onsubmit="return confirm('{{ app()->getLocale() === 'ne' ? 'के तपाईँ यो सूचना मेटाउन चाहनुहुन्छ?' : 'Are you sure you want to delete this notice?' }}');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold text-xs">
                                            {{ app()->getLocale() === 'ne' ? 'मेटाउनुहोस्' : 'Delete' }}
                                        </button>
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
