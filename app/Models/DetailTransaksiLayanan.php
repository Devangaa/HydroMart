<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model detail item layanan pada sebuah transaksi.
 */
class DetailTransaksiLayanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'layanan_id',
        'total_harga',
        'catatan',
    ];

    // Relasi ke transaksi utama (header pesanan).
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // Relasi ke data layanan yang dibeli.
    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function ulasan()
    {
        // Satu detail transaksi layanan dapat memiliki satu ulasan layanan.
        return $this->hasOne(Ulasan::class, 'id_detailtransaksi')->whereNotNull('id_layanan');
    }
}
