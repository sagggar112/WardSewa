<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            // Koshi (P1)
            ['province_id' => 1, 'name_en' => 'Bhojpur', 'name_ne' => 'भोजपुर', 'code' => 'BHO'],
            ['province_id' => 1, 'name_en' => 'Dhankuta', 'name_ne' => 'धनकुटा', 'code' => 'DHA'],
            ['province_id' => 1, 'name_en' => 'Ilam', 'name_ne' => 'इलाम', 'code' => 'ILA'],
            ['province_id' => 1, 'name_en' => 'Jhapa', 'name_ne' => 'झापा', 'code' => 'JHA'],
            ['province_id' => 1, 'name_en' => 'Khotang', 'name_ne' => 'खोटाङ', 'code' => 'KHO'],
            ['province_id' => 1, 'name_en' => 'Morang', 'name_ne' => 'मोरङ', 'code' => 'MOR'],
            ['province_id' => 1, 'name_en' => 'Okhaldhunga', 'name_ne' => 'ओखलढुङ्गा', 'code' => 'OKH'],
            ['province_id' => 1, 'name_en' => 'Panchthar', 'name_ne' => 'पाँचथर', 'code' => 'PAN'],
            ['province_id' => 1, 'name_en' => 'Sankhuwasabha', 'name_ne' => 'संखुवासभा', 'code' => 'SAN'],
            ['province_id' => 1, 'name_en' => 'Solukhumbu', 'name_ne' => 'सोलुखुम्बु', 'code' => 'SOL'],
            ['province_id' => 1, 'name_en' => 'Sunsari', 'name_ne' => 'सुनसरी', 'code' => 'SUN'],
            ['province_id' => 1, 'name_en' => 'Taplejung', 'name_ne' => 'ताप्लेजुङ', 'code' => 'TAP'],
            ['province_id' => 1, 'name_en' => 'Terhathum', 'name_ne' => 'तेह्रथुम', 'code' => 'TER'],
            ['province_id' => 1, 'name_en' => 'Udayapur', 'name_ne' => 'उदयपुर', 'code' => 'UDA'],

            // Madhesh (P2)
            ['province_id' => 2, 'name_en' => 'Saptari', 'name_ne' => 'सप्तरी', 'code' => 'SAP'],
            ['province_id' => 2, 'name_en' => 'Siraha', 'name_ne' => 'सिराहा', 'code' => 'SIR'],
            ['province_id' => 2, 'name_en' => 'Dhanusha', 'name_ne' => 'धनुषा', 'code' => 'DHN'],
            ['province_id' => 2, 'name_en' => 'Mahottari', 'name_ne' => 'महोत्तरी', 'code' => 'MAH'],
            ['province_id' => 2, 'name_en' => 'Sarlahi', 'name_ne' => 'सर्लाही', 'code' => 'SAR'],
            ['province_id' => 2, 'name_en' => 'Rautahat', 'name_ne' => 'रौतहट', 'code' => 'RAU'],
            ['province_id' => 2, 'name_en' => 'Bara', 'name_ne' => 'बारा', 'code' => 'BAR'],
            ['province_id' => 2, 'name_en' => 'Parsa', 'name_ne' => 'पर्सा', 'code' => 'PAR'],

            // Bagmati (P3)
            ['province_id' => 3, 'name_en' => 'Kathmandu', 'name_ne' => 'काठमाडौँ', 'code' => 'KTM'],
            ['province_id' => 3, 'name_en' => 'Lalitpur', 'name_ne' => 'ललितपुर', 'code' => 'LAL'],
            ['province_id' => 3, 'name_en' => 'Bhaktapur', 'name_ne' => 'भक्तपुर', 'code' => 'BKT'],
            ['province_id' => 3, 'name_en' => 'Kavrepalanchok', 'name_ne' => 'काभ्रेपलाञ्चोक', 'code' => 'KAV'],
            ['province_id' => 3, 'name_en' => 'Sindhupalchok', 'name_ne' => 'सिन्धुपाल्चोक', 'code' => 'SIN'],
            ['province_id' => 3, 'name_en' => 'Ramechhap', 'name_ne' => 'रामेछाप', 'code' => 'RAM'],
            ['province_id' => 3, 'name_en' => 'Dolakha', 'name_ne' => 'दोलखा', 'code' => 'DOL'],
            ['province_id' => 3, 'name_en' => 'Dhading', 'name_ne' => 'धादिङ', 'code' => 'DHD'],
            ['province_id' => 3, 'name_en' => 'Nuwakot', 'name_ne' => 'नुवाकोट', 'code' => 'NUW'],
            ['province_id' => 3, 'name_en' => 'Rasuwa', 'name_ne' => 'रसुवा', 'code' => 'RAS'],
            ['province_id' => 3, 'name_en' => 'Makwanpur', 'name_ne' => 'मकवानपुर', 'code' => 'MAK'],
            ['province_id' => 3, 'name_en' => 'Chitwan', 'name_ne' => 'चितवन', 'code' => 'CHI'],
            ['province_id' => 3, 'name_en' => 'Sindhuli', 'name_ne' => 'सिन्धुली', 'code' => 'SID'],

            // Gandaki (P4)
            ['province_id' => 4, 'name_en' => 'Kaski', 'name_ne' => 'कास्की', 'code' => 'KAS'],
            ['province_id' => 4, 'name_en' => 'Tanahun', 'name_ne' => 'तनहुँ', 'code' => 'TAN'],
            ['province_id' => 4, 'name_en' => 'Gorkha', 'name_ne' => 'गोरखा', 'code' => 'GOR'],
            ['province_id' => 4, 'name_en' => 'Lamjung', 'name_ne' => 'लमजुङ', 'code' => 'LAM'],
            ['province_id' => 4, 'name_en' => 'Syangja', 'name_ne' => 'स्याङ्जा', 'code' => 'SYA'],

            // Lumbini (P5)
            ['province_id' => 5, 'name_en' => 'Rupandehi', 'name_ne' => 'रुपन्देही', 'code' => 'RUP'],
            ['province_id' => 5, 'name_en' => 'Kapilvastu', 'name_ne' => 'कपिलवस्तु', 'code' => 'KAP'],
            ['province_id' => 5, 'name_en' => 'Dang', 'name_ne' => 'दाङ', 'code' => 'DAN'],
            ['province_id' => 5, 'name_en' => 'Banke', 'name_ne' => 'बाँके', 'code' => 'BAN'],

            // Karnali (P6)
            ['province_id' => 6, 'name_en' => 'Surkhet', 'name_ne' => 'सुर्खेत', 'code' => 'SUR'],
            ['province_id' => 6, 'name_en' => 'Jumla', 'name_ne' => 'जुम्ला', 'code' => 'JUM'],

            // Sudurpashchim (P7)
            ['province_id' => 7, 'name_en' => 'Kailali', 'name_ne' => 'कैलाली', 'code' => 'KAI'],
            ['province_id' => 7, 'name_en' => 'Kanchanpur', 'name_ne' => 'कञ्चनपुर', 'code' => 'KAN'],
        ];

        foreach ($districts as $d) {
            District::updateOrCreate(['code' => $d['code']], $d);
        }
    }
}
