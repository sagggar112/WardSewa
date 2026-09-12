<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationStatusLog;
use App\Models\Document;
use App\Models\ServiceType;
use App\Services\PaymentAggregatorService;
use App\Services\PdfGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    protected PaymentAggregatorService $paymentService;
    protected PdfGenerator $pdfGenerator;

    public function __construct(PaymentAggregatorService $paymentService, PdfGenerator $pdfGenerator)
    {
        $this->paymentService = $paymentService;
        $this->pdfGenerator = $pdfGenerator;
    }

    public function index(Request $request)
    {
        $citizen = Auth::guard('citizen')->user();
        
        $query = Application::with('serviceType')
            ->where('citizen_id', $citizen->id);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(10);

        return view('citizen.applications.index', compact('applications'));
    }

    public function create(Request $request)
    {
        $citizen = Auth::guard('citizen')->user();
        $selectedCode = $request->query('service');

        $serviceTypes = ServiceType::where('is_active', true)->get();
        $selectedService = $serviceTypes->firstWhere('code', $selectedCode) ?? $serviceTypes->first();

        return view('citizen.applications.create', compact('citizen', 'serviceTypes', 'selectedService'));
    }

    public function store(Request $request)
    {
        $citizen = Auth::guard('citizen')->user();

        $request->validate([
            'service_type_id' => ['required', 'exists:service_types,id'],
            'form_data' => ['required', 'array'],
        ]);

        $serviceType = ServiceType::findOrFail($request->service_type_id);

        // Generate application number: WS-2081-XXXX
        $nepaliYear = 2081; // Current Nepali Bikram Sambat year
        $count = Application::whereYear('created_at', now()->year)->count() + 1;
        $appNumber = sprintf('WS-%d-%04d', $nepaliYear, $count);

        DB::beginTransaction();
        try {
            $application = Application::create([
                'application_number' => $appNumber,
                'citizen_id' => $citizen->id,
                'service_type_id' => $serviceType->id,
                'palika_id' => $citizen->ward->palika_id ?? 1,
                'ward_id' => $citizen->ward_id,
                'form_data' => $request->form_data,
                'status' => 'submitted',
                'payment_status' => ($serviceType->fee > 0) ? 'unpaid' : 'waived',
                'payment_amount' => $serviceType->fee,
                'qr_code_token' => (string) Str::uuid(),
            ]);

            // Save status log
            ApplicationStatusLog::create([
                'application_id' => $application->id,
                'staff_id' => null,
                'from_status' => 'draft',
                'to_status' => 'submitted',
                'remarks' => 'Application submitted by citizen.',
            ]);

            // Handle file attachments
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $key => $file) {
                    if ($file->isValid()) {
                        $filename = time() . '_' . Str::slug($file->getClientOriginalName(), '_') . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('documents/' . $application->id, $filename, 'public');

                        Document::create([
                            'application_id' => $application->id,
                            'document_type' => $key,
                            'file_path' => $path,
                            'original_filename' => $file->getClientOriginalName(),
                            'file_size' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                            'verification_status' => 'pending',
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('citizen.applications.show', $application->id)
                ->with('success', "Your application {$appNumber} has been successfully submitted to Ward {$citizen->ward->ward_number}!");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to submit application: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $citizen = Auth::guard('citizen')->user();
        
        $application = Application::with(['serviceType', 'ward.palika', 'documents', 'statusLogs.staff', 'approvedBy'])
            ->where('citizen_id', $citizen->id)
            ->findOrFail($id);

        return view('citizen.applications.show', compact('application'));
    }

    public function initiatePayment($id)
    {
        $citizen = Auth::guard('citizen')->user();
        $application = Application::where('citizen_id', $citizen->id)->findOrFail($id);

        if ($application->payment_status === 'paid') {
            return back()->with('info', 'This application has already been paid for.');
        }

        $returnUrl = route('citizen.applications.payment-callback', $application->id);
        $result = $this->paymentService->initiateApplicationPayment($application, $returnUrl);

        if ($result['success'] && !empty($result['payment_url'])) {
            return redirect($result['payment_url']);
        }

        return back()->with('error', $result['message'] ?? 'Could not initiate payment. Please try again.');
    }

    public function paymentCallback(Request $request, $id)
    {
        $citizen = Auth::guard('citizen')->user();
        $application = Application::where('citizen_id', $citizen->id)->findOrFail($id);

        $pidx = $request->get('pidx');
        if ($pidx) {
            $verification = $this->paymentService->verifyPayment($pidx);
            if ($verification['success']) {
                $application->update([
                    'payment_status' => 'paid',
                    'payment_method' => 'khalti',
                    'khalti_transaction_id' => $verification['transaction_id'] ?? $pidx,
                ]);

                return redirect()->route('citizen.applications.show', $application->id)
                    ->with('success', 'Payment verified successfully! Thank you.');
            }
        }

        return redirect()->route('citizen.applications.show', $application->id)
            ->with('error', 'Payment verification failed or was cancelled.');
    }

    // Mock payment for instant testing in local environment
    public function mockPay($id)
    {
        $citizen = Auth::guard('citizen')->user();
        $application = Application::where('citizen_id', $citizen->id)->findOrFail($id);

        $application->update([
            'payment_status' => 'paid',
            'payment_method' => 'khalti_sandbox',
            'khalti_transaction_id' => 'MOCK_TXN_' . strtoupper(Str::random(8)),
        ]);

        return redirect()->route('citizen.applications.show', $application->id)
            ->with('success', 'Test payment successful! Application fee marked as paid.');
    }

    public function downloadCertificate($id)
    {
        $citizen = Auth::guard('citizen')->user();
        $application = Application::where('citizen_id', $citizen->id)
            ->where('status', 'approved')
            ->findOrFail($id);

        // If certificate file doesn't exist yet, generate it now
        if (!$application->certificate_path || !Storage::disk('public')->exists($application->certificate_path)) {
            $this->pdfGenerator->generateRecommendationLetter($application);
            $application->refresh();
        }

        $filePath = Storage::disk('public')->path($application->certificate_path);

        if (file_exists($filePath)) {
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $application->application_number . '.pdf"',
            ]);
        }

        return back()->with('error', 'Certificate file could not be found.');
    }
}
