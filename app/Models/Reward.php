<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function scopeActive($query)
    {
        return $query->where('is_delete', false);
    }

    public function penukarans()
    {
        return $this->hasMany(PenukaranReward::class, 'id_reward');
    }
}
