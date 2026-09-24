@extends('layouts.citizen')

@section('title', (app()->getLocale() === 'ne' ? 'महसुल भुक्तानी रसिद' : 'Payment Receipt') . ' - ' . $payment->transaction_id)

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-6 overflow-hidden">
        <div class="text-center pb-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="text-xl font-black text-slate-900">{{ app()->getLocale() === 'ne' ? 'महसुल भुक्तानी रसिद' : 'Utility Payment Receipt' }}</h1>
            <p class="text-xs text-emerald-600 font-semibold mt-0.5">{{ app()->getLocale() === 'ne' ? 'सफलतापूर्वक भुक्तानी सम्पन्न भयो' : 'Payment completed successfully' }}</p>
        </div>

        <div class="py-4 space-y-3 text-xs border-b border-slate-100">
            <div class="flex justify-between">
                <span class="text-slate-500">{{ app()->getLocale() === 'ne' ? 'कारोबार नम्बर:' : 'Transaction ID:' }}</span>
                <span class="font-mono font-bold text-slate-900">{{ $payment->transaction_id }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">{{ app()->getLocale() === 'ne' ? 'सेवा प्रदायक:' : 'Biller:' }}</span>
                <span class="font-bold text-slate-900">
                    {{ app()->getLocale() === 'ne' ? ($payment->biller->name_ne ?? $payment->biller->name_en) : ($payment->biller->name_en ?? $payment->biller->name_ne) }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">{{ app()->getLocale() === 'ne' ? 'ग्राहक / उपभोक्ता नं:' : 'Consumer / Account ID:' }}</span>
                <span class="font-mono font-bold text-slate-900">{{ $payment->consumer_id }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">{{ app()->getLocale() === 'ne' ? 'भुक्तानी माध्यम:' : 'Payment Method:' }}</span>
                <span class="font-semibold text-purple-700 uppercase">{{ $payment->payment_gateway }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">{{ app()->getLocale() === 'ne' ? 'मिति तथा समय:' : 'Date & Time:' }}</span>
                <span class="text-slate-900">{{ $payment->paid_at->format('Y-m-d H:i:s') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">{{ app()->getLocale() === 'ne' ? 'रकम:' : 'Amount:' }}</span>
                <span class="font-mono text-slate-900">{{ app()->getLocale() === 'ne' ? 'रु. ' : 'NPR ' }}{{ number_format($payment->amount, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">{{ app()->getLocale() === 'ne' ? 'सेवा शुल्क:' : 'Service Fee:' }}</span>
                <span class="font-mono text-slate-900">{{ app()->getLocale() === 'ne' ? 'रु. ' : 'NPR ' }}{{ number_format($payment->service_charge, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm font-bold pt-2 border-t border-slate-100 text-slate-900">
                <span>{{ app()->getLocale() === 'ne' ? 'कुल भुक्तानी:' : 'Total Paid:' }}</span>
                <span class="font-mono text-nepal-crimson text-base">{{ app()->getLocale() === 'ne' ? 'रु. ' : 'NPR ' }}{{ number_format($payment->total_amount, 2) }}</span>
            </div>
        </div>

        <div class="pt-6 flex items-center justify-between">
            <a href="{{ route('citizen.bills.index') }}" class="text-xs text-slate-600 hover:underline">&larr; {{ app()->getLocale() === 'ne' ? 'बिल सूचीमा फर्कनुहोस्' : 'Back to Utility Bills' }}</a>
            <button onclick="window.print()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-semibold">
                {{ app()->getLocale() === 'ne' ? 'रसिद प्रिन्ट गर्नुहोस्' : 'Print Receipt' }}
            </button>
        </div>
    </div>
</div>
@endsection
