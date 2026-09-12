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

        // 1. Kathmandu Citizen (KMC Ward 32 - Koteshwor)
        $kmc = Palika::where('code', 'KMC')->first();
        $kmcWard32 = $kmc ? Ward::where('palika_id', $kmc->id)->where('ward_number', 32)->first() : null;

        Citizen::updateOrCreate(
            ['phone' => '9841000000'],
            [
                'full_name' => 'Ram Bahadur Shrestha',
                'email' => 'ram.shrestha@example.com',
                'password' => $password,
                'citizenship_no' => '27-01-72-00123',
                'ward_id' => $kmcWard32?->id,
                'address' => 'Koteshwor, Kathmandu',
                'is_verified' => true,
            ]
        );

        // 2. Lalitpur Citizen (LMC Ward 1 - Kupandole)
        $lmc = Palika::where('code', 'LMC')->first();
        $lmcWard1 = $lmc ? Ward::where('palika_id', $lmc->id)->where('ward_number', 1)->first() : null;

        Citizen::updateOrCreate(
            ['phone' => '9851000000'],
            [
                'full_name' => 'Aayush Maharjan',
                'email' => 'aayush.maharjan@example.com',
                'password' => $password,
                'citizenship_no' => '27-02-74-00456',
                'ward_id' => $lmcWard1?->id,
                'address' => 'Kupandole, Lalitpur',
                'is_verified' => true,
            ]
        );

        // 3. Bhaktapur Citizen (BKM Ward 1 - Durbar Square)
        $bkm = Palika::where('code', 'BKM')->first();
        $bkmWard1 = $bkm ? Ward::where('palika_id', $bkm->id)->where('ward_number', 1)->first() : null;

        Citizen::updateOrCreate(
            ['phone' => '9861000000'],
            [
                'full_name' => 'Sunita Prajapati',
                'email' => 'sunita.prajapati@example.com',
                'password' => $password,
                'citizenship_no' => '27-03-75-00789',
                'ward_id' => $bkmWard1?->id,
                'address' => 'Durbar Square, Bhaktapur',
                'is_verified' => true,
            ]
        );
    }
}
