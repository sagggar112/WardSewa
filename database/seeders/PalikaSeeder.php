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

        $ktmId = $ktm ? $ktm->id : 23;
        $lalId = $lal ? $lal->id : 24;
        $bktId = $bkt ? $bkt->id : 25;

        $palikas = [
            // ========================================================
            // 1. KATHMANDU DISTRICT (11 Palikas: 1 Metro + 10 Municipalities)
            // ========================================================
            [
                'district_id' => $ktmId,
                'name_en' => 'Kathmandu Metropolitan City',
                'name_ne' => 'काठमाडौँ महानगरपालिका',
                'type' => 'metropolitan',
                'code' => 'KMC',
            ],
            [
                'district_id' => $ktmId,
                'name_en' => 'Kageshwori Manohara Municipality',
                'name_ne' => 'कागेश्वरी मनोहरा नगरपालिका',
                'type' => 'municipality',
                'code' => 'KMM',
            ],
            [
                'district_id' => $ktmId,
                'name_en' => 'Kirtipur Municipality',
                'name_ne' => 'कीर्तिपुर नगरपालिका',
                'type' => 'municipality',
                'code' => 'KRM',
            ],
            [
                'district_id' => $ktmId,
                'name_en' => 'Gokarneshwor Municipality',
                'name_ne' => 'गोकर्णेश्वर नगरपालिका',
                'type' => 'municipality',
                'code' => 'GKM',
            ],
            [
                'district_id' => $ktmId,
                'name_en' => 'Chandragiri Municipality',
                'name_ne' => 'चन्द्रागिरि नगरपालिका',
                'type' => 'municipality',
                'code' => 'CGM',
            ],
            [
                'district_id' => $ktmId,
                'name_en' => 'Tokha Municipality',
                'name_ne' => 'टोखा नगरपालिका',
                'type' => 'municipality',
                'code' => 'TKM',
            ],
            [
                'district_id' => $ktmId,
                'name_en' => 'Tarakeshwor Municipality',
                'name_ne' => 'तारकेश्वर नगरपालिका',
                'type' => 'municipality',
                'code' => 'TRM',
            ],
            [
                'district_id' => $ktmId,
                'name_en' => 'Dakshinkali Municipality',
                'name_ne' => 'दक्षिणकाली नगरपालिका',
                'type' => 'municipality',
                'code' => 'DKM',
            ],
            [
                'district_id' => $ktmId,
                'name_en' => 'Nagarjun Municipality',
                'name_ne' => 'नागार्जुन नगरपालिका',
                'type' => 'municipality',
                'code' => 'NJM',
            ],
            [
                'district_id' => $ktmId,
                'name_en' => 'Budhanilkantha Municipality',
                'name_ne' => 'बूढानीलकण्ठ नगरपालिका',
                'type' => 'municipality',
                'code' => 'BNM',
            ],
            [
                'district_id' => $ktmId,
                'name_en' => 'Shankharapur Municipality',
                'name_ne' => 'शंखरापुर नगरपालिका',
                'type' => 'municipality',
                'code' => 'SKM',
            ],

            // ========================================================
            // 2. LALITPUR DISTRICT (6 Palikas: 1 Metro + 2 Municipalities + 3 Rural)
            // ========================================================
            [
                'district_id' => $lalId,
                'name_en' => 'Lalitpur Metropolitan City',
                'name_ne' => 'ललितपुर महानगरपालिका',
                'type' => 'metropolitan',
                'code' => 'LMC',
            ],
            [
                'district_id' => $lalId,
                'name_en' => 'Mahalaxmi Municipality',
                'name_ne' => 'महालक्ष्मी नगरपालिका',
                'type' => 'municipality',
                'code' => 'MLM',
            ],
            [
                'district_id' => $lalId,
                'name_en' => 'Godawari Municipality',
                'name_ne' => 'गोदावरी नगरपालिका',
                'type' => 'municipality',
                'code' => 'GDM',
            ],
            [
                'district_id' => $lalId,
                'name_en' => 'Konjyosom Rural Municipality',
                'name_ne' => 'कोन्ज्योसोम गाउँपालिका',
                'type' => 'rural_municipality',
                'code' => 'KJM',
            ],
            [
                'district_id' => $lalId,
                'name_en' => 'Bagmati Rural Municipality',
                'name_ne' => 'बागमती गाउँपालिका',
                'type' => 'rural_municipality',
                'code' => 'BRM',
            ],
            [
                'district_id' => $lalId,
                'name_en' => 'Mahankal Rural Municipality',
                'name_ne' => 'महाङ्काल गाउँपालिका',
                'type' => 'rural_municipality',
                'code' => 'MHM',
            ],

            // ========================================================
            // 3. BHAKTAPUR DISTRICT (4 Palikas: 4 Municipalities)
            // ========================================================
            [
                'district_id' => $bktId,
                'name_en' => 'Bhaktapur Municipality',
                'name_ne' => 'भक्तपुर नगरपालिका',
                'type' => 'municipality',
                'code' => 'BKM',
            ],
            [
                'district_id' => $bktId,
                'name_en' => 'Madhyapur Thimi Municipality',
                'name_ne' => 'मध्यपुर थिमी नगरपालिका',
                'type' => 'municipality',
                'code' => 'MTM',
            ],
            [
                'district_id' => $bktId,
                'name_en' => 'Suryabinayak Municipality',
                'name_ne' => 'सूर्यविनायक नगरपालिका',
                'type' => 'municipality',
                'code' => 'SVM',
            ],
            [
                'district_id' => $bktId,
                'name_en' => 'Changunarayan Municipality',
                'name_ne' => 'चाँगुनारायण नगरपालिका',
                'type' => 'municipality',
                'code' => 'CNM',
            ],
        ];

        foreach ($palikas as $palika) {
            Palika::updateOrCreate(['code' => $palika['code']], $palika);
        }
    }
}
