<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\DetailTransaksiProduk;
use App\Models\Kecamatan;
use App\Models\Product;
use App\Models\Province;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Menampilkan Halaman Checkout
     */
    public function checkout(Request $request)
    {
        // Ambil data produk (Beli Sekarang)
        $product = Product::findOrFail($request->product_id);
        $qty = $request->qty ?? 1;

        // Logika Cek Sayuran
        $isSayuran = ($product->kategori == 'Sayuran');

        // Ambil semua provinsi untuk dropdown (non-sayuran)
        $provinces = Province::all();

        $sayuranProvince = null;
        $sayuranCity = null;

        if ($isSayuran) {
            // Sayuran: hanya bisa dikirim ke Kab. Jember (Sumbersari, Patrang, Kaliwates)
            $kecamatans = Kecamatan::whereIn('name', ['Sumbersari', 'Patrang', 'Kaliwates'])->orderBy('name')->get();
            $sayuranProvince = Province::where('name', 'Jawa Timur')->first();
            $sayuranCity = City::where('name', 'Kabupaten Jember')->first();
        } else {
            $kecamatans = collect();
        }

        return view('pelanggan.transaksi.checkout.produk.index', compact(
            'product', 'qty', 'isSayuran', 'kecamatans', 'provinces', 'sayuranProvince', 'sayuranCity'
        ));
    }

    /**
     * Eksekusi Simpan Transaksi (Store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah' => 'required|integer|min:1',
            'nama_penerima' => 'required|string',
            'no_hp' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'metode_pembayaran' => 'required|in:midtrans,cod',
            'catatan' => 'nullable|string',
        ]);

        $product = Product::find($request->product_id);

        // 1. Validasi Stok
        if ($product->jumlah_stok < $request->jumlah) {
            return back()->with('error', 'Maaf, stok tidak mencukupi.');
        }

        // 2. Validasi Geofencing Sayuran (Security Check)
        if ($product->kategori == 'Sayuran') {
            $kecamatan = Kecamatan::find($request->kecamatan_id);
            $allowed = ['Sumbersari', 'Patrang', 'Kaliwates'];
            if (! in_array($kecamatan->name, $allowed)) {
                return back()->with('error', 'Produk sayur hanya bisa dikirim ke Sumbersari, Patrang, dan Kaliwates.');
            }
        }

        // 3. Mulai Database Transaction
        return DB::transaction(function () use ($request, $product) {
            $subtotal = $product->harga * $request->jumlah;

            // Simpan Header Transaksi
            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'kecamatan_id' => $request->kecamatan_id,
                'tanggal_transaksi' => now(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'ekspedisi' => ($request->metode_pembayaran == 'cod') ? 'Kurir Lokal (COD)' : 'Ekspedisi Reguler',
                'status' => 'Menunggu Pembayaran',
                'alamat_pengiriman' => $request->alamat_lengkap,
                'nama_penerima' => $request->nama_penerima,
                'no_hp' => $request->no_hp,
                'poin' => floor($subtotal / 10000),
            ]);

            // Simpan Detail Transaksi
            DetailTransaksiProduk::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $product->id,
                'jumlah' => $request->jumlah,
                'total_harga' => $subtotal,
                'catatan' => $request->catatan ?? null,
                'nomor_resi' => null,
            ]);

            // Potong Stok
            $product->decrement('jumlah_stok', $request->jumlah);

            // 4. Integrasi Pembayaran
            if ($request->metode_pembayaran == 'cod') {
                return redirect()->route('transaksi.history')->with('success', 'Pesanan COD berhasil dibuat!');
            } else {
                // Di sini nanti tempat kode Midtrans (Snap Token)
                return $this->generateMidtransToken($transaksi);
            }
        });
    }

    private function generateMidtransToken($transaksi)
    {
        // Placeholder untuk integrasi Midtrans di tahap berikutnya
        return redirect()->route('transaksi.history')->with('success', 'Lanjutkan pembayaran melalui Midtrans.');
    }

    public function cancel($id)
    {
        // Cari transaksi milik user yang login
        $transaksi = Transaksi::where('user_id', Auth::id())->findOrFail($id);

        // Cek apakah status masih memungkinkan untuk dibatalkan
        if ($transaksi->status !== 'Menunggu Pembayaran') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        try {
            DB::transaction(function () use ($transaksi) {
                // 1. Ubah status transaksi
                $transaksi->update([
                    'status' => 'Dibatalkan',
                ]);

                // 2. Kembalikan stok produk
                // Kita ambil detail produk yang terkait dengan transaksi ini
                $details = DetailTransaksiProduk::where('transaksi_id', $transaksi->id)->get();

                foreach ($details as $detail) {
                    $product = Product::find($detail->produk_id);
                    if ($product) {
                        $product->increment('jumlah_stok', $detail->jumlah);
                    }
                }
            });

            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membatalkan pesanan.');
        }
    }

    public function history(Request $request)
    {
        $tab = $request->input('tab', 'aktif');

        $query = Transaksi::where('user_id', Auth::id())
            ->with(['detailProduks.produk', 'kecamatan'])
            ->orderBy('tanggal_transaksi', 'desc');

        if ($tab == 'riwayat') {
            $query->whereIn('status', ['Selesai', 'Dibatalkan']);
        } else {
            $query->whereNotIn('status', ['Selesai', 'Dibatalkan']);
        }

        $transaksis = $query->paginate(10)->withQueryString();

        return view('pelanggan.transaksi.history', compact('transaksis', 'tab'));
    }
}
