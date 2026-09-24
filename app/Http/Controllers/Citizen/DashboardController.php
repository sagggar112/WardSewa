<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Appointment;
use App\Models\Complaint;
use App\Models\Notice;
use App\Models\ServiceType;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Citizen $citizen */
        $citizen = Auth::guard('citizen')->user();
        $citizen->loadMissing(['ward.palika.district.province']);
        
        $stats = [
            'total_applications' => Application::where('citizen_id', $citizen->id)->count(),
            'approved_certificates' => Application::where('citizen_id', $citizen->id)->where('status', 'approved')->count(),
            'pending_reviews' => Application::where('citizen_id', $citizen->id)->whereIn('status', ['submitted', 'under_review', 'documents_requested'])->count(),
            'upcoming_appointments' => Appointment::where('citizen_id', $citizen->id)->where('appointment_date', '>=', now()->toDateString())->count(),
            'open_complaints' => Complaint::where('citizen_id', $citizen->id)->whereIn('status', ['open', 'in_progress'])->count(),
        ];

        // High priority action items (e.g., additional documents requested by ward staff)
        $actionRequiredApplications = Application::with('serviceType')
            ->where('citizen_id', $citizen->id)
            ->where('status', 'documents_requested')
            ->latest()
            ->get();

        $recentApplications = Application::with('serviceType')
            ->where('citizen_id', $citizen->id)
            ->latest()
            ->take(5)
            ->get();

        // Categorized & active services for instant live search/filtering
        $allServices = ServiceType::where('is_active', true)->orderBy('name_en')->get();
        $featuredServices = $allServices->take(6);

        // Upcoming appointments
        $upcomingAppointments = Appointment::with(['serviceType', 'ward.palika'])
            ->where('citizen_id', $citizen->id)
            ->where('appointment_date', '>=', now()->toDateString())
            ->orderBy('appointment_date')
            ->take(3)
            ->get();

        // Ward leadership / officials for the citizen's ward
        $wardOfficials = collect();
        if ($citizen->ward_id) {
            $wardOfficials = Staff::where('ward_id', $citizen->ward_id)
                ->whereIn('role', ['ward_chair', 'secretary', 'clerk'])
                ->orderByRaw("CASE 
                    WHEN role = 'ward_chair' THEN 1 
                    WHEN role = 'secretary' THEN 2 
                    ELSE 3 
                END")
                ->get();
        }

        // Active Ward notices
        $wardNotices = Notice::active()
            ->forWardOrPalika($citizen->ward->palika_id ?? 1, $citizen->ward_id)
            ->latest()
            ->take(5)
            ->get();

        return view('citizen.dashboard', compact(
            'citizen', 
            'stats', 
            'actionRequiredApplications',
            'recentApplications', 
            'allServices',
            'featuredServices', 
            'upcomingAppointments',
            'wardOfficials',
            'wardNotices'
        ));
    }
}
