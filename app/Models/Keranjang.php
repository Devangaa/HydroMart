<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model item keranjang belanja pelanggan.
 */
class Keranjang extends Model
{
    protected $table = 'keranjang';

    protected $fillable = [
        'user_id',
        'product_id',
        'jumlah',
    ];

    /**
     * Relasi item keranjang ke akun pelanggan.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi item keranjang ke produk.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
