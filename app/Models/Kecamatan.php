<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model referensi kecamatan untuk alamat akun dan transaksi.
 */
class Kecamatan extends Model
{
    /**
     * Relasi kecamatan ke kota induknya.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Relasi kecamatan ke daftar akun pengguna.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'kecamatan_id');
    }

    /**
     * Relasi kecamatan ke daftar transaksi pengiriman.
     */
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'kecamatan_id');
    }

    /**
     * Kunci utama kecamatan berasal dari referensi eksternal.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = ['city_id', 'name'];
}
