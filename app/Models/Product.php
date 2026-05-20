<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model master produk yang dijual HydroMart.
 */
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
        'total_terjual',
        'is_delete',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_delete' => 'boolean',
        'jumlah_stok' => 'integer',
        'berat' => 'integer',
        'foto_produk' => 'array',
    ];

    // Cakupan query untuk mempermudah pemanggilan produk yang belum dihapus.
    // Contoh penggunaan: Product::active()->get();
    public function scopeActive($query)
    {
        return $query->where('is_delete', false);
    }

    public function getSlugAttribute(): string
    {
        return str_replace(' ', '-', strtolower($this->nama_produk));
    }

    /**
     * Relasi produk ke ulasan pelanggan.
     */
    public function ulasans()
    {
        return $this->hasMany(Ulasan::class, 'id_produk');
    }

    /**
     * Atribut aksesori rata-rata rating ulasan aktif.
     */
    public function getAverageRatingAttribute()
    {
        return round($this->ulasans()->active()->avg('rating'), 1) ?: 0;
    }

    /**
     * Atribut aksesori total ulasan aktif.
     */
    public function getTotalUlasanAttribute()
    {
        return $this->ulasans()->active()->count();
    }
}
