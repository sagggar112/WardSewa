<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\Citizen;
use App\Models\District;
use App\Models\Palika;
use App\Models\ServiceType;
use App\Models\Staff;
use App\Models\Ward;
use Illuminate\Http\Request;

use App\Models\Province;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_districts' => District::count(),
            'total_palikas' => Palika::count(),
            'total_wards' => Ward::count(),
            'total_citizens' => Citizen::count(),
            'total_services' => ServiceType::count(),
            'total_admins' => Staff::count(),
            'super_admins' => Staff::where('role', 'super_admin')->count(),
            'district_admins' => Staff::where('role', 'district_admin')->count(),
            'local_govt_admins' => Staff::where('role', 'local_government_admin')->count(),
            'ward_admins' => Staff::whereIn('role', ['ward_admin', 'ward_chair', 'secretary', 'clerk'])->count(),
            'total_applications' => Application::count(),
            'pending_applications' => Application::whereIn('status', ['submitted', 'under_review', 'documents_requested'])->count(),
            'approved_applications' => Application::where('status', 'approved')->count(),
            'rejected_applications' => Application::where('status', 'rejected')->count(),
            'total_appointments' => Appointment::count(),
            'upcoming_appointments' => Appointment::upcoming()->count(),
        ];

        // District Breakdown
        $districts = District::withCount(['palikas', 'staff'])
            ->with(['palikas.wards'])
            ->get();

        $recentApplications = Application::with(['citizen', 'serviceType', 'ward.palika'])
            ->latest()
            ->take(10)
            ->get();

        $recentAuditLogs = AuditLog::with('staff')
            ->latest('created_at')
            ->take(8)
            ->get();

        return view('staff.superadmin.dashboard', compact('stats', 'districts', 'recentApplications', 'recentAuditLogs'));
    }

    public function geography()
    {
        $provinces = Province::orderBy('id')->get();
        $districts = District::with(['province', 'palikas.wards'])
            ->get();

        return view('staff.superadmin.geography.index', compact('districts', 'provinces'));
    }

    public function storeDistrict(Request $request)
    {
        $validated = $request->validate([
            'province_id' => ['required', 'exists:provinces,id'],
            'name_en' => ['required', 'string', 'max:255'],
            'name_ne' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:districts,code'],
            'admin_name' => ['nullable', 'string', 'max:255'],
            'admin_phone' => ['nullable', 'string', 'max:20'],
            'admin_email' => ['nullable', 'email', 'max:255', 'unique:staff,email'],
        ]);

        $district = District::create([
            'province_id' => $validated['province_id'],
            'name_en' => trim($validated['name_en']),
            'name_ne' => trim($validated['name_ne']),
            'code' => strtoupper(trim($validated['code'])),
        ]);

        // Provision District Admin Account
        $adminEmail = $validated['admin_email'] ?? ('admin.' . strtolower($district->code) . '@wardsewa.gov.np');
        $adminName = $validated['admin_name'] ?? ($district->name_en . ' District Coordinator');

        $staff = Staff::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'phone' => $validated['admin_phone'] ?? ('98010' . str_pad((string)$district->id, 5, '0', STR_PAD_LEFT)),
                'password' => Hash::make('password123'),
                'district_id' => $district->id,
                'palika_id' => null,
                'ward_id' => null,
                'role' => 'district_admin',
                'designation' => "District Administrative Officer ({$district->code})",
                'is_active' => true,
            ]
        );

        AuditLog::record(
            'create_district',
            "Super Admin created District: {$district->name_en} ({$district->code}) and provisioned DCC admin account ({$adminEmail})",
            $district,
            ['admin_email' => $adminEmail]
        );

        return redirect()->route('staff.superadmin.geography')
            ->with('success', "जिल्ला '{$district->name_ne}' ({$district->name_en}) सफलतापूर्वक थपिएको छ।");
    }

    public function storePalika(Request $request)
    {
        $validated = $request->validate([
            'district_id' => ['required', 'exists:districts,id'],
            'name_en' => ['required', 'string', 'max:255'],
            'name_ne' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:metropolitan,sub_metropolitan,municipality,rural_municipality'],
            'code' => ['required', 'string', 'max:10', 'unique:palikas,code'],
            'admin_name' => ['nullable', 'string', 'max:255'],
            'admin_phone' => ['nullable', 'string', 'max:20'],
            'admin_email' => ['nullable', 'email', 'max:255', 'unique:staff,email'],
        ]);

        $palika = Palika::create([
            'district_id' => $validated['district_id'],
            'name_en' => trim($validated['name_en']),
            'name_ne' => trim($validated['name_ne']),
            'type' => $validated['type'],
            'code' => strtoupper(trim($validated['code'])),
        ]);

        // Auto-provision Chief Administrative Officer / Local Government Admin
        $adminEmail = $validated['admin_email'] ?? ('admin.' . strtolower($palika->code) . '@wardsewa.gov.np');
        $adminName = $validated['admin_name'] ?? ($palika->name_en . ' Chief Admin Officer');

        Staff::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'phone' => $validated['admin_phone'] ?? ('98510' . str_pad((string)$palika->id, 5, '0', STR_PAD_LEFT)),
                'password' => Hash::make('password123'),
                'district_id' => $palika->district_id,
                'palika_id' => $palika->id,
                'ward_id' => null,
                'role' => 'local_government_admin',
                'designation' => "Chief Administrative Officer ({$palika->code})",
                'is_active' => true,
            ]
        );

        $typeLabel = match($palika->type) {
            'metropolitan' => 'महानगरपालिका (Metropolitan City)',
            'sub_metropolitan' => 'उपमहानगरपालिका (Sub-Metropolitan City)',
            'rural_municipality' => 'गाउँपालिका (Rural Municipality)',
            default => 'नगरपालिका (Municipality)',
        };

        AuditLog::record(
            'create_palika',
            "Super Admin created {$typeLabel}: {$palika->name_en} ({$palika->code}) under district #{$palika->district_id}",
            $palika,
            ['admin_email' => $adminEmail]
        );

        return redirect()->route('staff.superadmin.geography')
            ->with('success', "स्थानीय तह '{$palika->name_ne}' सफलतापूर्वक थपिएको छ। प्रशासक खाता: {$adminEmail}");
    }

    public function updatePalika(Request $request, $id)
    {
        $palika = Palika::findOrFail($id);

        $validated = $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ne' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:metropolitan,sub_metropolitan,municipality,rural_municipality'],
        ]);

        $old = $palika->toArray();

        $palika->update([
            'name_en' => trim($validated['name_en']),
            'name_ne' => trim($validated['name_ne']),
            'type' => $validated['type'],
        ]);

        AuditLog::record(
            'update_palika',
            "Super Admin updated Palika #{$palika->id}: {$palika->name_en}",
            $palika,
            ['old' => $old, 'new' => $palika->toArray()]
        );

        return redirect()->route('staff.superadmin.geography')
            ->with('success', "स्थानीय तह '{$palika->name_ne}' को विवरण अद्यावधिक गरिएको छ।");
    }

    public function admins(Request $request)
    {
        $query = Staff::with(['district', 'palika', 'ward']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $admins = $query->latest()->paginate(20);

        return view('staff.superadmin.admins.index', compact('admins'));
    }

    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('staff');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        $logs = $query->latest('created_at')->paginate(25);

        return view('staff.superadmin.audit_logs.index', compact('logs'));
    }
}
