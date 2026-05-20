<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Modul: Authentication
 * Fitur: Login dan logout pengguna.
 */
class LoginController extends Controller
{
    /**
     * Bagian: Halaman form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Bagian: Proses autentikasi login.
     * Alur: validasi kredensial -> cek username case-sensitive -> redirect per role.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // Check case-sensitive username
            if (Auth::user()->username !== $request->username) {
                Auth::logout();

                return back()->withErrors([
                    'username' => 'Username atau password salah.',
                ])->onlyInput('username');
            }

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/dashboard');
            } else {
                return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Bagian: Proses logout dan invalidasi sesi.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
