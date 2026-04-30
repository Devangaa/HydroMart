<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            'foto_produk.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP|max:2048',
            'kategori' => 'required|string',
            'berat' => 'required|integer',
            'unit' => 'required|string',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga.required'       => 'Harga produk harus diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'jumlah_stok.required' => 'Stok tidak boleh kosong.',
            'jumlah_stok.integer'  => 'Stok harus berupa angka bulat.',
            'foto_produk.*.image'    => 'File yang diunggah harus berupa gambar.',
            'foto_produk.*.mimes'    => 'Format gambar hanya boleh jpg, jpeg, atau png.',
            'foto_produk.*.max'      => 'Ukuran gambar maksimal adalah 2MB.',
            'berat.required'       => 'Berat produk wajib diisi.',
            'berat.integer'        => 'Berat harus berupa angka (gram).',
        ]);

        $data = $request->all();
        $filenames = [];

        if ($request->hasFile('foto_produk')) {
            // Ambil maksimal 4 file pertama
            $files = array_slice($request->file('foto_produk'), 0, 4);

            foreach ($files as $file) {
                // Rename file agar unik & aman: [Timestamp]-[Random].ext
                $filename = time() . '-' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/produk'), $filename);
                $filenames[] = $filename;
            }
        }

        $data['foto_produk'] = $filenames;

        Product::create([
            'nama_produk' => $request->nama_produk,
            'slug' => Str::slug($request->nama_produk),
            'kategori'    => $request->kategori,
            'harga'       => $request->harga,
            'jumlah_stok' => $request->jumlah_stok,
            'unit'        => $request->unit,
            'berat'       => $request->berat,
            'deskripsi'   => $request->deskripsi,
            'foto_produk' => $filenames,
        ]);

        
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->has('restore') && $request->restore == '1') {
            $product->update(['is_delete' => false]);
            return redirect()->back()->with('success', 'Produk "' . $product->nama_produk . '" berhasil dipulihkan!');
        }

        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|string|max:255',
            'slug' => Str::slug($request->nama_produk),
            'deskripsi' => 'nullable',
            'harga' => 'required|integer',
            'jumlah_stok' => 'required|integer',
            'foto_produk.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP|max:2048',
            'kategori' => 'required|string',
            'berat' => 'required|integer',
            'unit' => 'required|string',
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga.required'       => 'Harga produk harus diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'jumlah_stok.required' => 'Stok tidak boleh kosong.',
            'jumlah_stok.integer'  => 'Stok harus berupa angka bulat.',
            'foto_produk.*.image'    => 'File yang diunggah harus berupa gambar.',
            'foto_produk.*.mimes'    => 'Format gambar hanya boleh jpg, jpeg, atau png.',
            'foto_produk.*.max'      => 'Ukuran gambar maksimal adalah 2MB.',
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
        $currentImages = $product->foto_produk ?? [];

        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageName) {
                $filePath = public_path('uploads/produk/' . $imageName);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
                $currentImages = array_values(array_diff($currentImages, [$imageName]));
            }
        }

        if ($request->hasFile('foto_produk')) {
            $files = $request->file('foto_produk');
            
            foreach ($files as $file) {
                if (count($currentImages) < 4) { 
                    $filename = time() . '-' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/produk'), $filename);
                    $currentImages[] = $filename;
                }
            }
        }

        $data['foto_produk'] = $currentImages;
        $product->update([
            'nama_produk' => $request->nama_produk,
            'slug' => Str::slug($request->nama_produk),
            'kategori'    => $request->kategori,
            'harga'       => $request->harga,
            'jumlah_stok' => $request->jumlah_stok,
            'unit'        => $request->unit,
            'berat'       => $request->berat,
            'deskripsi'   => $request->deskripsi,
            'foto_produk' => $currentImages,
        ]);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_delete' => true]);
        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }
}