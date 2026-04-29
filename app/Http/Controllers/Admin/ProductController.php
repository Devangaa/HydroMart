<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(request $request)
    {
        $categories = Product::distinct()->pluck('kategori');

        $allProducts = Product::all();
    
        $stats = [
            'total' => $allProducts->count(),
            'aktif' => $allProducts->where('is_delete', false)->count(),
            'menipis' => $allProducts->where('is_delete', false)->where('jumlah_stok', '<=', 10)->count(),
            'dihapus' => $allProducts->where('is_delete', true)->count(),
        ];

        $query = Product::query();

        if ($request->query('status') == 'terhapus') {
            $query->where('is_delete', true);
        } else {
            $query->where('is_delete', false);
        }

        if ($request->filled('search')) {
        $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category') && $request->category != 'Semua Kategori') {
            $query->where('kategori', $request->category);
        }

        $products = $query->latest()->paginate(10);
        return view('admin.produk.index', compact('products', 'stats', 'categories'));
    }

    public function create()
    {
        return view('admin.produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable',
            'harga' => 'required|numeric',
            'jumlah_stok' => 'required|integer',
            'foto_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kategori' => 'required|string',
            'berat' => 'required|integer',
            'unit' => 'required|string',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga.required'       => 'Harga produk harus diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'jumlah_stok.required' => 'Stok tidak boleh kosong.',
            'jumlah_stok.integer'  => 'Stok harus berupa angka bulat.',
            'foto_produk.image'    => 'File yang diunggah harus berupa gambar.',
            'foto_produk.mimes'    => 'Format gambar hanya boleh jpg, jpeg, atau png.',
            'foto_produk.max'      => 'Ukuran gambar maksimal adalah 2MB.',
            'berat.required'       => 'Berat produk wajib diisi.',
            'berat.integer'        => 'Berat harus berupa angka (gram).',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto_produk')) {
            $file = $request->file('foto_produk');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/produk'), $filename);
            $data['foto_produk'] = $filename;
        }

        Product::create($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.produk.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->has('restore') && $request->restore == '1') {
            $product->update(['is_delete' => false]);

            return redirect()->route('admin.produk.index')
                ->with('success', 'Produk "' . $product->nama_produk . '" berhasil dipulihkan!');
        }

        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable',
            'harga' => 'required|integer',
            'jumlah_stok' => 'required|integer',
            'foto_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kategori' => 'required|string',
            'berat' => 'required|integer',
            'unit' => 'required|string',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga.required'       => 'Harga produk harus diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'jumlah_stok.required' => 'Stok tidak boleh kosong.',
            'jumlah_stok.integer'  => 'Stok harus berupa angka bulat.',
            'foto_produk.image'    => 'File yang diunggah harus berupa gambar.',
            'foto_produk.mimes'    => 'Format gambar hanya boleh jpg, jpeg, atau png.',
            'foto_produk.max'      => 'Ukuran gambar maksimal adalah 2MB.',
            'berat.required'       => 'Berat produk wajib diisi.',
            'berat.integer'        => 'Berat harus berupa angka (gram).',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.produk.index')
                ->withErrors($validator)
                ->withInput()
                ->with('editingProductId', $id);
        }

        $data = $request->all();

        if ($request->hasFile('foto_produk')) {
            if ($product->foto_produk && File::exists(public_path('uploads/produk/' . $product->foto_produk))) {
                File::delete(public_path('uploads/produk/' . $product->foto_produk));
            }

            $file = $request->file('foto_produk');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/produk'), $filename);
            $data['foto_produk'] = $filename;
        }

        $product->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        $product->update(['is_delete' => true]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}