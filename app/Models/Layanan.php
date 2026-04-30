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
        'is_delete'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_delete' => 'boolean',
        'foto_layanan' => 'array',
    ];
}
