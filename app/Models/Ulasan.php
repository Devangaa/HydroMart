<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ulasan pelanggan untuk produk atau layanan.
 */
class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';

    protected $fillable = [
        'id_detailtransaksi',
        'id_akun',
        'id_produk',
        'id_layanan',
        'tanggal_ulasan',
        'komentar',
        'rating',
        'balasan',
        'tanggal_balasan',
        'isdelete',
    ];

    protected $casts = [
        'tanggal_ulasan' => 'datetime',
        'tanggal_balasan' => 'datetime',
        'isdelete' => 'boolean',
        'rating' => 'integer',
    ];

    /**
     * Relasi ulasan ke akun pemberi ulasan.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_akun');
    }

    /**
     * Relasi ulasan ke produk yang dinilai.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk');
    }

    /**
     * Relasi ulasan ke layanan yang dinilai.
     */
    public function service()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan');
    }

    /**
     * Cakupan query untuk mengambil ulasan yang belum dihapus.
     */
    public function scopeActive($query)
    {
        return $query->where('isdelete', false);
    }
}
