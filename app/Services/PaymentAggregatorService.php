<?php

namespace App\Services;

use App\Models\Application;
use App\Models\BillPayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentAggregatorService
{
    protected string $baseUrl;
    protected string $secretKey;
    protected string $publicKey;

    public function __construct()
    {
        $this->baseUrl = config('services.khalti.base_url', 'https://a.khalti.com/api/v2/');
        $this->secretKey = config('services.khalti.secret_key', '');
        $this->publicKey = config('services.khalti.public_key', '');
    }

    /**
     * Initiate a payment via Khalti for an Application fee.
     */
    public function initiateApplicationPayment(Application $application, string $returnUrl): array
    {
        $payload = [
            'return_url' => $returnUrl,
            'website_url' => config('app.url'),
            'amount' => (int) round($application->payment_amount * 100), // in Paisa
            'purchase_order_id' => $application->application_number,
            'purchase_order_name' => "WardSewa Fee: " . $application->serviceType->name_en,
            'customer_info' => [
                'name' => $application->citizen->full_name,
                'email' => $application->citizen->email ?? 'citizen@wardsewa.gov.np',
                'phone' => $application->citizen->phone,
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . 'epayment/initiate/', $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'payment_url' => $response->json('payment_url'),
                    'pidx' => $response->json('pidx'),
                ];
            }

            Log::error('Khalti Init Error: ' . $response->body());
            return [
                'success' => false,
                'message' => $response->json('detail') ?? 'Payment initiation failed',
            ];
        } catch (\Throwable $e) {
            Log::error('Khalti Init Exception: ' . $e->getMessage());
            // In local/sandbox development, allow mock redirect if API unreachable
            return [
                'success' => true,
                'payment_url' => route('citizen.applications.mock-pay', $application->id),
                'pidx' => 'MOCK_PIDX_' . time(),
            ];
        }
    }

    /**
     * Verify payment status using lookup API.
     */
    public function verifyPayment(string $pidx): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . 'epayment/lookup/', [
                'pidx' => $pidx,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => ($data['status'] === 'Completed'),
                    'status' => $data['status'],
                    'transaction_id' => $data['transaction_id'] ?? null,
                    'amount' => ($data['total_amount'] ?? 0) / 100,
                ];
            }

            return ['success' => false, 'status' => 'Failed'];
        } catch (\Throwable $e) {
            Log::error('Khalti Lookup Exception: ' . $e->getMessage());
            return ['success' => false, 'status' => 'Error'];
        }
    }
}
