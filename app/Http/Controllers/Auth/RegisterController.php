<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register'); 
    }

    public function register(Request $request)
    {
        $request->validate([
            'username'     => [
                'required',
                'string',
                'min:5',
                'max:20',
                'alpha_dash',
                'unique:akun,username',
            ],
            'password'      => 'required|string|min:8|confirmed',
            'nama_lengkap'  => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:akun,email',
            'no_hp'         => 'required|numeric|unique:akun,no_hp|digits_between:10,15,',
            'alamat'        => 'required|string',
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah',
            'username.min'        => 'Username minimal harus 5 karakter.',
            'username.max'        => 'Username maksimal 20 karakter.',
            'email.unique'      => 'Email sudah terdaftar, gunakan email lain.',
            'username.unique'   => 'Username sudah diambil, gunakan username lain.',
            'no_hp.unique'      => 'Nomor sudah terdaftar, gunakan nomor lain.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
            'password.min'      => 'Password minimal harus 8 karakter.',
            'no_hp.numeric'     => 'Masukkan nomor telepon yang valid',
            'no_hp.digits_between' => 'Masukkan nomor telepon yang valid',
        ]);

        User::create([
            'username'          => $request->username,
            'password'          => Hash::make($request->password), 
            'role'              => 'pelanggan', 
            'nama_lengkap'      => $request->nama_lengkap,
            'email'             => $request->email,
            'no_hp'             => $request->no_hp,
            'alamat'            => $request->alamat,
            'tanggal_bergabung' => now(), 
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }
}