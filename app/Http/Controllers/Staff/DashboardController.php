<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Appointment;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $staff = Auth::guard('staff')->user();

        // 1. Role-based routing to dedicated Tier dashboards
        if ($staff->isSuperAdmin()) {
            return redirect()->route('staff.superadmin.dashboard');
        }

        if ($staff->isDistrictAdmin()) {
            return redirect()->route('staff.district.dashboard');
        }

        if ($staff->isLocalGovtAdmin()) {
            return redirect()->route('staff.localgovt.dashboard');
        }

        // 2. Operational Ward Admin / Staff Dashboard (Tier 4)
        $appQuery = Application::query();
        $complaintQuery = Complaint::query();
        $appointmentQuery = Appointment::query();

        if ($staff->ward_id) {
            $appQuery->where('ward_id', $staff->ward_id);
            $complaintQuery->where('ward_id', $staff->ward_id);
            $appointmentQuery->where('ward_id', $staff->ward_id);
        } else {
            $appQuery->where('palika_id', $staff->palika_id);
            $complaintQuery->where('palika_id', $staff->palika_id);
            $appointmentQuery->where('palika_id', $staff->palika_id);
        }

        $stats = [
            'submitted' => (clone $appQuery)->where('status', 'submitted')->count(),
            'under_review' => (clone $appQuery)->where('status', 'under_review')->count(),
            'documents_requested' => (clone $appQuery)->where('status', 'documents_requested')->count(),
            'approved' => (clone $appQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $appQuery)->where('status', 'rejected')->count(),
            'open_complaints' => (clone $complaintQuery)->whereIn('status', ['open', 'in_progress'])->count(),
            'today_appointments' => (clone $appointmentQuery)->today()->count(),
            'upcoming_appointments' => (clone $appointmentQuery)->upcoming()->count(),
        ];

        $recentApplications = (clone $appQuery)
            ->with(['citizen', 'serviceType', 'ward'])
            ->latest()
            ->take(10)
            ->get();

        $todayAppointments = (clone $appointmentQuery)
            ->with(['citizen', 'serviceType'])
            ->today()
            ->take(10)
            ->get();

        $teamStats = [
            'total' => $staff->ward_id ? \App\Models\Staff::where('ward_id', $staff->ward_id)->where('is_active', true)->count() : 0,
            'secretaries' => $staff->ward_id ? \App\Models\Staff::where('ward_id', $staff->ward_id)->where('role', 'secretary')->where('is_active', true)->count() : 0,
            'clerks' => $staff->ward_id ? \App\Models\Staff::where('ward_id', $staff->ward_id)->where('role', 'clerk')->where('is_active', true)->count() : 0,
        ];

        return view('staff.dashboard', compact('staff', 'stats', 'recentApplications', 'todayAppointments', 'teamStats'));
    }
}
