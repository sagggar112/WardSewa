<?php

namespace Database\Seeders;

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

        // ==========================================
        // 1. KATHMANDU METROPOLITAN CITY (KMC)
        // ==========================================
        $kmc = Palika::where('code', 'KMC')->first();
        if ($kmc) {
            $ward32 = Ward::where('palika_id', $kmc->id)->where('ward_number', 32)->first();

            // Ward Chair - Ward 32 (Koteshwor)
            Staff::updateOrCreate(
                ['email' => 'chair@ward32.gov.np'],
                [
                    'name' => 'Bharat Lal Shrestha (KMC Ward 32 Chair)',
                    'phone' => '9851000001',
                    'password' => $password,
                    'palika_id' => $kmc->id,
                    'ward_id' => $ward32?->id,
                    'role' => 'ward_chair',
                    'is_active' => true,
                ]
            );

            // Ward Secretary - Ward 32
            Staff::updateOrCreate(
                ['email' => 'secretary@ward32.gov.np'],
                [
                    'name' => 'Sita Sharma (KMC Ward 32 Secretary)',
                    'phone' => '9851000002',
                    'password' => $password,
                    'palika_id' => $kmc->id,
                    'ward_id' => $ward32?->id,
                    'role' => 'secretary',
                    'is_active' => true,
                ]
            );

            // Ward Clerk - Ward 32
            Staff::updateOrCreate(
                ['email' => 'clerk@ward32.gov.np'],
                [
                    'name' => 'Ramesh Adhikari (KMC Ward 32 Clerk)',
                    'phone' => '9851000003',
                    'password' => $password,
                    'palika_id' => $kmc->id,
                    'ward_id' => $ward32?->id,
                    'role' => 'clerk',
                    'is_active' => true,
                ]
            );

            // KMC Palika Administrator
            Staff::updateOrCreate(
                ['email' => 'admin@kathmandu.gov.np'],
                [
                    'name' => 'KMC IT Administrator',
                    'phone' => '9851000000',
                    'password' => $password,
                    'palika_id' => $kmc->id,
                    'ward_id' => null,
                    'role' => 'admin',
                    'is_active' => true,
                ]
            );
        }

        // ==========================================
        // 2. LALITPUR METROPOLITAN CITY (LMC)
        // ==========================================
        $lmc = Palika::where('code', 'LMC')->first();
        if ($lmc) {
            $lmcWard1 = Ward::where('palika_id', $lmc->id)->where('ward_number', 1)->first();

            // LMC Palika Administrator
            Staff::updateOrCreate(
                ['email' => 'admin@lalitpur.gov.np'],
                [
                    'name' => 'Lalitpur Metro IT Administrator',
                    'phone' => '9851100000',
                    'password' => $password,
                    'palika_id' => $lmc->id,
                    'ward_id' => null,
                    'role' => 'admin',
                    'is_active' => true,
                ]
            );

            // LMC Ward 1 Chair (Kupandole)
            Staff::updateOrCreate(
                ['email' => 'chair@lmc1.gov.np'],
                [
                    'name' => 'Bikram Maharjan (LMC Ward 1 Chair)',
                    'phone' => '9851100001',
                    'password' => $password,
                    'palika_id' => $lmc->id,
                    'ward_id' => $lmcWard1?->id,
                    'role' => 'ward_chair',
                    'is_active' => true,
                ]
            );
        }

        // ==========================================
        // 3. BHAKTAPUR MUNICIPALITY (BKM)
        // ==========================================
        $bkm = Palika::where('code', 'BKM')->first();
        if ($bkm) {
            $bkmWard1 = Ward::where('palika_id', $bkm->id)->where('ward_number', 1)->first();

            // BKM Palika Administrator
            Staff::updateOrCreate(
                ['email' => 'admin@bhaktapur.gov.np'],
                [
                    'name' => 'Bhaktapur Mun IT Administrator',
                    'phone' => '9851200000',
                    'password' => $password,
                    'palika_id' => $bkm->id,
                    'ward_id' => null,
                    'role' => 'admin',
                    'is_active' => true,
                ]
            );

            // BKM Ward 1 Chair (Durbar Square)
            Staff::updateOrCreate(
                ['email' => 'chair@bkm1.gov.np'],
                [
                    'name' => 'Sunil Prajapati (BKM Ward 1 Chair)',
                    'phone' => '9851200001',
                    'password' => $password,
                    'palika_id' => $bkm->id,
                    'ward_id' => $bkmWard1?->id,
                    'role' => 'ward_chair',
                    'is_active' => true,
                ]
            );
        }
    }
}
