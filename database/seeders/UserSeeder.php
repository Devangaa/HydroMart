<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan Model User sudah benar
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('akun')->insert([
            'nama_lengkap' => 'Administrator HydroMart',
            'email'        => 'admin@hydromart.id',
            'no_hp'        => '081234567890',
            'username'     => 'admin',
            'password'     => Hash::make('123'), // Password: 123
            'alamat'       => 'Kantor Pusat HydroMart, Bandung',
            'role'         => 'admin',
            'tanggal_bergabung' => now(),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }
}