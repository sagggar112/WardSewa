<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Palika;
use Illuminate\Database\Seeder;

class PalikaSeeder extends Seeder
{
    public function run(): void
    {
        $mapFile = database_path('data_palika_map.json');
        if (!file_exists($mapFile)) {
            $this->command?->error("data_palika_map.json not found!");
            return;
        }

        $palikas = json_decode(file_get_contents($mapFile), true);

        // Preload districts by code for performance
        $districtsByCode = District::all()->keyBy('code');

        $seededCount = 0;
        foreach ($palikas as $p) {
            $district = $districtsByCode->get($p['district_code']);
            if (!$district) {
                continue;
            }

            Palika::updateOrCreate(
                ['code' => $p['code']],
                [
                    'district_id' => $district->id,
                    'name_en' => $p['name_en'],
                    'name_ne' => $p['name_ne'],
                    'type' => $p['type'],
                ]
            );
            $seededCount++;
        }

        $total = Palika::count();
        $this->command?->info("Successfully seeded {$seededCount} palikas (Total in DB: {$total}) across all 77 districts of Nepal.");
    }
}
