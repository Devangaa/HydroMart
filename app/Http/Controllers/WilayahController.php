<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Kecamatan;
use App\Models\Province;
use Illuminate\Routing\Controller;

/**
 * Modul: Referensi Wilayah
 * Fitur: Menyediakan data provinsi, kota, dan kecamatan untuk dropdown dinamis.
 */
class WilayahController extends Controller
{
    /**
     * Bagian: Ambil daftar kota berdasarkan provinsi.
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
     * Bagian: Ambil daftar kota untuk konteks transaksi.
     */
    public function getCitiesForTransaction($provinceId)
    {
        $cities = City::where('province_id', $provinceId)
            ->where('name', '!=', 'Kabupaten Administrasi Kepulauan Seribu')
            ->orderBy('name')
            ->select('id', 'name')
            ->get();

        return response()->json($cities);
    }

    /**
     * Bagian: Ambil daftar kecamatan berdasarkan kota.
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
     * Bagian: Ambil daftar kecamatan untuk konteks transaksi.
     */
    public function getKecamatanByTransactionCity($cityId)
    {
        $kecamatan = Kecamatan::where('city_id', $cityId)
            ->orderBy('name')
            ->select('id', 'name')
            ->get();

        return response()->json($kecamatan);
    }

    /**
     * Bagian: Ambil semua provinsi untuk akun/profil.
     */
    public function getProvinces()
    {
        $provinces = Province::orderBy('name')->select('id', 'name')->get();

        return response()->json($provinces);
    }

    /**
     * Bagian: Ambil provinsi yang diizinkan untuk transaksi.
     */
    public function getProvincesForTransaction()
    {
        $allowedProvinces = ['Banten', 'Daerah Khusus Ibukota Jakarta', 'Jawa Barat', 'Jawa Tengah', 'Daerah Istimewa Yogyakarta', 'Jawa Timur'];
        $provinces = Province::whereIn('name', $allowedProvinces)
            ->orderBy('name')
            ->select('id', 'name')
            ->get();

        return response()->json($provinces);
    }
}
