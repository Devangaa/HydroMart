<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksiLayanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'layanan_id',
        'total_harga',
        'catatan',
    ];

    // Relasi ke Transaksi (Header)
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // Relasi ke Layanan (Master Data)
    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
