<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Complaint;
use App\Models\Notice;
use App\Models\ServiceType;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $citizen = Auth::guard('citizen')->user();
        
        $stats = [
            'total_applications' => Application::where('citizen_id', $citizen->id)->count(),
            'approved_certificates' => Application::where('citizen_id', $citizen->id)->where('status', 'approved')->count(),
            'pending_reviews' => Application::where('citizen_id', $citizen->id)->whereIn('status', ['submitted', 'under_review', 'documents_requested'])->count(),
            'open_complaints' => Complaint::where('citizen_id', $citizen->id)->whereIn('status', ['open', 'in_progress'])->count(),
        ];

        $recentApplications = Application::with('serviceType')
            ->where('citizen_id', $citizen->id)
            ->latest()
            ->take(5)
            ->get();

        $featuredServices = ServiceType::where('is_active', true)
            ->take(6)
            ->get();

        $wardNotices = Notice::active()
            ->forWardOrPalika($citizen->ward->palika_id ?? 1, $citizen->ward_id)
            ->latest()
            ->take(4)
            ->get();

        return view('citizen.dashboard', compact('citizen', 'stats', 'recentApplications', 'featuredServices', 'wardNotices'));
    }
}
