<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Langkah 1: Kirim OTP
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email'],
            ['email.email' => 'Format email tidak valid.']);

        // Cek apakah user terdaftar
        if (! User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'Email tidak terdaftar!'], 404);
        }

        $otp = rand(100000, 999999);

        // Simpan atau Update OTP di tabel
        EmailOtp::updateOrCreate(
            ['email' => $request->email, 'type' => 'forgot_password'],
            ['otp' => $otp, 'expires_at' => Carbon::now()->addMinutes(10)]
        );

        // Kirim Email
        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json(['message' => 'Kode OTP berhasil dikirim ke email!']);
    }

    // Langkah 2: Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $check = EmailOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('type', 'forgot_password')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (! $check) {
            return response()->json(['message' => 'Kode OTP salah atau sudah kadaluarsa!'], 422);
        }

        session(['reset_email' => $request->email]);

        // Jika benar, hapus OTP agar tidak bisa dipakai lagi
        $check->delete();

        return response()->json(['message' => 'OTP Valid! Silahkan ganti password Anda.']);
    }

    public function showResetForm(Request $request)
    {
        if (! session()->has('reset_email')) {
            return redirect()->route('password.request')->with('error', 'Silahkan verifikasi email terlebih dahulu.');
        }

        $email = session('reset_email');

        return view('auth.reset-password', compact('email'));
    }

    public function updatePassword(Request $request)
    {
        if (! session()->has('reset_email')) {
            return response()->json([
                'message' => 'Akses ditolak. Silahkan verifikasi OTP kembali.',
            ], 403); // 403 Forbidden
        }

        $sessionEmail = session('reset_email');

        // 2. VALIDASI: Pastikan inputan sesuai aturan
        $validator = Validator::make($request->all(), [
            // Email harus sama dengan yang ada di session tiket
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) use ($sessionEmail) {
                    if ($value !== $sessionEmail) {
                        $fail('Email tidak cocok dengan sesi verifikasi.');
                    }
                },
            ],
            'password' => 'required|min:8|confirmed',
        ], [
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Jika input tidak valid (misal password kurang panjang)
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 422); // 422 Unprocessable Entity
        }

        try {
            // 3. PROSES: Update password user
            $user = User::where('email', $sessionEmail)->first();

            if (! $user) {
                return response()->json(['message' => 'User tidak ditemukan.'], 404);
            }

            // Enkripsi password baru
            $user->password = Hash::make($request->password);
            $user->save();

            // 4. CLEANUP: Hapus session "tiket" agar tidak bisa dipakai lagi
            session()->forget('reset_email');

            return response()->json([
                'message' => 'Password berhasil diperbarui!',
            ], 200);

        } catch (\Exception $e) {
            // Log error jika ada masalah database
            Log::error('Update Password Error: '.$e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan sistem saat menyimpan password.',
            ], 500);
        }
    }
}
