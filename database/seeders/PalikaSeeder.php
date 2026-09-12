<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Palika;
use Illuminate\Database\Seeder;

class PalikaSeeder extends Seeder
{
    public function run(): void
    {
        $ktm = District::where('code', 'KTM')->first();
        $lal = District::where('code', 'LAL')->first();
        $bkt = District::where('code', 'BKT')->first();

        $palikas = [
            [
                'id' => 1,
                'district_id' => $ktm ? $ktm->id : 23,
                'name_en' => 'Kathmandu Metropolitan City',
                'name_ne' => 'काठमाडौँ महानगरपालिका',
                'type' => 'metropolitan',
                'code' => 'KMC',
            ],
            [
                'id' => 2,
                'district_id' => $lal ? $lal->id : 24,
                'name_en' => 'Lalitpur Metropolitan City',
                'name_ne' => 'ललितपुर महानगरपालिका',
                'type' => 'metropolitan',
                'code' => 'LMC',
            ],
            [
                'id' => 3,
                'district_id' => $bkt ? $bkt->id : 25,
                'name_en' => 'Bhaktapur Municipality',
                'name_ne' => 'भक्तपुर नगरपालिका',
                'type' => 'municipality',
                'code' => 'BKM',
            ],
        ];

        foreach ($palikas as $palika) {
            Palika::updateOrCreate(['code' => $palika['code']], $palika);
        }
    }
}
