<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksiProduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'jumlah',
        'total_harga',
        'catatan',
    ];

    // Relasi ke Transaksi (Header)
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // Relasi ke Produk (Master Data)
    public function produk()
    {
        return $this->belongsTo(Product::class);
    }
}
