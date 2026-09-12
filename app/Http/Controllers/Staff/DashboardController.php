<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $staff = Auth::guard('staff')->user();

        // Scope queries by ward or palika
        $appQuery = Application::query();
        $complaintQuery = Complaint::query();

        if ($staff->ward_id) {
            $appQuery->where('ward_id', $staff->ward_id);
            $complaintQuery->where('ward_id', $staff->ward_id);
        } else {
            $appQuery->where('palika_id', $staff->palika_id);
            $complaintQuery->where('palika_id', $staff->palika_id);
        }

        $stats = [
            'submitted' => (clone $appQuery)->where('status', 'submitted')->count(),
            'under_review' => (clone $appQuery)->where('status', 'under_review')->count(),
            'documents_requested' => (clone $appQuery)->where('status', 'documents_requested')->count(),
            'approved' => (clone $appQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $appQuery)->where('status', 'rejected')->count(),
            'open_complaints' => (clone $complaintQuery)->whereIn('status', ['open', 'in_progress'])->count(),
        ];

        $recentApplications = (clone $appQuery)
            ->with(['citizen', 'serviceType', 'ward'])
            ->latest()
            ->take(10)
            ->get();

        return view('staff.dashboard', compact('staff', 'stats', 'recentApplications'));
    }
}
