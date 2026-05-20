<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model detail item produk pada sebuah transaksi.
 */
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

    // Relasi ke transaksi utama (header pesanan).
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // Relasi ke data produk yang dibeli.
    public function produk()
    {
        return $this->belongsTo(Product::class);
    }

    public function ulasan()
    {
        // Satu detail transaksi dapat memiliki satu ulasan produk.
        return $this->hasOne(Ulasan::class, 'id_detailtransaksi')->whereNotNull('id_produk');
    }
}
