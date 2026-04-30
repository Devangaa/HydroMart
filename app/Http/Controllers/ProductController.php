<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $selectedCategory = $request->input('category');

        $query = Product::query()->latest();

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

    public function show($slug)
    {
        // Cari product yang slug-nya cocok
        $product = Product::where('slug', $slug)->firstOrFail();
        
        abort_if(!$product, 404);
        
        $relatedProducts = Product::where('kategori', $product->kategori)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('produk.show', compact('product', 'relatedProducts'));
    }
}