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
                'email' => 'hydromartjbr@gmail.com',
                'no_hp' => '080011112222',
                'password' => Hash::make('123'),
                'alamat' => 'Kantor Pusat HydroMart, Bandung',
                'kecamatan_id' => 352212,
                'role' => 'admin',
                'tanggal_bergabung' => now(),
            ]
        );
        User::updateOrCreate(
            ['username' => 'derrick'],
            [
                'nama_lengkap' => 'Derrick Timothy',
                'email' => 'derricktimothyd.j@gmail.com',
                'no_hp' => '085707878470',
                'password' => Hash::make('derrick123'),
                'alamat' => 'Jl. Karimata',
                'kecamatan_id' => 350921,
                'role' => 'pelanggan',
                'tanggal_bergabung' => now(),
            ]
        );
        User::updateOrCreate(
            ['username' => 'meyyy'],
            [
                'nama_lengkap' => 'Mey',
                'email' => 'meyyy@gmail.com',
                'no_hp' => '083325237523',
                'password' => Hash::make('12345678'),
                'alamat' => 'Unej',
                'kecamatan_id' => 350921,
                'role' => 'pelanggan',
                'tanggal_bergabung' => now(),
            ]
        );
        User::updateOrCreate(
            ['username' => 'farhan'],
            [
                'nama_lengkap' => 'Farhan',
                'email' => 'farhanganteng@gmail.com',
                'no_hp' => '083252337524',
                'password' => Hash::make('farhan123'),
                'alamat' => 'Unej',
                'kecamatan_id' => 350921,
                'role' => 'pelanggan',
                'tanggal_bergabung' => now(),
            ]
        );
        User::updateOrCreate(
            ['username' => 'nopirawon'],
            [
                'nama_lengkap' => 'nopi rawon',
                'email' => 'nopirawon@gmail.com',
                'no_hp' => '083252375245',
                'password' => Hash::make('12345678'),
                'alamat' => 'Unej',
                'kecamatan_id' => 350921,
                'role' => 'pelanggan',
                'tanggal_bergabung' => now(),
            ]
        );
    }
}
