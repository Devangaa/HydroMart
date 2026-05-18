<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Transaksi extends Model
{
    public function detailLayanans()
    {
        return $this->hasMany(DetailTransaksiLayanan::class, 'transaksi_id');
    }

    public function detailProduks()
    {
        return $this->hasMany(DetailTransaksiProduk::class, 'transaksi_id');
    }

    public function rewardRedemption()
    {
        return $this->belongsTo(PenukaranReward::class, 'id_penukaran_reward');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    protected $fillable = [
        'user_id',
        'id_penukaran_reward',
        'order_id',
        'kecamatan_id',
        'alamat_pengiriman',
        'nama_penerima',
        'no_hp',
        'tanggal_transaksi',
        'metode_pembayaran',
        'ekspedisi',
        'status',
        'poin',
        'kode_pembayaran',
        'batas_pembayaran',
        'nomor_resi',
        'ongkir',
    ];

    public function getTotalHargaAttribute()
    {
        $total = ($this->detailProduks->sum('total_harga') ?? 0) +
                 ($this->detailLayanans->sum('total_harga') ?? 0) +
                 ($this->ongkir ?? 0);

        if ($this->rewardRedemption) {
            $total -= $this->rewardRedemption->reward->diskon;
        }

        return max(0, $total);
    }

    public function markAsPaid()
    {
        return $this->update(['status' => 'Diproses']);
    }

    public function markAsSelesai()
    {
        if ($this->status !== 'Selesai') {
            return DB::transaction(function () {
                $this->update(['status' => 'Selesai']);

                // Berikan poin ke pelanggan
                $this->user->increment('poin_reward', $this->poin);

                // Catat riwayat poin
                RiwayatPoin::create([
                    'id_akun' => $this->user_id,
                    'jumlah_poin' => $this->poin,
                    'keterangan' => 'Poin dari transaksi '.$this->order_id,
                ]);

                // Update total_terjual untuk setiap produk
                foreach ($this->detailProduks as $detail) {
                    $detail->produk?->increment('total_terjual', $detail->jumlah);
                }

                return true;
            });
        }

        return false;
    }

    public function markAsCancelled()
    {
        if ($this->status !== 'Dibatalkan') {
            return DB::transaction(function () {
                $this->update(['status' => 'Dibatalkan']);

                foreach ($this->detailProduks as $detail) {
                    $detail->produk?->increment('jumlah_stok', $detail->jumlah);
                }

                return true;
            });
        }

        return false;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->order_id = 'HM-'.date('Ymd').'-'.strtoupper(Str::random(8));
        });
    }
}
