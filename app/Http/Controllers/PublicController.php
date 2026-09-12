<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Notice;
use App\Models\ServiceType;
use App\Models\Ward;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $services = ServiceType::where('is_active', true)->get();
        $notices = Notice::active()->latest()->take(5)->get();
        $pilotWard = Ward::with('palika.district')->where('ward_number', 32)->first();

        return view('welcome', compact('services', 'notices', 'pilotWard'));
    }

    public function notices()
    {
        $notices = Notice::active()->latest()->paginate(12);
        return view('notices.index', compact('notices'));
    }

    public function verifyCertificate(string $token)
    {
        $application = Application::with(['citizen', 'serviceType', 'ward.palika', 'approvedBy'])
            ->where('qr_code_token', $token)
            ->where('status', 'approved')
            ->first();

        return view('verify', compact('application', 'token'));
    }
}
