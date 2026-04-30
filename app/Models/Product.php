<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'nama_produk',
        'slug',
        'deskripsi',
        'harga',
        'jumlah_stok',
        'foto_produk',
        'kategori',
        'berat',
        'unit',
        'is_delete',
    ];


    protected $casts = [
        'harga' => 'decimal:2',
        'is_delete' => 'boolean',
        'jumlah_stok' => 'integer',
        'berat' => 'integer',
        'foto_produk' => 'array',
    ];

    // Scope untuk mempermudah pemanggilan produk yang belum dihapus
    // Contoh penggunaan: Product::active()->get();
    public function scopeActive($query)
    {
        return $query->where('is_delete', false);
    }

    public function getSlugAttribute(): string
    {
        return str_replace(' ', '-', strtolower($this->nama_produk));
    }

}