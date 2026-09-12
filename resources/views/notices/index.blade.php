@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">{{ __('Ward & Municipality Notice Board') }}</h1>
        <p class="text-slate-600 text-sm mt-1">{{ __('Latest public notices, tax rebates, and municipal development information for citizens.') }}</p>
    </div>

    @if($notices->isEmpty())
        <div class="bg-white rounded-xl border border-slate-200 p-8 text-center text-slate-500">
            {{ __('No new notices published at this moment.') }}
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($notices as $notice)
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                {{ ucfirst($notice->category) }}
                            </span>
                            <span class="text-xs text-slate-400">
                                {{ $notice->published_at ? $notice->published_at->format('M d, Y') : '' }}
                            </span>
                        </div>

                        <h2 class="text-lg font-bold text-slate-900 mb-2 leading-snug">{{ $notice->title }}</h2>
                        <p class="text-sm text-slate-600 whitespace-pre-line line-clamp-4">{{ $notice->content }}</p>
                    </div>

                    @if($notice->attachment_path)
                        <div class="mt-4 pt-3 border-t border-slate-100">
                            <a href="{{ Storage::url($notice->attachment_path) }}" target="_blank" class="inline-flex items-center space-x-1 text-xs text-nepal-blue font-semibold hover:underline">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                <span>{{ __('Download Attachment') }}</span>
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $notices->links() }}
        </div>
    @endif
</div>
@endsection
