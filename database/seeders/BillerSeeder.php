<?php

namespace Database\Seeders;

use App\Models\Biller;
use Illuminate\Database\Seeder;

class BillerSeeder extends Seeder
{
    public function run(): void
    {
        $billers = [
            [
                'name_en' => 'Nepal Electricity Authority (NEA)',
                'name_ne' => 'नेपाल विद्युत प्राधिकरण',
                'category' => 'electricity',
                'code' => 'NEA',
                'logo_url' => '/images/billers/nea.png',
                'api_config' => ['sc_code' => 'KTM_BANESHWOR'],
                'is_active' => true,
            ],
            [
                'name_en' => 'Kathmandu Upatyaka Khanepani Limited (KUKL)',
                'name_ne' => 'काठमाडौँ उपत्यका खानेपानी लिमिटेड',
                'category' => 'water',
                'code' => 'KUKL',
                'logo_url' => '/images/billers/kukl.png',
                'api_config' => ['branch_id' => 'KTM_MAHARAJGUNJ'],
                'is_active' => true,
            ],
            [
                'name_en' => 'WorldLink Communications',
                'name_ne' => 'वर्ल्डलिङ्क कम्युनिकेसन्स',
                'category' => 'internet',
                'code' => 'WLINK',
                'logo_url' => '/images/billers/worldlink.png',
                'api_config' => [],
                'is_active' => true,
            ],
            [
                'name_en' => 'Ward 32 Property & Business Tax',
                'name_ne' => 'वडा नं ३२ सम्पत्ति तथा व्यवसाय कर',
                'category' => 'ward_tax',
                'code' => 'WARD32_TAX',
                'logo_url' => '/images/billers/gov.png',
                'api_config' => ['ward' => 32],
                'is_active' => true,
            ],
        ];

        foreach ($billers as $biller) {
            Biller::updateOrCreate(['code' => $biller['code']], $biller);
        }
    }
}
