<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama_lengkap' => 'Administrator HydroMart',
                'email' => 'admin-utama@hydromart.id',
                'no_hp' => '080011112222',
                'password' => Hash::make('123'),
                'alamat' => 'Kantor Pusat HydroMart, Bandung',
                'kecamatan_id' => 352212,
                'role' => 'admin',
                'tanggal_bergabung' => now(),
            ]
        );
    }
}
