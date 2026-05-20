<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

/**
 * Modul: Katalog Layanan (Publik)
 * Fitur: Menampilkan daftar layanan dan detail layanan untuk pelanggan.
 */
class LayananController extends Controller
{
    /**
     * Bagian: Listing layanan.
     * Alur: filter pencarian -> pagination -> kirim ke view.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Layanan::query()->where('is_delete', 0)->latest();

        if ($search) {
            $query->where('nama_layanan', 'like', "%{$search}%");
        }

        $layanan = $query->paginate(20);

        return view('layanan.index', compact('layanan', 'search'));
    }

    /**
     * Bagian: Detail layanan beserta ulasan aktif.
     */
    public function show($slug)
    {
        // Cari product yang slug-nya cocok
        $layanan = Layanan::with(['ulasans' => function ($query) {
            $query->active()->latest();
        }, 'ulasans.user'])->where('slug', $slug)->where('is_delete', 0)->firstOrFail();

        abort_if(! $layanan, 404);

        return view('layanan.show', compact('layanan'));
    }
}
