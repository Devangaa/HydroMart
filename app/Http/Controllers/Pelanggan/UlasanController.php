<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\DetailTransaksiLayanan;
use App\Models\DetailTransaksiProduk;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Modul: Pelanggan - Ulasan
 * Fitur: Pengiriman ulasan untuk produk/layanan dari transaksi selesai.
 */
class UlasanController extends Controller
{
    /**
     * Bagian: Simpan ulasan pelanggan.
     * Alur: validasi input -> verifikasi transaksi selesai -> cegah duplikasi ulasan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_detailtransaksi' => 'required',
            'type' => 'required|in:produk,layanan',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string',
        ]);

        $id_produk = null;
        $id_layanan = null;

        if ($request->type === 'produk') {
            $detail = DetailTransaksiProduk::with('transaksi')->findOrFail($request->id_detailtransaksi);
            if ($detail->transaksi->status !== 'Selesai') {
                return back()->with('error', 'Anda hanya dapat memberikan ulasan pada pesanan yang sudah selesai.');
            }
            $id_produk = $detail->produk_id;
        } else {
            $detail = DetailTransaksiLayanan::with('transaksi')->findOrFail($request->id_detailtransaksi);
            if ($detail->transaksi->status !== 'Selesai') {
                return back()->with('error', 'Anda hanya dapat memberikan ulasan pada pesanan yang sudah selesai.');
            }
            $id_layanan = $detail->layanan_id;
        }

        // Cek apakah sudah ada ulasan
        $existing = Ulasan::where('id_detailtransaksi', $request->id_detailtransaksi)
            ->where('id_akun', Auth::id())
            ->where(function ($query) use ($request) {
                if ($request->type === 'produk') {
                    $query->whereNotNull('id_produk');
                } else {
                    $query->whereNotNull('id_layanan');
                }
            })->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk item ini.');
        }

        Ulasan::create([
            'id_detailtransaksi' => $request->id_detailtransaksi,
            'id_akun' => Auth::id(),
            'id_produk' => $id_produk,
            'id_layanan' => $id_layanan,
            'tanggal_ulasan' => now(),
            'komentar' => $request->komentar,
            'rating' => $request->rating,
            'isdelete' => false,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
