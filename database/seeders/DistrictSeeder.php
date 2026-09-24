<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $mapFile = database_path('data_district_map.json');
        if (!file_exists($mapFile)) {
            $this->command?->error("data_district_map.json not found!");
            return;
        }

        $districts = json_decode(file_get_contents($mapFile), true);

        foreach ($districts as $d) {
            District::updateOrCreate(
                ['code' => $d['code']],
                [
                    'province_id' => (int)$d['province_id'],
                    'name_en' => $d['name_en'],
                    'name_ne' => $d['name_ne'],
                ]
            );
        }

        $count = District::count();
        $this->command?->info("Successfully seeded {$count} districts across all 7 provinces of Nepal.");
    }
}
