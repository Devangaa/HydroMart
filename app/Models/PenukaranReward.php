<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model riwayat klaim dan penukaran reward pelanggan.
 */
class PenukaranReward extends Model
{
    protected $table = 'penukaran_reward';

    protected $fillable = [
        'id_akun',
        'id_reward',
        'status_reward',
        'tanggal_klaim',
        'tanggal_penukaran',
        'batas_berlaku',
    ];

    protected $casts = [
        'tanggal_klaim' => 'datetime',
        'tanggal_penukaran' => 'datetime',
        'batas_berlaku' => 'datetime',
    ];

    /**
     * Relasi penukaran ke akun pelanggan.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_akun');
    }

    /**
     * Relasi penukaran ke data reward.
     */
    public function reward()
    {
        return $this->belongsTo(Reward::class, 'id_reward');
    }

    /**
     * Relasi penukaran ke transaksi yang memakai reward.
     */
    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'id_penukaran_reward');
    }
}
