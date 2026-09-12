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
        $kmc = Palika::where('code', 'KMC')->first();
        if (!$kmc) {
            return;
        }

        $ward32 = Ward::where('palika_id', $kmc->id)->where('ward_number', 32)->first();

        // 1. Ward Chair
        Staff::updateOrCreate(
            ['email' => 'chair@ward32.gov.np'],
            [
                'name' => 'Bharat Lal Shrestha (Ward Chair)',
                'phone' => '9851000001',
                'password' => Hash::make('password123'),
                'palika_id' => $kmc->id,
                'ward_id' => $ward32 ? $ward32->id : null,
                'role' => 'ward_chair',
                'is_active' => true,
            ]
        );

        // 2. Ward Secretary
        Staff::updateOrCreate(
            ['email' => 'secretary@ward32.gov.np'],
            [
                'name' => 'Sita Sharma (Ward Secretary)',
                'phone' => '9851000002',
                'password' => Hash::make('password123'),
                'palika_id' => $kmc->id,
                'ward_id' => $ward32 ? $ward32->id : null,
                'role' => 'secretary',
                'is_active' => true,
            ]
        );

        // 3. Ward Clerk
        Staff::updateOrCreate(
            ['email' => 'clerk@ward32.gov.np'],
            [
                'name' => 'Ramesh Adhikari (Front Desk Clerk)',
                'phone' => '9851000003',
                'password' => Hash::make('password123'),
                'palika_id' => $kmc->id,
                'ward_id' => $ward32 ? $ward32->id : null,
                'role' => 'clerk',
                'is_active' => true,
            ]
        );

        // 4. Palika Administrator
        Staff::updateOrCreate(
            ['email' => 'admin@kathmandu.gov.np'],
            [
                'name' => 'KMC IT Administrator',
                'phone' => '9851000000',
                'password' => Hash::make('password123'),
                'palika_id' => $kmc->id,
                'ward_id' => null, // Palika-level
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}
