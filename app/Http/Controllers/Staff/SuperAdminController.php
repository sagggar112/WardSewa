<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\Citizen;
use App\Models\District;
use App\Models\Palika;
use App\Models\ServiceType;
use App\Models\Staff;
use App\Models\Ward;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_districts' => District::count(),
            'total_palikas' => Palika::count(),
            'total_wards' => Ward::count(),
            'total_citizens' => Citizen::count(),
            'total_services' => ServiceType::count(),
            'total_admins' => Staff::count(),
            'super_admins' => Staff::where('role', 'super_admin')->count(),
            'district_admins' => Staff::where('role', 'district_admin')->count(),
            'local_govt_admins' => Staff::where('role', 'local_government_admin')->count(),
            'ward_admins' => Staff::whereIn('role', ['ward_admin', 'ward_chair', 'secretary', 'clerk'])->count(),
            'total_applications' => Application::count(),
            'pending_applications' => Application::whereIn('status', ['submitted', 'under_review', 'documents_requested'])->count(),
            'approved_applications' => Application::where('status', 'approved')->count(),
            'rejected_applications' => Application::where('status', 'rejected')->count(),
            'total_appointments' => Appointment::count(),
            'upcoming_appointments' => Appointment::upcoming()->count(),
        ];

        // District Breakdown
        $districts = District::withCount(['palikas', 'staff'])
            ->with(['palikas.wards'])
            ->get();

        $recentApplications = Application::with(['citizen', 'serviceType', 'ward.palika'])
            ->latest()
            ->take(10)
            ->get();

        $recentAuditLogs = AuditLog::with('staff')
            ->latest('created_at')
            ->take(8)
            ->get();

        return view('staff.superadmin.dashboard', compact('stats', 'districts', 'recentApplications', 'recentAuditLogs'));
    }

    public function geography()
    {
        $districts = District::with(['province', 'palikas.wards'])
            ->get();

        return view('staff.superadmin.geography.index', compact('districts'));
    }

    public function admins(Request $request)
    {
        $query = Staff::with(['district', 'palika', 'ward']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $admins = $query->latest()->paginate(20);

        return view('staff.superadmin.admins.index', compact('admins'));
    }

    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('staff');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        $logs = $query->latest('created_at')->paginate(25);

        return view('staff.superadmin.audit_logs.index', compact('logs'));
    }
}
