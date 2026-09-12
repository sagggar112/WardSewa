<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Appointment;
use App\Models\Palika;
use App\Models\Ward;
use Illuminate\Http\Request;

class LocalGovtAdminController extends Controller
{
    public function dashboard()
    {
        $staff = auth('staff')->user();
        $palika = $staff->palika ?? Palika::where('code', 'KMC')->first();

        if (!$palika) {
            return redirect()->route('staff.dashboard');
        }

        $wardIds = $palika->wards->pluck('id');

        $stats = [
            'palika_name' => $palika->name_ne . ' (' . $palika->name_en . ')',
            'palika_type' => ucfirst(str_replace('_', ' ', $palika->type)),
            'total_wards' => $palika->wards()->count(),
            'total_ward_admins' => $palika->staff()->whereIn('role', ['ward_admin', 'ward_chair', 'secretary'])->count(),
            'total_applications' => Application::where('palika_id', $palika->id)->count(),
            'pending_applications' => Application::where('palika_id', $palika->id)->whereIn('status', ['submitted', 'under_review', 'documents_requested'])->count(),
            'approved_applications' => Application::where('palika_id', $palika->id)->where('status', 'approved')->count(),
            'rejected_applications' => Application::where('palika_id', $palika->id)->where('status', 'rejected')->count(),
            'total_appointments' => Appointment::where('palika_id', $palika->id)->count(),
            'upcoming_appointments' => Appointment::where('palika_id', $palika->id)->upcoming()->count(),
        ];

        // Wards with application statistics
        $wards = Ward::where('palika_id', $palika->id)
            ->withCount(['applications'])
            ->with(['staff' => function ($q) {
                $q->whereIn('role', ['ward_admin', 'ward_chair', 'secretary']);
            }])
            ->orderBy('ward_number')
            ->get();

        $recentApplications = Application::with(['citizen', 'serviceType', 'ward'])
            ->where('palika_id', $palika->id)
            ->latest()
            ->take(10)
            ->get();

        return view('staff.localgovt.dashboard', compact('staff', 'palika', 'stats', 'wards', 'recentApplications'));
    }

    public function wards()
    {
        $staff = auth('staff')->user();
        $palika = $staff->palika ?? Palika::where('code', 'KMC')->first();

        $wards = Ward::where('palika_id', $palika->id)
            ->withCount(['applications', 'citizens'])
            ->with('staff')
            ->orderBy('ward_number')
            ->paginate(20);

        return view('staff.localgovt.wards', compact('staff', 'palika', 'wards'));
    }

    public function applications(Request $request)
    {
        $staff = auth('staff')->user();
        $palika = $staff->palika ?? Palika::where('code', 'KMC')->first();

        $query = Application::with(['citizen', 'serviceType', 'ward'])
            ->where('palika_id', $palika->id);

        if ($request->filled('ward_id')) {
            $query->where('ward_id', $request->ward_id);
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
        $wards = Ward::where('palika_id', $palika->id)->orderBy('ward_number')->get();

        return view('staff.localgovt.applications', compact('staff', 'palika', 'applications', 'wards'));
    }
}
