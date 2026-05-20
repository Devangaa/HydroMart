<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Modul: Katalog Produk (Publik)
 * Fitur: Menampilkan daftar produk dan detail produk untuk pelanggan.
 */
class ProductController extends Controller
{
    /**
     * Bagian: Listing produk.
     * Alur: filter pencarian/kategori -> pagination -> kirim ke view.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $selectedCategory = $request->input('category');

        $query = Product::query()
            ->where('is_delete', 0)
            ->orderByRaw('jumlah_stok = 0 ASC')
            ->orderBy('total_terjual', 'desc')
            ->orderBy('id', 'desc');

        if ($search) {
            $query->where('nama_produk', 'like', "%{$search}%");
        }

        if ($selectedCategory) {
            $query->where('kategori', $selectedCategory);
        }

        $products = $query->paginate(20);

        $categories = Product::distinct()->pluck('kategori')->filter();

        return view('produk.index', compact('products', 'search', 'selectedCategory', 'categories'));
    }

    /**
     * Bagian: Detail produk.
     * Alur: ambil produk + ulasan aktif -> ambil produk terkait.
     */
    public function show($slug)
    {
        // Cari product yang slug-nya cocok
        $product = Product::with(['ulasans' => function ($query) {
            $query->active()->latest();
        }, 'ulasans.user'])->where('slug', $slug)->where('is_delete', 0)->firstOrFail();

        abort_if(! $product, 404);

        $relatedProducts = Product::where('kategori', $product->kategori)
            ->where('id', '!=', $product->id)
            ->where('is_delete', 0)
            ->limit(4)
            ->get();

        return view('produk.show', compact('product', 'relatedProducts'));
    }
}
