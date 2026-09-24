<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Appointment;
use App\Models\District;
use App\Models\Palika;
use App\Models\Ward;
use Illuminate\Http\Request;

class DistrictAdminController extends Controller
{
    public function dashboard()
    {
        $staff = auth('staff')->user();
        $district = $staff->district ?? District::first();

        if (!$district) {
            return redirect()->route('staff.dashboard');
        }

        $palikaIds = $district->palikas->pluck('id');
        $wardIds = Ward::whereIn('palika_id', $palikaIds)->pluck('id');

        $stats = [
            'district_name' => $district->name_ne . ' (' . $district->name_en . ')',
            'total_palikas' => $district->palikas()->count(),
            'total_wards' => $wardIds->count(),
            'total_applications' => Application::whereIn('palika_id', $palikaIds)->count(),
            'pending_applications' => Application::whereIn('palika_id', $palikaIds)->whereIn('status', ['submitted', 'under_review', 'documents_requested'])->count(),
            'approved_applications' => Application::whereIn('palika_id', $palikaIds)->where('status', 'approved')->count(),
            'rejected_applications' => Application::whereIn('palika_id', $palikaIds)->where('status', 'rejected')->count(),
            'total_appointments' => Appointment::whereIn('palika_id', $palikaIds)->count(),
            'upcoming_appointments' => Appointment::whereIn('palika_id', $palikaIds)->upcoming()->count(),
        ];

        // Palikas breakdown with application stats
        $palikas = Palika::where('district_id', $district->id)
            ->withCount(['wards', 'applications'])
            ->with(['staff' => function ($q) {
                $q->where('role', 'local_government_admin');
            }])
            ->get();

        $recentApplications = Application::with(['citizen', 'serviceType', 'ward.palika'])
            ->whereIn('palika_id', $palikaIds)
            ->latest()
            ->take(10)
            ->get();

        return view('staff.district.dashboard', compact('staff', 'district', 'stats', 'palikas', 'recentApplications'));
    }

    public function palikas()
    {
        $staff = auth('staff')->user();
        $district = $staff->district ?? District::first();

        $palikas = Palika::where('district_id', $district->id)
            ->withCount(['wards', 'applications', 'staff'])
            ->with(['wards', 'staff'])
            ->get();

        return view('staff.district.palikas', compact('staff', 'district', 'palikas'));
    }

    public function applications(Request $request)
    {
        $staff = auth('staff')->user();
        $district = $staff->district ?? District::first();
        $palikaIds = $district->palikas->pluck('id');

        $query = Application::with(['citizen', 'serviceType', 'ward.palika'])
            ->whereIn('palika_id', $palikaIds);

        if ($request->filled('palika_id')) {
            $query->where('palika_id', $request->palika_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
        $palikas = Palika::where('district_id', $district->id)->get();

        return view('staff.district.applications', compact('staff', 'district', 'applications', 'palikas'));
    }
}
