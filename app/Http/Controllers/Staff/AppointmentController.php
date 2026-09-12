<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $staff = auth('staff')->user();

        $query = Appointment::with(['citizen', 'ward.palika', 'serviceType', 'application']);

        if ($staff->ward_id) {
            $query->where('ward_id', $staff->ward_id);
        } elseif ($staff->palika_id) {
            $query->where('palika_id', $staff->palika_id);
        } elseif ($staff->district_id) {
            $query->whereHas('palika', function ($q) use ($staff) {
                $q->where('district_id', $staff->district_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->where('appointment_date', $request->date);
        }

        $appointments = $query->latest('appointment_date')->paginate(15);

        return view('staff.appointments.index', compact('staff', 'appointments'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:confirmed,rescheduled,completed,cancelled,no_show'],
            'remarks' => ['nullable', 'string'],
        ]);

        $staff = auth('staff')->user();
        $appointment = Appointment::findOrFail($id);

        $oldStatus = $appointment->status;
        $appointment->update([
            'status' => $request->status,
            'remarks' => $request->remarks ?? $appointment->remarks,
        ]);

        AuditLog::record(
            'appointment_status_update',
            "Appointment {$appointment->appointment_number} status changed from {$oldStatus} to {$request->status}",
            $appointment
        );

        return back()->with('success', "Appointment status updated to {$request->status}.");
    }
}
