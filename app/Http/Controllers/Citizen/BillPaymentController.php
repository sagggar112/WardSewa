<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Biller;
use App\Models\BillPayment;
use App\Models\CitizenBillAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BillPaymentController extends Controller
{
    public function index()
    {
        $citizen = Auth::guard('citizen')->user();
        $billers = Biller::where('is_active', true)->get();
        $savedAccounts = CitizenBillAccount::with('biller')
            ->where('citizen_id', $citizen->id)
            ->get();
        $recentPayments = BillPayment::with('biller')
            ->where('citizen_id', $citizen->id)
            ->latest()
            ->take(5)
            ->get();

        return view('citizen.bills.index', compact('billers', 'savedAccounts', 'recentPayments'));
    }

    public function showBiller($id)
    {
        $biller = Biller::findOrFail($id);
        $citizen = Auth::guard('citizen')->user();

        return view('citizen.bills.show', compact('biller', 'citizen'));
    }

    public function processPayment(Request $request, $id)
    {
        $citizen = Auth::guard('citizen')->user();
        $biller = Biller::findOrFail($id);

        $request->validate([
            'consumer_id' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:10'],
            'save_account' => ['nullable', 'boolean'],
        ]);

        $amount = (float) $request->amount;
        $serviceCharge = 5.00;
        $totalAmount = $amount + $serviceCharge;

        $txnId = 'TXN-' . strtoupper(Str::random(10));

        $payment = BillPayment::create([
            'transaction_id' => $txnId,
            'citizen_id' => $citizen->id,
            'biller_id' => $biller->id,
            'consumer_id' => $request->consumer_id,
            'amount' => $amount,
            'service_charge' => $serviceCharge,
            'total_amount' => $totalAmount,
            'payment_gateway' => 'khalti_sandbox',
            'gateway_ref_id' => 'KHL-' . rand(100000, 999999),
            'status' => 'success',
            'paid_at' => now(),
        ]);

        if ($request->boolean('save_account')) {
            CitizenBillAccount::firstOrCreate(
                [
                    'citizen_id' => $citizen->id,
                    'biller_id' => $biller->id,
                    'consumer_id' => $request->consumer_id,
                ],
                [
                    'account_holder_name' => $citizen->full_name,
                    'nickname' => $biller->name_en . ' - ' . $request->consumer_id,
                ]
            );
        }

        return redirect()->route('citizen.bills.receipt', $payment->id)
            ->with('success', 'Utility bill paid successfully!');
    }

    public function receipt($id)
    {
        $citizen = Auth::guard('citizen')->user();
        $payment = BillPayment::with('biller')
            ->where('citizen_id', $citizen->id)
            ->findOrFail($id);

        return view('citizen.bills.receipt', compact('payment'));
    }
}
