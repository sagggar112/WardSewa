<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Dispatch SMS message to the given mobile number using configured provider.
     */
    public function sendSms(string $phone, string $message): bool
    {
        $normalizedPhone = $this->normalizePhone($phone);
        $provider = strtolower(config('services.sms.provider', 'log'));

        Log::info("Dispatching SMS via [{$provider}] to {$normalizedPhone}: \"{$message}\"");

        if ($provider === 'log' || empty($provider)) {
            Log::info("[LOG SMS GATEWAY] Message queued for {$normalizedPhone}: {$message}");
            return true;
        }

        try {
            return match ($provider) {
                'sparrow' => $this->sendViaSparrow($normalizedPhone, $message),
                'aakash' => $this->sendViaAakash($normalizedPhone, $message),
                'twilio' => $this->sendViaTwilio($normalizedPhone, $message),
                'generic' => $this->sendViaGeneric($normalizedPhone, $message),
                default => $this->unrecognizedProvider($provider, $normalizedPhone, $message),
            };
        } catch (\Throwable $e) {
            Log::error("SMS Gateway Exception [{$provider}]: " . $e->getMessage(), [
                'phone' => $normalizedPhone,
                'error_file' => $e->getFile() . ':' . $e->getLine(),
            ]);

            // If SMS failed in development or debug mode, still allow seamless flow
            if (app()->environment('local', 'testing') || config('services.sms.debug', false)) {
                session()->flash('sms_warning', "SMS sending failed via {$provider}: {$e->getMessage()}");
            }

            return false;
        }
    }

    /**
     * Send SMS via Sparrow SMS API (Nepal).
     */
    protected function sendViaSparrow(string $phone, string $message): bool
    {
        $token = config('services.sms.sparrow.token');
        $from = config('services.sms.sparrow.from', 'WardSewa');
        $url = config('services.sms.sparrow.url', 'http://api.sparrowsms.com/v2/sms/');

        if (empty($token)) {
            Log::warning("[Sparrow SMS] Token not configured. Falling back to log.");
            return true;
        }

        $response = Http::timeout(8)->post($url, [
            'token' => $token,
            'from' => $from,
            'to' => $phone,
            'text' => $message,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $code = $data['response_code'] ?? null;
            if ($code == 200 || ($data['response'] ?? '') === 'SMS has been sent successfully.') {
                Log::info("[Sparrow SMS] Sent successfully to {$phone}. Response: " . $response->body());
                return true;
            }

            Log::error("[Sparrow SMS] Error response from gateway: " . $response->body());
            return false;
        }

        Log::error("[Sparrow SMS] HTTP request failed ({$response->status()}): " . $response->body());
        return false;
    }

    /**
     * Send SMS via Aakash SMS API (Nepal).
     */
    protected function sendViaAakash(string $phone, string $message): bool
    {
        $token = config('services.sms.aakash.token');
        $url = config('services.sms.aakash.url', 'https://sms.aakashsms.com/sms/v3/send');

        if (empty($token)) {
            Log::warning("[Aakash SMS] Token not configured. Falling back to log.");
            return true;
        }

        $response = Http::timeout(8)->post($url, [
            'auth_token' => $token,
            'to' => $phone,
            'text' => $message,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['error']) && $data['error'] === false) {
                Log::info("[Aakash SMS] Sent successfully to {$phone}. Response: " . $response->body());
                return true;
            }

            Log::error("[Aakash SMS] Error response from gateway: " . $response->body());
            return false;
        }

        Log::error("[Aakash SMS] HTTP request failed ({$response->status()}): " . $response->body());
        return false;
    }

    /**
     * Send SMS via Twilio (International / Nepal with +977).
     */
    protected function sendViaTwilio(string $phone, string $message): bool
    {
        $sid = config('services.sms.twilio.sid');
        $token = config('services.sms.twilio.token');
        $from = config('services.sms.twilio.from');

        if (empty($sid) || empty($token) || empty($from)) {
            Log::warning("[Twilio] Credentials not configured. Falling back to log.");
            return true;
        }

        $internationalPhone = '+977' . $phone;
        $endpoint = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";

        $response = Http::withBasicAuth($sid, $token)
            ->timeout(8)
            ->asForm()
            ->post($endpoint, [
                'From' => $from,
                'To' => $internationalPhone,
                'Body' => $message,
            ]);

        if ($response->successful()) {
            Log::info("[Twilio] Sent successfully to {$internationalPhone}. SID: " . ($response->json()['sid'] ?? 'ok'));
            return true;
        }

        Log::error("[Twilio] Request failed ({$response->status()}): " . $response->body());
        return false;
    }

    /**
     * Send SMS via Generic HTTP Endpoint.
     */
    protected function sendViaGeneric(string $phone, string $message): bool
    {
        $url = config('services.sms.generic.url');
        $method = strtoupper(config('services.sms.generic.method', 'POST'));
        $token = config('services.sms.generic.token');
        $toField = config('services.sms.generic.to_field', 'to');
        $messageField = config('services.sms.generic.message_field', 'text');

        if (empty($url)) {
            Log::warning("[Generic SMS] Endpoint URL not configured. Falling back to log.");
            return true;
        }

        $client = Http::timeout(8);
        if (!empty($token)) {
            $client = $client->withToken($token);
        }

        $payload = [
            $toField => $phone,
            $messageField => $message,
        ];

        $response = $method === 'GET'
            ? $client->get($url, $payload)
            : $client->post($url, $payload);

        if ($response->successful()) {
            Log::info("[Generic SMS] Sent successfully to {$phone}. Response: " . $response->body());
            return true;
        }

        Log::error("[Generic SMS] Request failed ({$response->status()}): " . $response->body());
        return false;
    }

    /**
     * Fallback for unknown provider.
     */
    protected function unrecognizedProvider(string $provider, string $phone, string $message): bool
    {
        Log::warning("Unrecognized SMS provider [{$provider}], logging SMS to {$phone}: {$message}");
        return true;
    }

    /**
     * Notify citizen of an application status update.
     */
    public function notifyStatusChange(Application $application, string $newStatus, ?string $remarks = null): void
    {
        $phone = $application->citizen->phone;
        $appNo = $application->application_number;
        $serviceName = $application->serviceType->name_ne ?? $application->serviceType->name_en;

        $msg = match ($newStatus) {
            'under_review' => "नमस्ते, तपाईँको निवेदन नं. {$appNo} ({$serviceName}) वडा कार्यालयबाट अध्ययन प्रक्रियामा छ।",
            'documents_requested' => "नमस्ते, निवेदन नं. {$appNo} का लागि थप कागजात आवश्यक परेको छ: '{$remarks}'। कृपया पोर्टलमा अपलोड गर्नुहोस्।",
            'approved' => "बधाई छ! तपाईँको निवेदन नं. {$appNo} ({$serviceName}) स्वीकृत भएको छ। सिफारिस पत्र डाउनलोड गर्न सक्नुहुन्छ।",
            'rejected' => "निवेदन नं. {$appNo} अस्वीकृत भएको छ। कारण: '{$remarks}'। थप जानकारीका लागि वडा कार्यालयमा सम्पर्क गर्नुहोस्।",
            default => "तपाईँको निवेदन नं. {$appNo} को स्थिति '{$newStatus}' मा परिवर्तन भएको छ।",
        };

        // Dispatch status SMS
        $this->sendSms($phone, $msg);
    }

    /**
     * Normalize a Nepali phone number to 10 digits (e.g. 9841234567).
     */
    public function normalizePhone(string $phone): string
    {
        // Strip non-digit characters
        $digits = preg_replace('/[^0-9]/', '', $phone);

        // If prefixed with 977 (Nepal country code) and 13 digits (97798xxxxxxxx)
        if (strlen($digits) === 13 && str_starts_with($digits, '977')) {
            $digits = substr($digits, 3);
        }

        // If prefixed with 0 (e.g. 09841234567)
        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return $digits;
    }
}
