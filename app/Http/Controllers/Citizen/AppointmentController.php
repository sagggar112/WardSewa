<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $citizen = Auth::guard('citizen')->user();
        $appointments = Appointment::with(['serviceType', 'ward.palika'])
            ->where('citizen_id', $citizen->id)
            ->latest('appointment_date')
            ->paginate(10);

        return view('citizen.appointments.index', compact('citizen', 'appointments'));
    }

    public function create()
    {
        $citizen = Auth::guard('citizen')->user();
        $serviceTypes = ServiceType::where('is_active', true)->get();
        $palikas = \App\Models\Palika::with(['wards' => fn($q) => $q->orderBy('ward_number')])
            ->orderBy('name_en')
            ->get();

        return view('citizen.appointments.create', compact('citizen', 'serviceTypes', 'palikas'));
    }

    public function store(Request $request)
    {
        $citizen = Auth::guard('citizen')->user();

        $request->validate([
            'palika_id' => ['nullable', 'exists:palikas,id'],
            'ward_id' => ['nullable', 'exists:wards,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'time_slot' => ['required', 'string'],
            'purpose' => ['required', 'string', 'max:255'],
            'service_type_id' => ['nullable', 'exists:service_types,id'],
        ]);

        $wardId = $request->ward_id ?: $citizen->ward_id;
        $ward = $wardId ? \App\Models\Ward::find($wardId) : null;
        $palikaId = $ward ? $ward->palika_id : ($request->palika_id ?: ($citizen->ward?->palika_id ?? 1));

        if (!$wardId) {
            $defaultWard = \App\Models\Ward::where('ward_number', 32)->first() ?? \App\Models\Ward::first();
            if ($defaultWard) {
                $wardId = $defaultWard->id;
                $palikaId = $defaultWard->palika_id;
            }
        }

        $nepaliYear = 2081;
        $count = Appointment::whereYear('created_at', now()->year)->count() + 1;
        $aptNumber = sprintf('APT-%d-%04d', $nepaliYear, $count);

        $appointment = Appointment::create([
            'appointment_number' => $aptNumber,
            'citizen_id' => $citizen->id,
            'palika_id' => $palikaId,
            'ward_id' => $wardId,
            'service_type_id' => $request->service_type_id,
            'appointment_date' => $request->appointment_date,
            'time_slot' => $request->time_slot,
            'status' => 'scheduled',
            'purpose' => $request->purpose,
            'remarks' => $request->remarks,
        ]);

        \App\Models\AuditLog::record(
            'appointment_booked',
            "Citizen {$citizen->full_name} booked appointment {$aptNumber} for Ward {$appointment->ward?->ward_number}",
            $appointment
        );

        return redirect()->route('citizen.appointments.index')
            ->with('success', "भेटघाट दर्ता नं. {$aptNumber} सफलतापूर्वक बुक भयो (मिती: {$appointment->appointment_date->format('Y-m-d')}, समय: {$appointment->time_slot})।");
    }
}
