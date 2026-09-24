<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Palika;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\JsonResponse;

class GeographyController extends Controller
{
    /**
     * Get all 7 provinces.
     */
    public function provinces(): JsonResponse
    {
        $provinces = Province::orderBy('id')->get(['id', 'name_en', 'name_ne', 'code']);

        return response()->json($provinces);
    }

    /**
     * Get districts for a given province.
     */
    public function districts(int $provinceId): JsonResponse
    {
        $districts = District::where('province_id', $provinceId)
            ->orderBy('name_en')
            ->get(['id', 'province_id', 'name_en', 'name_ne', 'code']);

        return response()->json($districts);
    }

    /**
     * Get palikas for a given district.
     */
    public function palikas(int $districtId): JsonResponse
    {
        $palikas = Palika::where('district_id', $districtId)
            ->orderBy('type')
            ->orderBy('name_en')
            ->get(['id', 'district_id', 'name_en', 'name_ne', 'type', 'code']);

        return response()->json($palikas);
    }

    /**
     * Get wards for a given palika.
     */
    public function wards(int $palikaId): JsonResponse
    {
        $wards = Ward::where('palika_id', $palikaId)
            ->orderBy('ward_number')
            ->get(['id', 'palika_id', 'ward_number', 'office_address', 'office_phone', 'office_email']);

        return response()->json($wards);
    }
}
