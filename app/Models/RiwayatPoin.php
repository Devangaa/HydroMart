<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model riwayat perubahan poin reward pelanggan.
 */
class RiwayatPoin extends Model
{
    protected $table = 'riwayat_poins';

    protected $fillable = [
        'id_akun',
        'jumlah_poin',
        'keterangan',
    ];

    /**
     * Relasi riwayat poin ke akun pelanggan.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_akun');
    }
}
