<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'nama_produk' => 'Selada Keriting Hijau',
                'deskripsi' => 'Selada hidroponik segar, tanpa pestisida.',
                'harga' => 15000,
                'jumlah_stok' => 50,
                'kategori' => 'Sayuran',
                'berat' => 250,
                'unit' => 'Gram',
                'is_delete' => false,
            ],
            [
                'nama_produk' => 'Pakcoy Hidroponik',
                'deskripsi' => 'Pakcoy renyah kualitas premium.',
                'harga' => 12000,
                'jumlah_stok' => 5, // Ini akan memicu label 'Stok Menipis'
                'kategori' => 'Sayuran',
                'berat' => 300,
                'unit' => 'Gram',
                'is_delete' => false,
            ],
            [
                'nama_produk' => 'Nutrisi AB Mix Sayur',
                'deskripsi' => 'Nutrisi lengkap untuk pertumbuhan tanaman.',
                'harga' => 35000,
                'jumlah_stok' => 20,
                'kategori' => 'Nutrisi',
                'berat' => 1000,
                'unit' => 'Set',
                'is_delete' => false,
            ],
            [
                'nama_produk' => 'Netpot Hitam 5cm',
                'deskripsi' => 'Netpot tahan lama untuk sistem hidroponik.',
                'harga' => 1500,
                'jumlah_stok' => 200,
                'kategori' => 'Alat',
                'berat' => 10,
                'unit' => 'Pcs',
                'is_delete' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}