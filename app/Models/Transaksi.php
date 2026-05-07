<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    public function detailLayanans()
    {
        return $this->hasMany(DetailTransaksiLayanan::class, 'transaksi_id');
    }

    public function detailProduks()
    {
        return $this->hasMany(DetailTransaksiProduk::class, 'transaksi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    protected $fillable = [
        'user_id',
        'kecamatan_id',
        'alamat_pengiriman',
        'nama_penerima',
        'no_hp',
        'tanggal_transaksi',
        'metode_pembayaran',
        'ekspedisi',
        'status',
        'poin',
        'nomor_resi',
        'ongkir',
    ];
}
