<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';

    protected $fillable = [
        'nama_layanan',
        'slug',
        'deskripsi',
        'harga',
        'foto_layanan',
        'is_delete',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_delete' => 'boolean',
        'foto_layanan' => 'array',
    ];

    public function ulasans()
    {
        return $this->hasMany(Ulasan::class, 'id_layanan');
    }

    public function getAverageRatingAttribute()
    {
        return round($this->ulasans()->active()->avg('rating'), 1) ?: 0;
    }

    public function getTotalUlasanAttribute()
    {
        return $this->ulasans()->active()->count();
    }
}
