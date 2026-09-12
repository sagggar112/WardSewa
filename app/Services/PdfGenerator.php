<?php

namespace App\Services;

use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PdfGenerator
{
    /**
     * Generate an official recommendation letter or certificate PDF for an approved application.
     */
    public function generateRecommendationLetter(Application $application): string
    {
        // 1. Ensure QR Code verification token exists
        if (!$application->qr_code_token) {
            $application->qr_code_token = (string) Str::uuid();
            $application->save();
        }

        $verificationUrl = url("/verify/{$application->qr_code_token}");

        // 2. Generate Base64 QR code svg/png
        $qrCodeImage = null;
        try {
            if (class_exists(QrCode::class)) {
                $qrCodeSvg = QrCode::format('svg')->size(120)->generate($verificationUrl);
                $qrCodeImage = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
            }
        } catch (\Throwable $e) {
            \Log::warning('QrCode generation failed: ' . $e->getMessage());
        }

        // 3. Render HTML view with application, ward, palika, staff details
        $data = [
            'application' => $application,
            'ward' => $application->ward,
            'palika' => $application->palika,
            'citizen' => $application->citizen,
            'serviceType' => $application->serviceType,
            'approver' => $application->approvedBy,
            'verificationUrl' => $verificationUrl,
            'qrCodeImage' => $qrCodeImage,
            'issuedDateBs' => now()->format('Y-m-d'), // Can be converted to BS
            'issuedDateAd' => now()->format('d M, Y'),
        ];

        $filename = 'certificates/' . $application->application_number . '.pdf';
        
        try {
            if (class_exists(Pdf::class)) {
                $pdf = Pdf::loadView('pdf.recommendation_letter', $data)
                    ->setPaper('a4', 'portrait')
                    ->setOption('isHtml5ParserEnabled', true)
                    ->setOption('isRemoteEnabled', true);

                $pdfContent = $pdf->output();
                Storage::disk('public')->put($filename, $pdfContent);
            } else {
                // Fallback html storage if dompdf is not yet loaded in current PHP env
                $html = view('pdf.recommendation_letter', $data)->render();
                Storage::disk('public')->put($filename . '.html', $html);
            }
        } catch (\Throwable $e) {
            \Log::error('PDF generation error: ' . $e->getMessage());
            $html = view('pdf.recommendation_letter', $data)->render();
            Storage::disk('public')->put($filename . '.html', $html);
        }

        $application->certificate_path = $filename;
        $application->save();

        return $filename;
    }
}
