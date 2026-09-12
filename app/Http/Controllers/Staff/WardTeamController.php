<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class WardTeamController extends Controller
{
    public function index()
    {
        $staff = auth('staff')->user();

        if (!$staff->ward) {
            return redirect()->route('staff.dashboard')->with('error', 'कुनै वडा तोकिएको छैन।');
        }

        $ward = $staff->ward;
        $teamMembers = Staff::where('ward_id', $ward->id)
            ->orderByRaw("CASE 
                WHEN role = 'ward_chair' THEN 1 
                WHEN role = 'ward_admin' THEN 2 
                WHEN role = 'secretary' THEN 3 
                WHEN role = 'clerk' THEN 4 
                ELSE 5 END")
            ->orderBy('name')
            ->get();

        return view('staff.team.index', compact('staff', 'ward', 'teamMembers'));
    }

    public function store(Request $request)
    {
        $staff = auth('staff')->user();

        if (!$staff->ward) {
            return back()->with('error', 'तपाईँको खातामा कुनै वडा तोकिएको छैन।');
        }

        $ward = $staff->ward;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:100', 'unique:staff,email'],
            'phone' => ['required', 'string', 'max:30'],
            'role' => ['required', 'string', Rule::in(['secretary', 'clerk', 'ward_admin'])],
            'designation' => ['required', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'email.unique' => 'यो इमेल प्रणालीमा पहिले नै दर्ता भइसकेको छ।',
            'role.in' => 'तपाईँ वडा सचिव, सहायक (Clerk) वा वडा प्रशासक मात्र सिर्जना गर्न सक्नुहुन्छ।',
        ]);

        $newStaff = Staff::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => trim($validated['phone']),
            'password' => Hash::make(!empty($validated['password']) ? $validated['password'] : 'password123'),
            'role' => $validated['role'],
            'designation' => trim($validated['designation']),
            'ward_id' => $ward->id,
            'palika_id' => $ward->palika_id,
            'district_id' => $ward->palika->district_id ?? $staff->district_id,
            'is_active' => true,
        ]);

        AuditLog::record(
            'create_ward_staff',
            "Ward Admin/Chair ({$staff->name}) added {$newStaff->role_title}: {$newStaff->name} ({$newStaff->email}) to Ward #{$ward->ward_number}",
            $newStaff,
            ['ward_id' => $ward->id, 'role' => $newStaff->role, 'email' => $newStaff->email]
        );

        return redirect()->route('staff.team.index')
            ->with('success', "नयाँ कर्मचारी '{$newStaff->name}' ({$newStaff->role_title}) सफलतापूर्वक दर्ता गरियो।");
    }

    public function update(Request $request, $id)
    {
        $staff = auth('staff')->user();

        if (!$staff->ward) {
            return back()->with('error', 'कुनै वडा तोकिएको छैन।');
        }

        // Multi-tenancy check: Staff must belong to the same ward
        $targetStaff = Staff::where('ward_id', $staff->ward_id)->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'designation' => ['required', 'string', 'max:100'],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        // Prevent deactivating own self or ward chair if done by another
        if ($targetStaff->id === $staff->id && !$validated['is_active']) {
            return back()->with('error', 'तपाईँ आफ्नै खाता निष्क्रिय गर्न सक्नुहुन्न।');
        }

        $oldData = $targetStaff->only(['name', 'phone', 'designation', 'is_active']);

        $targetStaff->name = trim($validated['name']);
        $targetStaff->phone = trim($validated['phone']);
        $targetStaff->designation = trim($validated['designation']);
        $targetStaff->is_active = (bool)$validated['is_active'];

        if (!empty($validated['password'])) {
            $targetStaff->password = Hash::make($validated['password']);
        }

        $targetStaff->save();

        AuditLog::record(
            'update_ward_staff',
            "Ward Admin/Chair ({$staff->name}) updated details of {$targetStaff->name} in Ward #{$staff->ward->ward_number}",
            $targetStaff,
            ['old' => $oldData, 'new' => $targetStaff->only(['name', 'phone', 'designation', 'is_active'])]
        );

        return redirect()->route('staff.team.index')
            ->with('success', "कर्मचारी '{$targetStaff->name}' को विवरण सफलतापूर्वक अद्यावधिक गरियो।");
    }

    public function destroy($id)
    {
        $staff = auth('staff')->user();

        if (!$staff->ward) {
            return back()->with('error', 'कुनै वडा तोकिएको छैन।');
        }

        $targetStaff = Staff::where('ward_id', $staff->ward_id)->findOrFail($id);

        if ($targetStaff->id === $staff->id) {
            return back()->with('error', 'तपाईँ आफ्नै खाता निष्क्रिय गर्न सक्नुहुन्न।');
        }

        if ($targetStaff->role === 'ward_chair') {
            return back()->with('error', 'वडा अध्यक्षको खाता यहाँबाट निष्क्रिय गर्न मिल्दैन।');
        }

        $targetStaff->is_active = false;
        $targetStaff->save();

        AuditLog::record(
            'deactivate_ward_staff',
            "Ward Admin/Chair ({$staff->name}) deactivated staff {$targetStaff->name} ({$targetStaff->email})",
            $targetStaff,
            ['role' => $targetStaff->role, 'email' => $targetStaff->email]
        );

        return redirect()->route('staff.team.index')
            ->with('success', "कर्मचारी '{$targetStaff->name}' लाई निष्क्रिय गरिएको छ।");
    }
}
