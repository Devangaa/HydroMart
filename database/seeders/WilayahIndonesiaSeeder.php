<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Kecamatan;
use App\Models\Province;
use Illuminate\Database\Seeder;

class WilayahIndonesiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sqlUrl = 'https://raw.githubusercontent.com/cahyadsn/wilayah/refs/heads/master/db/wilayah.sql';

        try {
            $sqlContent = file_get_contents($sqlUrl);

            if (! $sqlContent) {
                $this->command->error('Gagal download file SQL dari GitHub');

                return;
            }

            // Parse INSERT statements dari wilayah table
            $this->parseAndImport($sqlContent);

            $this->command->info('✓ Data wilayah berhasil diimport');

        } catch (\Exception $e) {
            $this->command->error('Error: '.$e->getMessage());
        }
    }

    private function parseAndImport($sqlContent): void
    {
        // Parse INSERT INTO wilayah (kode, nama) VALUES ...
        // Format: ('11','Aceh'),('11.01','Kabupaten Aceh Selatan'),('11.01.01','Bakongan')
        preg_match_all("/\('([^']+)','([^']*)'\)/", $sqlContent, $matches, PREG_SET_ORDER);

        $provinces = [];
        $cities = [];
        $kecamatans = [];

        foreach ($matches as $match) {
            $kode = $match[1];
            $nama = $match[2];

            // Handle escaped single quotes in nama
            $nama = str_replace("''", "'", $nama);

            // Identifikasi level hierarki berdasarkan jumlah titik
            $parts = explode('.', $kode);

            if (count($parts) === 1) {
                // Provinsi: '11' (2 digit)
                $id = (int) $kode;
                $provinces[$id] = $nama;

                if (! Province::where('id', $id)->exists()) {
                    Province::create(['id' => $id, 'name' => $nama]);
                }

            } elseif (count($parts) === 2) {
                // Kabupaten/Kota: '11.01' (2.2 format)
                $provinceId = (int) $parts[0];
                $cityId = (int) ($parts[0].sprintf('%02d', $parts[1]));
                $cities[$cityId] = ['name' => $nama, 'province_id' => $provinceId];

                if (! City::where('id', $cityId)->exists()) {
                    City::create([
                        'id' => $cityId,
                        'province_id' => $provinceId,
                        'name' => $nama,
                    ]);
                }

            } elseif (count($parts) === 3) {
                // Kecamatan: '11.01.01' (2.2.2 format)
                $cityId = (int) ($parts[0].sprintf('%02d', $parts[1]));
                $kecamatanId = (int) ($parts[0].sprintf('%02d', $parts[1]).sprintf('%02d', $parts[2]));
                $kecamatans[$kecamatanId] = ['name' => $nama, 'city_id' => $cityId];

                if (! Kecamatan::where('id', $kecamatanId)->exists()) {
                    Kecamatan::create([
                        'id' => $kecamatanId,
                        'city_id' => $cityId,
                        'name' => $nama,
                    ]);
                }
            }
        }

        $this->command->line('✓ '.count($provinces).' Provinsi diimport');
        $this->command->line('✓ '.count($cities).' Kota/Kabupaten diimport');
        $this->command->line('✓ '.count($kecamatans).' Kecamatan diimport');
    }
}
