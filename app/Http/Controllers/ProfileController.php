<?php

namespace App\Http\Controllers;

use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function edit() 
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $oldUsername = $user->username;

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username'     => [
                'required',
                'string',
                'min:5',
                'max:20',
                'alpha_dash',
                'unique:akun,username,' . $oldUsername . ',username'
            ],
            'email'        => 'required|email|max:255|unique:akun,email,' . $oldUsername . ',username',
            'no_hp'        => 'required|numeric|digits_between:10,15|unique:akun,no_hp,' . $oldUsername . ',username',
            'alamat'       => 'required|string',
            'provinsi'     => 'required|integer|exists:provinces,id',
            'kota'         => 'required|integer|exists:cities,id',
            'kecamatan'    => 'required|integer|exists:kecamatans,id',
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah',
            'username.min'        => 'Username minimal harus 5 karakter.',
            'username.max'        => 'Username maksimal 20 karakter.',
            'email.unique'      => 'Email sudah terdaftar, gunakan email lain.',
            'username.unique'   => 'Username sudah diambil, gunakan username lain.',
            'no_hp.unique'      => 'Nomor sudah terdaftar, gunakan nomor lain.',
            'no_hp.numeric'     => 'Masukkan nomor telepon yang valid',
            'no_hp.digits_between' => 'Masukkan nomor telepon yang valid',
            'provinsi.exists'   => 'Provinsi tidak valid, pilih dari daftar.',
            'kota.exists'       => 'Kota tidak valid, pilih dari daftar.',
            'kecamatan.exists'  => 'Kecamatan tidak valid, pilih dari daftar.',
        ]);

        User::where('username', $oldUsername)->update([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'no_hp'        => $request->no_hp,
            'alamat'       => $request->alamat,
            'kecamatan_id' => $request->kecamatan,
        ]);

        $newUser = User::where('username', $request->username)->first();
    
        Auth::login($newUser);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'Password lama yang Anda masukkan salah.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password baru minimal 8 karakter.',
        ]);
        
        User::where('username', $user->username)->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}