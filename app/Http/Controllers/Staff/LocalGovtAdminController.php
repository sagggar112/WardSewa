<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Appointment;
use App\Models\Palika;
use App\Models\Ward;
use Illuminate\Http\Request;

use App\Models\AuditLog;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

        // Next suggested ward number
        $maxWardNum = Ward::where('palika_id', $palika->id)->max('ward_number') ?? 0;
        $nextWardNumber = $maxWardNum + 1;

        return view('staff.localgovt.wards', compact('staff', 'palika', 'wards', 'nextWardNumber'));
    }

    public function storeWard(Request $request)
    {
        $staff = auth('staff')->user();
        $palika = $staff->palika ?? Palika::where('code', 'KMC')->first();

        if (!$palika) {
            return back()->with('error', 'कुनै पालिका तोकिएको छैन।');
        }

        $validated = $request->validate([
            'ward_number' => [
                'required',
                'integer',
                'min:1',
                'max:99',
                Rule::unique('wards')->where('palika_id', $palika->id),
            ],
            'office_address' => ['required', 'string', 'max:255'],
            'office_phone' => ['nullable', 'string', 'max:30'],
            'office_email' => ['nullable', 'string', 'max:100'],
            'chairperson_name' => ['nullable', 'string', 'max:255'],
            'chairperson_phone' => ['nullable', 'string', 'max:30'],
            'chairperson_email' => ['nullable', 'email', 'max:100', 'unique:staff,email'],
        ], [
            'ward_number.unique' => 'यो वडा नम्बर यस पालिकामा पहिले नै दर्ता भइसकेको छ।',
        ]);

        $ward = Ward::create([
            'palika_id' => $palika->id,
            'ward_number' => (int)$validated['ward_number'],
            'office_address' => trim($validated['office_address']),
            'office_phone' => $validated['office_phone'] ?? ('01-4' . str_pad((string)$validated['ward_number'], 5, '0', STR_PAD_LEFT)),
            'office_email' => $validated['office_email'] ?? ("ward{$validated['ward_number']}@" . strtolower($palika->code) . ".gov.np"),
        ]);

        // Auto-provision Chairperson Account
        $codeLower = strtolower($palika->code);
        $chairEmail = $validated['chairperson_email'] ?? "chair.{$codeLower}{$ward->ward_number}@wardsewa.gov.np";
        $chairName = $validated['chairperson_name'] ?? "{$palika->name_en} Ward {$ward->ward_number} Chairperson";
        $chairPhone = $validated['chairperson_phone'] ?? ('9851' . str_pad((string)$ward->id, 6, '0', STR_PAD_LEFT));

        Staff::firstOrCreate(
            ['email' => $chairEmail],
            [
                'name' => $chairName,
                'phone' => $chairPhone,
                'password' => Hash::make('password123'),
                'district_id' => $palika->district_id,
                'palika_id' => $palika->id,
                'ward_id' => $ward->id,
                'role' => 'ward_chair',
                'designation' => "Ward Chairperson ({$palika->name_en} Ward {$ward->ward_number})",
                'is_active' => true,
            ]
        );

        AuditLog::record(
            'create_ward',
            "Local Govt Admin ({$staff->name}) created Ward #{$ward->ward_number} in {$palika->name_en} with Chair: {$chairName}",
            $ward,
            ['chair_name' => $chairName, 'chair_email' => $chairEmail]
        );

        return redirect()->route('staff.localgovt.wards')
            ->with('success', "वडा नं. {$ward->ward_number} सफलतापूर्वक थपिएको छ। वडा अध्यक्ष लगइन: {$chairEmail}");
    }

    public function updateWard(Request $request, $id)
    {
        $staff = auth('staff')->user();
        $palika = $staff->palika ?? Palika::where('code', 'KMC')->first();

        // Multi-tenancy authorization check: ward must belong to this admin's palika
        $ward = Ward::where('palika_id', $palika->id)->findOrFail($id);

        $validated = $request->validate([
            'office_address' => ['required', 'string', 'max:255'],
            'office_phone' => ['nullable', 'string', 'max:30'],
            'office_email' => ['nullable', 'string', 'max:100'],
            'chairperson_name' => ['nullable', 'string', 'max:255'],
            'chairperson_phone' => ['nullable', 'string', 'max:30'],
        ]);

        $old = $ward->toArray();

        $ward->update([
            'office_address' => trim($validated['office_address']),
            'office_phone' => $validated['office_phone'] ?? $ward->office_phone,
            'office_email' => $validated['office_email'] ?? $ward->office_email,
        ]);

        // Update chairperson if exists
        $chair = $ward->staff()->where('role', 'ward_chair')->first();
        if ($chair && (!empty($validated['chairperson_name']) || !empty($validated['chairperson_phone']))) {
            $chair->update([
                'name' => $validated['chairperson_name'] ?: $chair->name,
                'phone' => $validated['chairperson_phone'] ?: $chair->phone,
            ]);
        }

        AuditLog::record(
            'update_ward',
            "Local Govt Admin updated Ward #{$ward->ward_number} details in {$palika->name_en}",
            $ward,
            ['old' => $old, 'new' => $ward->toArray()]
        );

        return redirect()->route('staff.localgovt.wards')
            ->with('success', "वडा नं. {$ward->ward_number} को विवरण सफलतापूर्वक अद्यावधिक गरिएको छ।");
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

    public function wardStaff($id)
    {
        $staff = auth('staff')->user();
        $palika = $staff->palika ?? Palika::where('code', 'KMC')->first();

        $ward = Ward::where('palika_id', $palika->id)->with('palika')->findOrFail($id);

        $teamMembers = Staff::where('ward_id', $ward->id)
            ->orderByRaw("CASE 
                WHEN role = 'ward_chair' THEN 1 
                WHEN role = 'ward_admin' THEN 2 
                WHEN role = 'secretary' THEN 3 
                WHEN role = 'clerk' THEN 4 
                ELSE 5 END")
            ->orderBy('name')
            ->get();

        return view('staff.localgovt.ward_staff', compact('staff', 'palika', 'ward', 'teamMembers'));
    }

    public function storeWardStaff(Request $request, $id)
    {
        $staff = auth('staff')->user();
        $palika = $staff->palika ?? Palika::where('code', 'KMC')->first();

        $ward = Ward::where('palika_id', $palika->id)->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:100', 'unique:staff,email'],
            'phone' => ['required', 'string', 'max:30'],
            'role' => ['required', 'string', Rule::in(['ward_chair', 'secretary', 'clerk', 'ward_admin'])],
            'designation' => ['required', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'email.unique' => 'यो इमेल प्रणालीमा पहिले नै दर्ता भइसकेको छ।',
        ]);

        $newStaff = Staff::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => trim($validated['phone']),
            'password' => Hash::make(!empty($validated['password']) ? $validated['password'] : 'password123'),
            'role' => $validated['role'],
            'designation' => trim($validated['designation']),
            'ward_id' => $ward->id,
            'palika_id' => $palika->id,
            'district_id' => $palika->district_id,
            'is_active' => true,
        ]);

        AuditLog::record(
            'create_ward_staff_by_palika',
            "Municipal Admin ({$staff->name}) added {$newStaff->role_title}: {$newStaff->name} ({$newStaff->email}) to {$palika->name_en} Ward #{$ward->ward_number}",
            $newStaff,
            ['palika_id' => $palika->id, 'ward_id' => $ward->id, 'role' => $newStaff->role]
        );

        return redirect()->route('staff.localgovt.wards.staff', $ward->id)
            ->with('success', "कर्मचारी '{$newStaff->name}' ({$newStaff->role_title}) सफलतापूर्वक दर्ता गरियो।");
    }

    public function updateWardStaff(Request $request, $id, $staffId)
    {
        $staff = auth('staff')->user();
        $palika = $staff->palika ?? Palika::where('code', 'KMC')->first();

        $ward = Ward::where('palika_id', $palika->id)->findOrFail($id);
        $targetStaff = Staff::where('ward_id', $ward->id)->findOrFail($staffId);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'designation' => ['required', 'string', 'max:100'],
            'role' => ['nullable', 'string', Rule::in(['ward_chair', 'secretary', 'clerk', 'ward_admin'])],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $oldData = $targetStaff->only(['name', 'phone', 'designation', 'role', 'is_active']);

        $targetStaff->name = trim($validated['name']);
        $targetStaff->phone = trim($validated['phone']);
        $targetStaff->designation = trim($validated['designation']);
        if (!empty($validated['role'])) {
            $targetStaff->role = $validated['role'];
        }
        $targetStaff->is_active = (bool)$validated['is_active'];

        if (!empty($validated['password'])) {
            $targetStaff->password = Hash::make($validated['password']);
        }

        $targetStaff->save();

        AuditLog::record(
            'update_ward_staff_by_palika',
            "Municipal Admin ({$staff->name}) updated details of {$targetStaff->name} ({$targetStaff->role_title}) in Ward #{$ward->ward_number}",
            $targetStaff,
            ['old' => $oldData, 'new' => $targetStaff->only(['name', 'phone', 'designation', 'role', 'is_active'])]
        );

        return redirect()->route('staff.localgovt.wards.staff', $ward->id)
            ->with('success', "कर्मचारी '{$targetStaff->name}' को विवरण सफलतापूर्वक अद्यावधिक गरियो।");
    }
}

