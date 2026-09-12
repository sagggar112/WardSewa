<?php

namespace Database\Seeders;

use App\Models\Citizen;
use App\Models\Palika;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CitizenSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        $citizensData = [
            // 1. Kathmandu Metropolitan City (KMC Ward 32 - Koteshwor)
            [
                'phone' => '9841000000',
                'full_name' => 'Ram Bahadur Shrestha',
                'email' => 'ram.shrestha@example.com',
                'citizenship_no' => '27-01-72-00123',
                'palika_code' => 'KMC',
                'ward_number' => 32,
                'address' => 'Koteshwor, Kathmandu',
            ],
            // 2. Chandragiri Municipality (CGM Ward 1 - Dahachok)
            [
                'phone' => '9841000001',
                'full_name' => 'Bikash Giri',
                'email' => 'bikash.giri@example.com',
                'citizenship_no' => '27-01-74-00234',
                'palika_code' => 'CGM',
                'ward_number' => 1,
                'address' => 'Dahachok, Chandragiri',
            ],
            // 3. Budhanilkantha Municipality (BNM Ward 3 - Mandir)
            [
                'phone' => '9841000002',
                'full_name' => 'Sujata Adhikari',
                'email' => 'sujata.adhikari@example.com',
                'citizenship_no' => '27-01-75-00345',
                'palika_code' => 'BNM',
                'ward_number' => 3,
                'address' => 'Budhanilkantha, Kathmandu',
            ],
            // 4. Kirtipur Municipality (KRM Ward 1 - Baghbhairab)
            [
                'phone' => '9841000003',
                'full_name' => 'Ranjan Maharjan',
                'email' => 'ranjan.maharjan@example.com',
                'citizenship_no' => '27-01-76-00456',
                'palika_code' => 'KRM',
                'ward_number' => 1,
                'address' => 'Baghbhairab, Kirtipur',
            ],
            // 5. Tokha Municipality (TKM Ward 2 - Chandeshwori)
            [
                'phone' => '9841000004',
                'full_name' => 'Dipendra Shrestha',
                'email' => 'dipendra.shrestha@example.com',
                'citizenship_no' => '27-01-77-00567',
                'palika_code' => 'TKM',
                'ward_number' => 2,
                'address' => 'Tokha Chandeshwori, Kathmandu',
            ],
            // 6. Tarakeshwor Municipality (TRM Ward 8 - Manamaiju)
            [
                'phone' => '9841000005',
                'full_name' => 'Gita Sharma',
                'email' => 'gita.sharma@example.com',
                'citizenship_no' => '27-01-78-00678',
                'palika_code' => 'TRM',
                'ward_number' => 8,
                'address' => 'Manamaiju, Tarakeshwor',
            ],
            // 7. Lalitpur Metropolitan City (LMC Ward 1 - Kupandole)
            [
                'phone' => '9851000000',
                'full_name' => 'Aayush Maharjan',
                'email' => 'aayush.maharjan@example.com',
                'citizenship_no' => '27-02-74-00456',
                'palika_code' => 'LMC',
                'ward_number' => 1,
                'address' => 'Kupandole, Lalitpur',
            ],
            // 8. Bhaktapur Municipality (BKM Ward 1 - Durbar Square)
            [
                'phone' => '9861000000',
                'full_name' => 'Sunita Prajapati',
                'email' => 'sunita.prajapati@example.com',
                'citizenship_no' => '27-03-75-00789',
                'palika_code' => 'BKM',
                'ward_number' => 1,
                'address' => 'Durbar Square, Bhaktapur',
            ],
        ];

        foreach ($citizensData as $data) {
            $palika = Palika::where('code', $data['palika_code'])->first();
            $ward = $palika ? Ward::where('palika_id', $palika->id)->where('ward_number', $data['ward_number'])->first() : null;

            Citizen::updateOrCreate(
                ['phone' => $data['phone']],
                [
                    'full_name' => $data['full_name'],
                    'email' => $data['email'],
                    'password' => $password,
                    'citizenship_no' => $data['citizenship_no'],
                    'ward_id' => $ward?->id,
                    'address' => $data['address'],
                    'is_verified' => true,
                ]
            );
        }
    }
}
