<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function kecamatans()
    {
        return $this->hasMany(Kecamatan::class);
    }

    protected $fillable = ['province_id', 'name'];
}
