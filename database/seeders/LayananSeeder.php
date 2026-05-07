<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layanans = [
            [
                'nama_layanan' => 'Nutrient',
                'deskripsi' => "Paket pemasangan sistem hidroponik entry-level cocok untuk pemula yang ingin memulai berkebun hidroponik di rumah. Paket ini mencakup:\n• Pemasangan sistem Wick sederhana (kapasitas 20 lubang tanam)\n• Instalasi pipa PVC dan netpot\n• Pengisian nutrisi AB Mix awal\n• Panduan penggunaan dasar (buku panduan)\n• Garansi instalasi 1 bulan",
                'harga' => 750000,
                'is_delete' => false,
            ],
            [
                'nama_layanan' => 'HydroBoost',
                'deskripsi' => "Paket pemasangan sistem hidroponik menengah untuk hobi serius dan skala rumahan yang lebih produktif. Paket ini mencakup semua layanan HydroBoost, ditambah:\n• Sistem NFT (Nutrient Film Technique) kapasitas 60 lubang tanam\n• Pompa sirkulasi otomatis dan timer\n• TDS Meter digital untuk monitoring nutrisi\n• Media tanam rockwool premium\n• Pelatihan langsung oleh teknisi berpengalaman (2 sesi)\n• Garansi instalasi 3 bulan\n• 1x kunjungan perawatan gratis",
                'harga' => 2500000,
                'is_delete' => false,
            ],
            [
                'nama_layanan' => 'GreenElite',
                'deskripsi' => "Paket pemasangan sistem hidroponik premium all-in-one untuk skala semi-komersial dan hasil panen maksimal. Paket ini mencakup semua layanan GreenElite, ditambah:\n• Sistem DFT/NFT kombinasi kapasitas 150 lubang tanam\n• Sistem irigasi otomatis berbasis timer digital\n• pH & EC meter profesional untuk kontrol nutrisi akurat\n• Lampu grow light LED full spectrum untuk pertumbuhan optimal\n• Paket nutrisi AB Mix lengkap 3 bulan pemakaian\n• Konsultasi desain tata letak kebun hidroponik\n• Pelatihan intensif 4 sesi bersama agronomis hidroponik\n• Garansi instalasi 6 bulan\n• Kunjungan perawatan rutin setiap bulan (3 bulan pertama)",
                'harga' => 7500000,
                'is_delete' => false,
            ],
        ];

        foreach ($layanans as $layanan) {
            $layanan['slug'] = Str::slug($layanan['nama_layanan']);
            $layanan['foto_layanan'] = [];
            Layanan::create($layanan);
        }
    }
}
