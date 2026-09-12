<?php

namespace Database\Seeders;

use App\Models\Palika;
use App\Models\Ward;
use Illuminate\Database\Seeder;

class WardSeeder extends Seeder
{
    public function run(): void
    {
        $kmc = Palika::where('code', 'KMC')->first();
        if (!$kmc) {
            return;
        }

        // Seed 32 wards for Kathmandu Metropolitan City
        for ($w = 1; $w <= 32; $w++) {
            $isPilot = ($w === 32);
            Ward::updateOrCreate(
                [
                    'palika_id' => $kmc->id,
                    'ward_number' => $w,
                ],
                [
                    'office_address' => $isPilot ? 'Koteshwor, Kathmandu' : "Ward {$w} Office, Kathmandu",
                    'office_phone' => $isPilot ? '01-4601234' : "01-40000{$w}",
                    'office_email' => $isPilot ? 'ward32@kathmandu.gov.np' : "ward{$w}@kathmandu.gov.np",
                ]
            );
        }
    }
}
