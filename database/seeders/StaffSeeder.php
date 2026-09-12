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

        // Super Admin Alias
        Staff::updateOrCreate(
            ['email' => 'admin@wardsewa.gov.np'],
            [
                'name' => 'Central System Administrator',
                'phone' => '9800000002',
                'password' => $password,
                'district_id' => null,
                'palika_id' => null,
                'ward_id' => null,
                'role' => 'super_admin',
                'designation' => 'Senior System Architect',
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
        // 3. TIER 3: ALL 11 PALIKAS OF KATHMANDU DISTRICT (ACTIVE PALIKAS)
        // =========================================================================
        
        $ktmPalikas = [
            'KMC' => [
                'name' => 'Kathmandu Metropolitan City (काठमाडौँ महानगरपालिका)',
                'email' => 'admin.kmc@wardsewa.gov.np',
                'alias' => 'admin@kathmandu.gov.np',
                'phone' => '9851000000',
                'designation' => 'Chief Administrative Officer (KMC)',
            ],
            'CGM' => [
                'name' => 'Chandragiri Municipality (चन्द्रागिरि नगरपालिका)',
                'email' => 'admin.chandragiri@wardsewa.gov.np',
                'alias' => 'admin@chandragiri.gov.np',
                'phone' => '9851000050',
                'designation' => 'Chief Administrative Officer (CGM)',
            ],
            'BNM' => [
                'name' => 'Budhanilkantha Municipality (बूढानीलकण्ठ नगरपालिका)',
                'email' => 'admin.budhanilkantha@wardsewa.gov.np',
                'alias' => 'admin@budhanilkantha.gov.np',
                'phone' => '9851000060',
                'designation' => 'Chief Administrative Officer (BNM)',
            ],
            'TRM' => [
                'name' => 'Tarakeshwor Municipality (तारकेश्वर नगरपालिका)',
                'email' => 'admin.tarakeshwor@wardsewa.gov.np',
                'alias' => 'admin@tarakeshwor.gov.np',
                'phone' => '9851000070',
                'designation' => 'Chief Administrative Officer (TRM)',
            ],
            'TKM' => [
                'name' => 'Tokha Municipality (टोखा नगरपालिका)',
                'email' => 'admin.tokha@wardsewa.gov.np',
                'alias' => 'admin@tokha.gov.np',
                'phone' => '9851000080',
                'designation' => 'Chief Administrative Officer (TKM)',
            ],
            'KRM' => [
                'name' => 'Kirtipur Municipality (कीर्तिपुर नगरपालिका)',
                'email' => 'admin.kirtipur@wardsewa.gov.np',
                'alias' => 'admin@kirtipur.gov.np',
                'phone' => '9851000090',
                'designation' => 'Chief Administrative Officer (KRM)',
            ],
            'NJM' => [
                'name' => 'Nagarjun Municipality (नागार्जुन नगरपालिका)',
                'email' => 'admin.nagarjun@wardsewa.gov.np',
                'alias' => 'admin@nagarjun.gov.np',
                'phone' => '9851000100',
                'designation' => 'Chief Administrative Officer (NJM)',
            ],
            'DKM' => [
                'name' => 'Dakshinkali Municipality (दक्षिणकाली नगरपालिका)',
                'email' => 'admin.dakshinkali@wardsewa.gov.np',
                'alias' => 'admin@dakshinkali.gov.np',
                'phone' => '9851000110',
                'designation' => 'Chief Administrative Officer (DKM)',
            ],
            'GKM' => [
                'name' => 'Gokarneshwor Municipality (गोकर्णेश्वर नगरपालिका)',
                'email' => 'admin.gokarneshwor@wardsewa.gov.np',
                'alias' => 'admin@gokarneshwor.gov.np',
                'phone' => '9851000120',
                'designation' => 'Chief Administrative Officer (GKM)',
            ],
            'KMM' => [
                'name' => 'Kageshwori Manohara Municipality (कागेश्वरी मनोहरा नगरपालिका)',
                'email' => 'admin.kageshwori@wardsewa.gov.np',
                'alias' => 'admin@kageshwori.gov.np',
                'phone' => '9851000130',
                'designation' => 'Chief Administrative Officer (KMM)',
            ],
            'SKM' => [
                'name' => 'Shankharapur Municipality (शंखरापुर नगरपालिका)',
                'email' => 'admin.shankharapur@wardsewa.gov.np',
                'alias' => 'admin@shankharapur.gov.np',
                'phone' => '9851000140',
                'designation' => 'Chief Administrative Officer (SKM)',
            ],
        ];

        foreach ($ktmPalikas as $code => $palikaData) {
            $palika = Palika::where('code', $code)->first();
            if ($palika) {
                // Primary Admin Account
                Staff::updateOrCreate(
                    ['email' => $palikaData['email']],
                    [
                        'name' => $palikaData['name'],
                        'phone' => $palikaData['phone'],
                        'password' => $password,
                        'district_id' => $palika->district_id,
                        'palika_id' => $palika->id,
                        'ward_id' => null,
                        'role' => 'local_government_admin',
                        'designation' => $palikaData['designation'],
                        'is_active' => true,
                    ]
                );

                // Alias Account
                if (isset($palikaData['alias'])) {
                    Staff::updateOrCreate(
                        ['email' => $palikaData['alias']],
                        [
                            'name' => $palikaData['name'],
                            'phone' => $palikaData['phone'],
                            'password' => $password,
                            'district_id' => $palika->district_id,
                            'palika_id' => $palika->id,
                            'ward_id' => null,
                            'role' => 'local_government_admin',
                            'designation' => $palikaData['designation'],
                            'is_active' => true,
                        ]
                    );
                }
            }
        }

        // Lalitpur Metro & Bhaktapur Municipality Admins
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
        // 4. TIER 4: WARD LEVEL STAFF ACROSS ALL 11 KATHMANDU PALIKAS
        // =========================================================================

        // 4.1 KMC Ward 32 (Pilot Ward - Full Staff Team)
        $kmc = Palika::where('code', 'KMC')->first();
        if ($kmc) {
            $ward32 = Ward::where('palika_id', $kmc->id)->where('ward_number', 32)->first();
            $ward32Id = $ward32?->id;

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
        }

        // 4.2 Seed Active Ward Chairs for each of the other 10 Kathmandu Palikas
        $palikaWardChairs = [
            'CGM' => ['name' => 'Ganesh Prasad Rijal', 'palika_name' => 'Chandragiri', 'email' => 'chair@cgm1.gov.np', 'alias' => 'chair@chandragiri1.gov.np'],
            'BNM' => ['name' => 'Surendra Lama', 'palika_name' => 'Budhanilkantha', 'email' => 'chair@bnm1.gov.np', 'alias' => 'chair@budhanilkantha1.gov.np'],
            'TRM' => ['name' => 'Shiva Prasad Aryal', 'palika_name' => 'Tarakeshwor', 'email' => 'chair@trm1.gov.np', 'alias' => 'chair@tarakeshwor1.gov.np'],
            'TKM' => ['name' => 'Prakash Adhikari', 'palika_name' => 'Tokha', 'email' => 'chair@tkm1.gov.np', 'alias' => 'chair@tokha1.gov.np'],
            'KRM' => ['name' => 'Hira Lal Maharjan', 'palika_name' => 'Kirtipur', 'email' => 'chair@krm1.gov.np', 'alias' => 'chair@kirtipur1.gov.np'],
            'NJM' => ['name' => 'Mohan Bahadur Basnet', 'palika_name' => 'Nagarjun', 'email' => 'chair@njm1.gov.np', 'alias' => 'chair@nagarjun1.gov.np'],
            'DKM' => ['name' => 'Krishna Prasad Shrestha', 'palika_name' => 'Dakshinkali', 'email' => 'chair@dkm1.gov.np', 'alias' => 'chair@dakshinkali1.gov.np'],
            'GKM' => ['name' => 'Jayaram Thapa', 'palika_name' => 'Gokarneshwor', 'email' => 'chair@gkm1.gov.np', 'alias' => 'chair@gokarneshwor1.gov.np'],
            'KMM' => ['name' => 'Nabin Shrestha', 'palika_name' => 'Kageshwori Manohara', 'email' => 'chair@kmm1.gov.np', 'alias' => 'chair@kageshwori1.gov.np'],
            'SKM' => ['name' => 'Laxman Dangol', 'palika_name' => 'Shankharapur', 'email' => 'chair@skm1.gov.np', 'alias' => 'chair@shankharapur1.gov.np'],
        ];

        foreach ($palikaWardChairs as $code => $chairData) {
            $palika = Palika::where('code', $code)->first();
            if ($palika) {
                $ward1 = Ward::where('palika_id', $palika->id)->where('ward_number', 1)->first();
                if ($ward1) {
                    Staff::updateOrCreate(
                        ['email' => $chairData['email']],
                        [
                            'name' => $chairData['name'],
                            'phone' => '98510' . str_pad((string)$ward1->id, 5, '0', STR_PAD_LEFT),
                            'password' => $password,
                            'district_id' => $palika->district_id,
                            'palika_id' => $palika->id,
                            'ward_id' => $ward1->id,
                            'role' => 'ward_chair',
                            'designation' => "Ward Chairperson ({$chairData['palika_name']} Ward 1)",
                            'is_active' => true,
                        ]
                    );

                    if (isset($chairData['alias'])) {
                        Staff::updateOrCreate(
                            ['email' => $chairData['alias']],
                            [
                                'name' => $chairData['name'],
                                'phone' => '98510' . str_pad((string)$ward1->id, 5, '0', STR_PAD_LEFT),
                                'password' => $password,
                                'district_id' => $palika->district_id,
                                'palika_id' => $palika->id,
                                'ward_id' => $ward1->id,
                                'role' => 'ward_chair',
                                'designation' => "Ward Chairperson ({$chairData['palika_name']} Ward 1)",
                                'is_active' => true,
                            ]
                        );
                    }
                }
            }
        }

        // Lalitpur & Bhaktapur Ward 1 Chairs
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
