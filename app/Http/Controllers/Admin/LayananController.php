<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            'foto_layanan.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP|max:2048'
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'harga.required'       => 'Harga layanan harus diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'foto_layanan.*.image'    => 'File yang diunggah harus berupa gambar.',
            'foto_layanan.*.mimes'    => 'Format gambar hanya boleh jpg, jpeg, atau png.',
            'foto_layanan.*.max'      => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        $data = $request->all();
        $filenames = [];

        if ($request->hasFile('foto_layanan')) {
            // Ambil maksimal 4 file pertama
            $files = array_slice($request->file('foto_layanan'), 0, 4);

            foreach ($files as $file) {
                // Rename file agar unik & aman: [Timestamp]-[Random].ext
                $filename = time() . '-' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/layanan'), $filename);
                $filenames[] = $filename;
            }
        }

        $data['foto_layanan'] = $filenames;

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
            'foto_layanan.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP|max:2048'
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'harga.required'       => 'Harga layanan harus diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'foto_layanan.*.image'    => 'File yang diunggah harus berupa gambar.',
            'foto_layanan.*.mimes'    => 'Format gambar hanya boleh jpg, jpeg, atau png.',
            'foto_layanan.*.max'      => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('editingLayananId', $id);
        }

        $data = $request->all();
        $currentImages = $layanan->foto_layanan ?? [];


        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageName) {
                $filePath = public_path('uploads/layanan/' . $imageName);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
                $currentImages = array_values(array_diff($currentImages, [$imageName]));
            }
        }

        if ($request->hasFile('foto_layanan')) {
            $files = $request->file('foto_layanan');
            
            foreach ($files as $file) {
                if (count($currentImages) < 4) { 
                    $filename = time() . '-' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/layanan'), $filename);
                    $currentImages[] = $filename;
                }
            }
        }

        $data['foto_layanan'] = $currentImages;
        $layanan->update([
            'nama_layanan' => $request->nama_layanan,
            'harga'       => $request->harga,
            'deskripsi'   => $request->deskripsi,
            'foto_layanan' => $currentImages,
        ]);
        
        return redirect()->back()->with('success', 'Layanan diperbarui!');
    }

    public function destroy($id)
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->update(['is_delete' => 1]); 
        return redirect()->back()->with('success', 'Layanan berhasil dihapus!');
    }
}
