<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Kecamatan;
use App\Models\Province;

class WilayahController extends Controller
{
    /**
     * Get cities by province_id
     */
    public function getCitiesByProvince($provinceId)
    {
        $cities = City::where('province_id', $provinceId)
            ->orderBy('name')
            ->select('id', 'name')
            ->get();

        return response()->json($cities);
    }

    /**
     * Get kecamatan by city_id
     */
    public function getKecamatanByCity($cityId)
    {
        $kecamatan = Kecamatan::where('city_id', $cityId)
            ->orderBy('name')
            ->select('id', 'name')
            ->get();

        return response()->json($kecamatan);
    }

    /**
     * Get all provinces (untuk initial dropdown)
     */
    public function getProvinces()
    {
        $provinces = Province::orderBy('name')->select('id', 'name')->get();

        return response()->json($provinces);
    }
}
