<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $provinces = [
            ['id' => 1, 'name_en' => 'Koshi Province', 'name_ne' => 'कोशी प्रदेश', 'code' => 'P1'],
            ['id' => 2, 'name_en' => 'Madhesh Province', 'name_ne' => 'मधेश प्रदेश', 'code' => 'P2'],
            ['id' => 3, 'name_en' => 'Bagmati Province', 'name_ne' => 'बागमती प्रदेश', 'code' => 'P3'],
            ['id' => 4, 'name_en' => 'Gandaki Province', 'name_ne' => 'गण्डकी प्रदेश', 'code' => 'P4'],
            ['id' => 5, 'name_en' => 'Lumbini Province', 'name_ne' => 'लुम्बिनी प्रदेश', 'code' => 'P5'],
            ['id' => 6, 'name_en' => 'Karnali Province', 'name_ne' => 'कर्णाली प्रदेश', 'code' => 'P6'],
            ['id' => 7, 'name_en' => 'Sudurpashchim Province', 'name_ne' => 'सुदूरपश्चिम प्रदेश', 'code' => 'P7'],
        ];

        foreach ($provinces as $p) {
            Province::updateOrCreate(['id' => $p['id']], $p);
        }
    }
}
