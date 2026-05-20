<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model master reward yang dapat ditukar dengan poin pelanggan.
 */
class Reward extends Model
{
    protected $table = 'rewards';

    protected $fillable = [
        'nama_reward',
        'poin_diperlukan',
        'deskripsi',
        'diskon',
        'minimal_pembelian',
        'durasi_reward',
        'is_delete',
    ];

    protected $casts = [
        'poin_diperlukan' => 'integer',
        'diskon' => 'decimal:2',
        'minimal_pembelian' => 'decimal:2',
        'durasi_reward' => 'integer',
        'is_delete' => 'boolean',
    ];

    /**
     * Cakupan query untuk mengambil reward yang masih aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_delete', false);
    }

    /**
     * Relasi reward ke riwayat penukaran.
     */
    public function penukarans()
    {
        return $this->hasMany(PenukaranReward::class, 'id_reward');
    }
}
