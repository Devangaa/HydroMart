<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPoin extends Model
{
    protected $table = 'riwayat_poins';

    protected $fillable = [
        'id_akun',
        'jumlah_poin',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_akun');
    }
}
