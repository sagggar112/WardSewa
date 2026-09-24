@extends('layouts.citizen')

@section('title', __('Utility Bills'))

@section('content')
<div class="space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-black text-slate-900">{{ __('Utility Bills') }}</h1>
        <p class="text-xs text-slate-500 mt-1">
            {{ app()->getLocale() === 'ne' ? 'विद्युत, खानेपानी, इन्टरनेट तथा वडा कर अनलाइन तत्काल तिर्नुहोस्।' : 'Pay electricity, drinking water, internet, and municipal utility bills instantly online.' }}
        </p>
    </div>

    <!-- Biller Directory -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($billers as $biller)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">
                        {{ $biller->category }}
                    </span>
                    <h2 class="text-base font-bold text-slate-900 mt-2">
                        {{ app()->getLocale() === 'ne' ? ($biller->name_ne ?? $biller->name_en) : ($biller->name_en ?? $biller->name_ne) }}
                    </h2>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <a href="{{ route('citizen.bills.show', $biller->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-900 hover:bg-nepal-crimson text-white text-xs font-bold rounded-lg transition">
                        {{ app()->getLocale() === 'ne' ? 'बिल तिर्नुहोस्' : 'Pay Bill' }} &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Saved Accounts & Recent Payments -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
        <!-- Saved Accounts -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-900 mb-3">
                {{ app()->getLocale() === 'ne' ? 'सुरक्षित गरिएका ग्राहक नम्बरहरू' : 'Saved Customer Accounts' }}
            </h3>
            @if($savedAccounts->isEmpty())
                <p class="text-xs text-slate-400">
                    {{ app()->getLocale() === 'ne' ? 'कुनै ग्राहक खाता सुरक्षित गरिएको छैन।' : 'No saved consumer accounts found.' }}
                </p>
            @else
                <div class="space-y-2">
                    @foreach($savedAccounts as $acc)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-slate-900">
                                    {{ app()->getLocale() === 'ne' ? ($acc->biller->name_ne ?? $acc->biller->name_en) : ($acc->biller->name_en ?? $acc->biller->name_ne) }}
                                </span>
                                <span class="text-slate-500 block">
                                    {{ app()->getLocale() === 'ne' ? 'ग्राहक नं:' : 'Consumer ID:' }} {{ $acc->consumer_id }}
                                </span>
                            </div>
                            <a href="{{ route('citizen.bills.show', $acc->biller_id) }}?consumer_id={{ $acc->consumer_id }}" class="text-nepal-blue font-bold hover:underline">
                                {{ app()->getLocale() === 'ne' ? 'तिर्नुहोस्' : 'Pay' }} &rarr;
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-900 mb-3">
                {{ app()->getLocale() === 'ne' ? 'हालैका भुक्तानीहरू' : 'Recent Payments' }}
            </h3>
            @if($recentPayments->isEmpty())
                <p class="text-xs text-slate-400">
                    {{ app()->getLocale() === 'ne' ? 'हालसम्म कुनै महसुल भुक्तानी गरिएको छैन।' : 'No bill payments recorded yet.' }}
                </p>
            @else
                <div class="space-y-2">
                    @foreach($recentPayments as $pay)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-slate-900">
                                    {{ app()->getLocale() === 'ne' ? ($pay->biller->name_ne ?? $pay->biller->name_en) : ($pay->biller->name_en ?? $pay->biller->name_ne) }}
                                </span>
                                <span class="text-slate-500 block font-mono">
                                    {{ app()->getLocale() === 'ne' ? 'रु. ' . number_format($pay->total_amount, 2) : 'NPR ' . number_format($pay->total_amount, 2) }} | {{ $pay->paid_at->format('M d, Y') }}
                                </span>
                            </div>
                            <a href="{{ route('citizen.bills.receipt', $pay->id) }}" class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded font-semibold text-[11px]">
                                {{ app()->getLocale() === 'ne' ? 'रसिद हेर्नुहोस्' : 'View Receipt' }}
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
