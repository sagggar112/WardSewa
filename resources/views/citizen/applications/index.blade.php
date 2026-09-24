@extends('layouts.citizen')

@section('title', __('My Applications'))

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">{{ __('My Applications') }}</h1>
            <p class="text-xs text-slate-500 mt-1">
                {{ app()->getLocale() === 'ne' ? 'तपाईँले वडा कार्यालयमा पेश गरेका सबै निवेदनको अवस्था ट्रयाक गर्नुहोस्।' : 'Track the real-time status and certificates of all your submitted applications.' }}
            </p>
        </div>
        <a href="{{ route('citizen.applications.create') }}" class="px-4 py-2.5 bg-nepal-red hover:bg-nepal-crimson text-white text-xs font-bold rounded-lg shadow transition">
            {{ __('+ New Application') }}
        </a>
    </div>

    <!-- Filter Tabs (Strict Monolingual) -->
    <div class="flex items-center space-x-2 border-b border-slate-200 overflow-x-auto text-xs font-semibold">
        <a href="{{ route('citizen.applications.index', ['status' => 'all']) }}" class="px-4 py-2.5 {{ (!request('status') || request('status') === 'all') ? 'border-b-2 border-nepal-crimson text-nepal-crimson font-bold' : 'text-slate-500 hover:text-slate-800' }}">
            {{ app()->getLocale() === 'ne' ? 'सबै' : 'All' }}
        </a>
        <a href="{{ route('citizen.applications.index', ['status' => 'submitted']) }}" class="px-4 py-2.5 {{ request('status') === 'submitted' ? 'border-b-2 border-nepal-crimson text-nepal-crimson font-bold' : 'text-slate-500 hover:text-slate-800' }}">
            {{ __('Submitted') }}
        </a>
        <a href="{{ route('citizen.applications.index', ['status' => 'under_review']) }}" class="px-4 py-2.5 {{ request('status') === 'under_review' ? 'border-b-2 border-nepal-crimson text-nepal-crimson font-bold' : 'text-slate-500 hover:text-slate-800' }}">
            {{ __('Under Review') }}
        </a>
        <a href="{{ route('citizen.applications.index', ['status' => 'approved']) }}" class="px-4 py-2.5 {{ request('status') === 'approved' ? 'border-b-2 border-nepal-crimson text-nepal-crimson font-bold' : 'text-slate-500 hover:text-slate-800' }}">
            {{ __('Approved') }}
        </a>
        <a href="{{ route('citizen.applications.index', ['status' => 'rejected']) }}" class="px-4 py-2.5 {{ request('status') === 'rejected' ? 'border-b-2 border-nepal-crimson text-nepal-crimson font-bold' : 'text-slate-500 hover:text-slate-800' }}">
            {{ __('Rejected') }}
        </a>
    </div>

    <!-- Applications List -->
    @if($applications->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 text-sm">
            {{ app()->getLocale() === 'ne' ? 'कुनै निवेदन फेला परेन।' : 'No applications found.' }}
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                        <tr>
                            <th class="p-3">{{ __('Application No') }}</th>
                            <th class="p-3">{{ __('Service') }}</th>
                            <th class="p-3">{{ __('Fee') }}</th>
                            <th class="p-3">{{ __('Date') }}</th>
                            <th class="p-3">{{ __('Status') }}</th>
                            <th class="p-3 text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($applications as $app)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $app->application_number }}</td>
                                <td class="p-3">
                                    <div class="font-bold text-slate-900">
                                        {{ app()->getLocale() === 'ne' ? ($app->serviceType->name_ne ?? $app->serviceType->name_en) : ($app->serviceType->name_en ?? $app->serviceType->name_ne) }}
                                    </div>
                                </td>
                                <td class="p-3">
                                    @if($app->payment_status === 'paid')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            {{ app()->getLocale() === 'ne' ? 'भुक्तानी सम्पन्न' : 'Paid' }}
                                        </span>
                                    @elseif($app->payment_status === 'waived')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                            {{ __('Free') }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                            {{ app()->getLocale() === 'ne' ? 'रु. ' . number_format($app->payment_amount, 0) . ' बाँकी' : 'NPR ' . number_format($app->payment_amount, 0) . ' Due' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3 text-slate-600">{{ $app->created_at->format('Y-m-d') }}</td>
                                <td class="p-3">
                                    @if($app->status === 'approved')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">{{ __('Approved') }}</span>
                                    @elseif($app->status === 'under_review')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">{{ __('Under Review') }}</span>
                                    @elseif($app->status === 'documents_requested')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800">{{ __('Documents Requested') }}</span>
                                    @elseif($app->status === 'rejected')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-100 text-red-800">{{ __('Rejected') }}</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800">{{ __('Submitted') }}</span>
                                    @endif
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('citizen.applications.show', $app->id) }}" class="px-3 py-1.5 bg-slate-900 hover:bg-nepal-crimson text-white rounded text-xs font-semibold transition">
                                        {{ app()->getLocale() === 'ne' ? 'विवरण हेर्नुहोस्' : 'View Details' }} &rarr;
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
        </div>
    @endif
</div>
@endsection
