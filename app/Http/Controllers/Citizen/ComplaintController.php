<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index()
    {
        $citizen = Auth::guard('citizen')->user();
        $complaints = Complaint::where('citizen_id', $citizen->id)
            ->latest()
            ->paginate(10);

        return view('citizen.complaints.index', compact('complaints'));
    }

    public function create()
    {
        $citizen = Auth::guard('citizen')->user();
        return view('citizen.complaints.create', compact('citizen'));
    }

    public function store(Request $request)
    {
        $citizen = Auth::guard('citizen')->user();

        $request->validate([
            'category' => ['required', 'in:sanitation,roads,electricity,water,corruption,other'],
            'subject' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:200'],
            'is_anonymous' => ['nullable', 'boolean'],
        ]);

        $ticketNo = 'CMP-' . date('Ymd') . '-' . rand(1000, 9999);

        Complaint::create([
            'ticket_number' => $ticketNo,
            'citizen_id' => $request->boolean('is_anonymous') ? null : $citizen->id,
            'palika_id' => $citizen->ward->palika_id ?? 1,
            'ward_id' => $citizen->ward_id,
            'category' => $request->category,
            'subject' => $request->subject,
            'description' => $request->description,
            'location' => $request->location,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'anonymous_contact' => $request->boolean('is_anonymous') ? $citizen->phone : null,
            'status' => 'open',
            'priority' => 'medium',
        ]);

        return redirect()->route('citizen.complaints.index')
            ->with('success', "Grievance lodged successfully! Ticket number: {$ticketNo}");
    }

    public function show($id)
    {
        $citizen = Auth::guard('citizen')->user();
        $complaint = Complaint::where(function ($q) use ($citizen) {
            $q->where('citizen_id', $citizen->id)
              ->orWhere('anonymous_contact', $citizen->phone);
        })->findOrFail($id);

        return view('citizen.complaints.show', compact('complaint'));
    }
}
