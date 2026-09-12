<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Palika;
use App\Models\Staff;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        $ktmDistrict = District::where('code', 'KTM')->first();
        $lalDistrict = District::where('code', 'LAL')->first();
        $bktDistrict = District::where('code', 'BKT')->first();

        // =========================================================================
        // 1. TIER 1: SUPER ADMIN (System-Wide Scope)
        // =========================================================================
        Staff::updateOrCreate(
            ['email' => 'superadmin@wardsewa.gov.np'],
            [
                'name' => 'WardSewa Master Super Administrator',
                'phone' => '9800000001',
                'password' => $password,
                'district_id' => null,
                'palika_id' => null,
                'ward_id' => null,
                'role' => 'super_admin',
                'designation' => 'Chief Technology & Governance Officer',
                'is_active' => true,
            ]
        );

        // =========================================================================
        // 2. TIER 2: DISTRICT ADMINS (Single District Scope)
        // =========================================================================
        
        // Kathmandu District Admin
        if ($ktmDistrict) {
            Staff::updateOrCreate(
                ['email' => 'admin.ktm@wardsewa.gov.np'],
                [
                    'name' => 'Kathmandu District Coordination Administrator',
                    'phone' => '9801000001',
                    'password' => $password,
                    'district_id' => $ktmDistrict->id,
                    'palika_id' => null,
                    'ward_id' => null,
                    'role' => 'district_admin',
                    'designation' => 'District Administrative Officer (KTM)',
                    'is_active' => true,
                ]
            );
        }

        // Lalitpur District Admin
        if ($lalDistrict) {
            Staff::updateOrCreate(
                ['email' => 'admin.lalitpur@wardsewa.gov.np'],
                [
                    'name' => 'Lalitpur District Coordination Administrator',
                    'phone' => '9802000001',
                    'password' => $password,
                    'district_id' => $lalDistrict->id,
                    'palika_id' => null,
                    'ward_id' => null,
                    'role' => 'district_admin',
                    'designation' => 'District Administrative Officer (LAL)',
                    'is_active' => true,
                ]
            );
        }

        // Bhaktapur District Admin
        if ($bktDistrict) {
            Staff::updateOrCreate(
                ['email' => 'admin.bhaktapur@wardsewa.gov.np'],
                [
                    'name' => 'Bhaktapur District Coordination Administrator',
                    'phone' => '9803000001',
                    'password' => $password,
                    'district_id' => $bktDistrict->id,
                    'palika_id' => null,
                    'ward_id' => null,
                    'role' => 'district_admin',
                    'designation' => 'District Administrative Officer (BKT)',
                    'is_active' => true,
                ]
            );
        }

        // =========================================================================
        // 3. TIER 3: LOCAL GOVERNMENT ADMINS (Single Palika Scope)
        // =========================================================================
        
        // 3.1 Kathmandu Metropolitan City (KMC)
        $kmc = Palika::where('code', 'KMC')->first();
        if ($kmc) {
            Staff::updateOrCreate(
                ['email' => 'admin.kmc@wardsewa.gov.np'],
                [
                    'name' => 'Kathmandu Metro Local Government Admin',
                    'phone' => '9851000000',
                    'password' => $password,
                    'district_id' => $kmc->district_id,
                    'palika_id' => $kmc->id,
                    'ward_id' => null,
                    'role' => 'local_government_admin',
                    'designation' => 'Chief Administrative Officer (KMC)',
                    'is_active' => true,
                ]
            );

            // Backwards compatibility alias
            Staff::updateOrCreate(
                ['email' => 'admin@kathmandu.gov.np'],
                [
                    'name' => 'KMC IT Administrator',
                    'phone' => '9851000010',
                    'password' => $password,
                    'district_id' => $kmc->district_id,
                    'palika_id' => $kmc->id,
                    'ward_id' => null,
                    'role' => 'local_government_admin',
                    'designation' => 'Director of IT (KMC)',
                    'is_active' => true,
                ]
            );
        }

        // 3.2 Chandragiri Municipality (CGM)
        $cgm = Palika::where('code', 'CGM')->first();
        if ($cgm) {
            Staff::updateOrCreate(
                ['email' => 'admin.chandragiri@wardsewa.gov.np'],
                [
                    'name' => 'Chandragiri Municipality Admin',
                    'phone' => '9851000050',
                    'password' => $password,
                    'district_id' => $cgm->district_id,
                    'palika_id' => $cgm->id,
                    'ward_id' => null,
                    'role' => 'local_government_admin',
                    'designation' => 'Chief Administrative Officer (CGM)',
                    'is_active' => true,
                ]
            );
        }

        // 3.3 Lalitpur Metropolitan City (LMC)
        $lmc = Palika::where('code', 'LMC')->first();
        if ($lmc) {
            Staff::updateOrCreate(
                ['email' => 'admin.lmc@wardsewa.gov.np'],
                [
                    'name' => 'Lalitpur Metro Local Government Admin',
                    'phone' => '9851100000',
                    'password' => $password,
                    'district_id' => $lmc->district_id,
                    'palika_id' => $lmc->id,
                    'ward_id' => null,
                    'role' => 'local_government_admin',
                    'designation' => 'Chief Administrative Officer (LMC)',
                    'is_active' => true,
                ]
            );

            // Alias
            Staff::updateOrCreate(
                ['email' => 'admin@lalitpur.gov.np'],
                [
                    'name' => 'Lalitpur IT Administrator',
                    'phone' => '9851100010',
                    'password' => $password,
                    'district_id' => $lmc->district_id,
                    'palika_id' => $lmc->id,
                    'ward_id' => null,
                    'role' => 'local_government_admin',
                    'designation' => 'Director of IT (LMC)',
                    'is_active' => true,
                ]
            );
        }

        // 3.4 Bhaktapur Municipality (BKM)
        $bkm = Palika::where('code', 'BKM')->first();
        if ($bkm) {
            Staff::updateOrCreate(
                ['email' => 'admin.bkm@wardsewa.gov.np'],
                [
                    'name' => 'Bhaktapur Municipality Admin',
                    'phone' => '9851200000',
                    'password' => $password,
                    'district_id' => $bkm->district_id,
                    'palika_id' => $bkm->id,
                    'ward_id' => null,
                    'role' => 'local_government_admin',
                    'designation' => 'Chief Administrative Officer (BKM)',
                    'is_active' => true,
                ]
            );

            // Alias
            Staff::updateOrCreate(
                ['email' => 'admin@bhaktapur.gov.np'],
                [
                    'name' => 'Bhaktapur IT Administrator',
                    'phone' => '9851200010',
                    'password' => $password,
                    'district_id' => $bkm->district_id,
                    'palika_id' => $bkm->id,
                    'ward_id' => null,
                    'role' => 'local_government_admin',
                    'designation' => 'Director of IT (BKM)',
                    'is_active' => true,
                ]
            );
        }

        // =========================================================================
        // 4. TIER 4: WARD ADMINS & WARD STAFF (Single Ward Scope)
        // =========================================================================

        // 4.1 KMC Ward 32 (Pilot Ward)
        if ($kmc) {
            $ward32 = Ward::where('palika_id', $kmc->id)->where('ward_number', 32)->first();
            $ward32Id = $ward32?->id;

            // Ward 32 Administrator
            Staff::updateOrCreate(
                ['email' => 'admin.ward32@wardsewa.gov.np'],
                [
                    'name' => 'KMC Ward 32 Administrator',
                    'phone' => '9851000032',
                    'password' => $password,
                    'district_id' => $kmc->district_id,
                    'palika_id' => $kmc->id,
                    'ward_id' => $ward32Id,
                    'role' => 'ward_admin',
                    'designation' => 'Ward Executive Officer',
                    'is_active' => true,
                ]
            );

            // Alias
            Staff::updateOrCreate(
                ['email' => 'admin@ward32.gov.np'],
                [
                    'name' => 'KMC Ward 32 Administrator',
                    'phone' => '9851000033',
                    'password' => $password,
                    'district_id' => $kmc->district_id,
                    'palika_id' => $kmc->id,
                    'ward_id' => $ward32Id,
                    'role' => 'ward_admin',
                    'designation' => 'Ward Executive Officer',
                    'is_active' => true,
                ]
            );

            // Ward 32 Chairperson
            Staff::updateOrCreate(
                ['email' => 'chair@ward32.gov.np'],
                [
                    'name' => 'Bharat Lal Shrestha',
                    'phone' => '9851000001',
                    'password' => $password,
                    'district_id' => $kmc->district_id,
                    'palika_id' => $kmc->id,
                    'ward_id' => $ward32Id,
                    'role' => 'ward_chair',
                    'designation' => 'Ward Chairperson (वडा अध्यक्ष)',
                    'is_active' => true,
                ]
            );

            // Ward 32 Secretary
            Staff::updateOrCreate(
                ['email' => 'secretary@ward32.gov.np'],
                [
                    'name' => 'Sita Sharma',
                    'phone' => '9851000002',
                    'password' => $password,
                    'district_id' => $kmc->district_id,
                    'palika_id' => $kmc->id,
                    'ward_id' => $ward32Id,
                    'role' => 'secretary',
                    'designation' => 'Ward Secretary (वडा सचिव)',
                    'is_active' => true,
                ]
            );

            // Ward 32 Front Desk Clerk
            Staff::updateOrCreate(
                ['email' => 'clerk@ward32.gov.np'],
                [
                    'name' => 'Ramesh Adhikari',
                    'phone' => '9851000003',
                    'password' => $password,
                    'district_id' => $kmc->district_id,
                    'palika_id' => $kmc->id,
                    'ward_id' => $ward32Id,
                    'role' => 'clerk',
                    'designation' => 'Front Desk Assistant (वडा सहायक)',
                    'is_active' => true,
                ]
            );
        }

        // 4.2 LMC Ward 1 (Kupandole)
        if ($lmc) {
            $lmcWard1 = Ward::where('palika_id', $lmc->id)->where('ward_number', 1)->first();
            if ($lmcWard1) {
                Staff::updateOrCreate(
                    ['email' => 'chair@lmc1.gov.np'],
                    [
                        'name' => 'Bikram Maharjan',
                        'phone' => '9851100001',
                        'password' => $password,
                        'district_id' => $lmc->district_id,
                        'palika_id' => $lmc->id,
                        'ward_id' => $lmcWard1->id,
                        'role' => 'ward_chair',
                        'designation' => 'Ward Chairperson (LMC Ward 1)',
                        'is_active' => true,
                    ]
                );
            }
        }

        // 4.3 BKM Ward 1 (Durbar Square)
        if ($bkm) {
            $bkmWard1 = Ward::where('palika_id', $bkm->id)->where('ward_number', 1)->first();
            if ($bkmWard1) {
                Staff::updateOrCreate(
                    ['email' => 'chair@bkm1.gov.np'],
                    [
                        'name' => 'Sunil Prajapati',
                        'phone' => '9851200001',
                        'password' => $password,
                        'district_id' => $bkm->district_id,
                        'palika_id' => $bkm->id,
                        'ward_id' => $bkmWard1->id,
                        'role' => 'ward_chair',
                        'designation' => 'Ward Chairperson (BKM Ward 1)',
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
