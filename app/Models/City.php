<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model referensi kota untuk data wilayah dan ongkir.
 */
class City extends Model
{
    /**
     * Relasi kota ke provinsi induknya.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Relasi kota ke daftar kecamatan.
     */
    public function kecamatans(): HasMany
    {
        return $this->hasMany(Kecamatan::class);
    }

    /**
     * Kunci utama kota berasal dari referensi eksternal (bukan auto increment).
     *
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = ['province_id', 'name', 'ongkir'];
}
