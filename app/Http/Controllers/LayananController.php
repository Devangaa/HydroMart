<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Layanan::query()->where('is_delete', 0)->latest();

        if ($search) {
            $query->where('nama_layanan', 'like', "%{$search}%");
        }

        $layanan = $query->paginate(20);

        return view('layanan.index', compact('layanan', 'search',));
    }

    public function show($slug)
    {
        // Cari product yang slug-nya cocok
        $layanan = Layanan::where('slug', $slug)->where('is_delete', 0)->firstOrFail();
        
        abort_if(!$layanan, 404);

        return view('layanan.show', compact('layanan'));
    }
}