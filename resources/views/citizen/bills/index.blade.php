@extends('layouts.citizen')

@section('title', 'उपयोगिता महसुल भुक्तानी (Utility Bills)')

@section('content')
<div class="space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h1 class="text-2xl font-black text-slate-900">उपयोगिता महसुल तथा वडा कर भुक्तानी</h1>
        <p class="text-xs text-slate-500 mt-1">विद्युत, खानेपानी, इन्टरनेट तथा वडा कर अनलाइन खल्ती वा ई-सेवा मार्फत तत्काल तिर्नुहोस्।</p>
    </div>

    <!-- Biller Directory -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($billers as $biller)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">
                        {{ $biller->category }}
                    </span>
                    <h2 class="text-base font-bold text-slate-900 mt-2">{{ $biller->name_ne }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $biller->name_en }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <a href="{{ route('citizen.bills.show', $biller->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-900 hover:bg-nepal-crimson text-white text-xs font-bold rounded-lg transition">
                        बिल तिर्नुहोस् &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Saved Accounts & Recent Payments -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
        <!-- Saved Accounts -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-900 mb-3">सुरक्षित गरिएका ग्राहक नम्बरहरू</h3>
            @if($savedAccounts->isEmpty())
                <p class="text-xs text-slate-400">कुनै ग्राहक खाता सुरक्षित गरिएको छैन।</p>
            @else
                <div class="space-y-2">
                    @foreach($savedAccounts as $acc)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-slate-900">{{ $acc->biller->name_ne }}</span>
                                <span class="text-slate-500 block">ग्राहक नं: {{ $acc->consumer_id }}</span>
                            </div>
                            <a href="{{ route('citizen.bills.show', $acc->biller_id) }}?consumer_id={{ $acc->consumer_id }}" class="text-nepal-blue font-bold hover:underline">
                                तिर्नुहोस् &rarr;
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-900 mb-3">हालैका भुक्तानीहरू (Recent Payments)</h3>
            @if($recentPayments->isEmpty())
                <p class="text-xs text-slate-400">हालसम्म कुनै महसुल भुक्तानी गरिएको छैन।</p>
            @else
                <div class="space-y-2">
                    @foreach($recentPayments as $pay)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-slate-900">{{ $pay->biller->name_ne }}</span>
                                <span class="text-slate-500 block font-mono">रु. {{ number_format($pay->total_amount, 2) }} | {{ $pay->paid_at->format('M d, Y') }}</span>
                            </div>
                            <a href="{{ route('citizen.bills.receipt', $pay->id) }}" class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded font-semibold text-[11px]">
                                रसिद हेर्नुहोस्
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
