<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'email_otps';

    // Kolom yang boleh diisi
    protected $fillable = [
        'email',
        'otp',
        'type',
        'expires_at',
    ];

    // Pastikan expires_at dibaca sebagai objek Carbon/Tanggal
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
