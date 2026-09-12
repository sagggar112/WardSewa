<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationStatusLog;
use App\Models\Document;
use App\Services\NotificationService;
use App\Services\PdfGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    protected NotificationService $notificationService;
    protected PdfGenerator $pdfGenerator;

    public function __construct(NotificationService $notificationService, PdfGenerator $pdfGenerator)
    {
        $this->notificationService = $notificationService;
        $this->pdfGenerator = $pdfGenerator;
    }

    public function index(Request $request)
    {
        $staff = Auth::guard('staff')->user();

        $query = Application::with(['citizen', 'serviceType', 'ward']);

        if ($staff->ward_id) {
            $query->where('ward_id', $staff->ward_id);
        } else {
            $query->where('palika_id', $staff->palika_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('service_type_id')) {
            $query->where('service_type_id', $request->service_type_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                  ->orWhereHas('citizen', function ($cq) use ($search) {
                      $cq->where('full_name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $applications = $query->latest()->paginate(15);

        return view('staff.applications.index', compact('applications', 'staff'));
    }

    public function show($id)
    {
        $staff = Auth::guard('staff')->user();

        $query = Application::with(['citizen', 'serviceType', 'ward.palika', 'documents', 'statusLogs.staff', 'approvedBy']);

        if ($staff->ward_id) {
            $query->where('ward_id', $staff->ward_id);
        } else {
            $query->where('palika_id', $staff->palika_id);
        }

        $application = $query->findOrFail($id);

        return view('staff.applications.show', compact('application', 'staff'));
    }

    public function startReview($id)
    {
        $staff = Auth::guard('staff')->user();
        $application = Application::where('ward_id', $staff->ward_id ?? $staff->palika_id)->findOrFail($id);

        if ($application->status === 'submitted') {
            $application->update([
                'status' => 'under_review',
                'verified_by' => $staff->id,
            ]);

            ApplicationStatusLog::create([
                'application_id' => $application->id,
                'staff_id' => $staff->id,
                'from_status' => 'submitted',
                'to_status' => 'under_review',
                'remarks' => "Review started by {$staff->name} ({$staff->role})",
            ]);

            $this->notificationService->notifyStatusChange($application, 'under_review');
        }

        return back()->with('success', 'Application is now marked as Under Review.');
    }

    public function requestDocuments(Request $request, $id)
    {
        $request->validate([
            'remarks' => ['required', 'string', 'min:5'],
        ]);

        $staff = Auth::guard('staff')->user();
        $application = Application::where('ward_id', $staff->ward_id ?? $staff->palika_id)->findOrFail($id);

        $fromStatus = $application->status;
        $application->update([
            'status' => 'documents_requested',
            'remarks' => $request->remarks,
        ]);

        ApplicationStatusLog::create([
            'application_id' => $application->id,
            'staff_id' => $staff->id,
            'from_status' => $fromStatus,
            'to_status' => 'documents_requested',
            'remarks' => $request->remarks,
        ]);

        $this->notificationService->notifyStatusChange($application, 'documents_requested', $request->remarks);

        return back()->with('success', 'Notification sent to citizen requesting additional documents.');
    }

    public function approve(Request $request, $id)
    {
        $staff = Auth::guard('staff')->user();

        // Only Ward Chair, Secretary or Admin can approve
        if (!$staff->canApproveApplications()) {
            return back()->with('error', 'Only Ward Chair or Secretary has the authority to approve applications.');
        }

        $application = Application::where('ward_id', $staff->ward_id ?? $staff->palika_id)->findOrFail($id);

        DB::beginTransaction();
        try {
            $fromStatus = $application->status;
            $application->update([
                'status' => 'approved',
                'approved_by' => $staff->id,
                'approved_at' => now(),
                'remarks' => $request->input('remarks', 'Approved officially by ' . $staff->name),
            ]);

            ApplicationStatusLog::create([
                'application_id' => $application->id,
                'staff_id' => $staff->id,
                'from_status' => $fromStatus,
                'to_status' => 'approved',
                'remarks' => $request->input('remarks', 'Approved officially with digital stamp and signature.'),
            ]);

            // Generate official recommendation letter PDF with QR Code
            $this->pdfGenerator->generateRecommendationLetter($application);

            $this->notificationService->notifyStatusChange($application, 'approved');

            DB::commit();

            return back()->with('success', 'Application approved successfully! Official recommendation letter with QR code has been generated.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5'],
        ]);

        $staff = Auth::guard('staff')->user();

        if (!$staff->canApproveApplications()) {
            return back()->with('error', 'Only Ward Chair or Secretary has the authority to reject applications.');
        }

        $application = Application::where('ward_id', $staff->ward_id ?? $staff->palika_id)->findOrFail($id);

        $fromStatus = $application->status;
        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        ApplicationStatusLog::create([
            'application_id' => $application->id,
            'staff_id' => $staff->id,
            'from_status' => $fromStatus,
            'to_status' => 'rejected',
            'remarks' => 'Application rejected: ' . $request->rejection_reason,
        ]);

        $this->notificationService->notifyStatusChange($application, 'rejected', $request->rejection_reason);

        return back()->with('success', 'Application rejected.');
    }
}
