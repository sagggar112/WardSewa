<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Citizen;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send OTP code to citizen phone.
     */
    public function sendOtp(string $phone, string $otp): bool
    {
        $message = "[WardSewa] Your verification code is: {$otp}. Valid for 10 minutes.";
        
        // Log OTP in development for ease of testing
        Log::info("OTP SENT to {$phone}: {$otp} | Message: {$message}");

        if (app()->environment('local', 'testing')) {
            session()->flash('dev_otp', $otp);
        }

        // SMS gateway hook (Sparrow SMS / Aakash SMS in production)
        $smsProvider = config('services.sms.provider', 'log');
        if ($smsProvider === 'log') {
            return true;
        }

        // e.g. HTTP POST to Sparrow SMS / Aakash SMS API
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

        Log::info("SMS NOTIFICATION to {$phone}: {$msg}");
    }
}
