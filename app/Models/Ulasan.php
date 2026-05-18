<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'id_akun');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk');
    }

    public function service()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan');
    }

    public function scopeActive($query)
    {
        return $query->where('isdelete', false);
    }
}
