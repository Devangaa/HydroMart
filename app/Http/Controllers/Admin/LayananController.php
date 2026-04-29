<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Layanan::query();

        if ($request->search) {
            $query->where('nama_layanan', 'like', '%' . $request->search . '%');
        }

        if ($status == 'terhapus') {
            $query->where('is_delete', 1);
        } else {
            $query->where('is_delete', 0);
        }

        $layanan = $query->latest()->paginate(10);
        
        $stats = [
            'total' => Layanan::count(),
            'aktif' => Layanan::where('is_delete', 0)->count(),
            'dihapus' => Layanan::where('is_delete', 1)->count(),
        ];

        return view('admin.layanan.index', compact('layanan', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable',
            'foto_layanan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga.required'       => 'Harga produk harus diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'foto_produk.image'    => 'File yang diunggah harus berupa gambar.',
            'foto_produk.mimes'    => 'Format gambar hanya boleh jpg, jpeg, atau png.',
            'foto_produk.max'      => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto_layanan')) {
            $file = $request->file('foto_layanan');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/layanan'), $nama_file);
            $data['foto_layanan'] = $nama_file;
        }

        Layanan::create($data);
        return redirect()->back()->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $layanan = Layanan::findOrFail($id);
        
        if ($request->has('restore')) {
            $layanan->update(['is_delete' => 0]);
            return redirect()->back()->with('success', 'Layanan dipulihkan!');
        }

        $validator = Validator::make($request->all(), [
            'nama_layanan' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable',
            'foto_layanan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'harga.required'       => 'Harga produk harus diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'foto_produk.image'    => 'File yang diunggah harus berupa gambar.',
            'foto_produk.mimes'    => 'Format gambar hanya boleh jpg, jpeg, atau png.',
            'foto_produk.max'      => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('editingLayananId', $id);
        }

        $data = $request->all();

        if ($request->hasFile('foto_layanan')) {
            if ($layanan->foto_layanan && File::exists(public_path('uploads/layanan/' . $layanan->foto_layanan))) {
                File::delete(public_path('uploads/layanan/' . $layanan->foto_layanan));
            }

            $file = $request->file('foto_layanan');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/layanan'), $nama_file);
            $data['foto_layanan'] = $nama_file;
        }

        $layanan->update($data);
        return redirect()->back()->with('success', 'Layanan diperbarui!');
    }

    public function destroy($id)
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->update(['is_delete' => 1]); 
        return redirect()->back()->with('success', 'Layanan berhasil dihapus!');
    }
}
