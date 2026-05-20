<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model penyimpanan OTP email untuk verifikasi/reset password.
 */
class EmailOtp extends Model
{
    use HasFactory;

    // Nama tabel di basis data.
    protected $table = 'email_otps';

    // Kolom yang boleh diisi mass assignment.
    protected $fillable = [
        'email',
        'otp',
        'type',
        'expires_at',
    ];

    // Pastikan expires_at dibaca sebagai objek tanggal (Carbon).
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
