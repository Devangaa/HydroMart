<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model referensi provinsi untuk data wilayah Indonesia.
 */
class Province extends Model
{
    /**
     * Relasi provinsi ke daftar kota.
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Kunci utama provinsi berasal dari referensi eksternal.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = ['name'];
}
