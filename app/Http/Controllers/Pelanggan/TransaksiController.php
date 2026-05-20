<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\DetailTransaksiLayanan;
use App\Models\DetailTransaksiProduk;
use App\Models\Kecamatan;
use App\Models\Keranjang;
use App\Models\Layanan;
use App\Models\PenukaranReward;
use App\Models\Product;
use App\Models\Province;
use App\Models\Transaksi;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\CoreApi;

/**
 * Modul: Pelanggan - Transaksi
 * Fitur: Checkout produk/layanan, pembayaran Midtrans, tracking, dan histori transaksi.
 */
class TransaksiController extends Controller
{
    /**
     * Bagian: Inisialisasi konfigurasi Midtrans SDK.
     */
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Bagian: Endpoint cek ongkir berdasarkan kecamatan dan total berat.
     */
    public function cekOngkir(Request $request)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'total_weight' => 'required|integer|min:0',
        ]);

        $ongkir = $this->calculateOngkir($request->kecamatan_id, $request->total_weight);

        return response()->json([
            'success' => true,
            'ongkir' => $ongkir,
            'formatted_ongkir' => 'Rp '.number_format($ongkir, 0, ',', '.'),
        ]);
    }

    /**
     * Bagian: Helper perhitungan ongkir.
     * Catatan: beberapa kecamatan mendapat ongkir gratis.
     */
    private function calculateOngkir($kecamatanId, $totalWeightGrams)
    {
        $kecamatan = Kecamatan::with('city')->findOrFail($kecamatanId);
        $freeShippingKecamatans = ['Patrang', 'Sumbersari', 'Kaliwates'];

        if (in_array($kecamatan->name, $freeShippingKecamatans)) {
            return 0;
        }

        // Ambil ongkir per kg dari tabel cities (yang diatur via seeder/manual)
        $ongkirPerKg = $kecamatan->city->ongkir ?? 0;

        // Konversi gram ke kg dan bulatkan ke atas (misal 1.2kg jadi 2kg)
        $weightInKg = ceil($totalWeightGrams / 1000);

        // Minimal 1kg jika ada beratnya
        if ($weightInKg < 1 && $totalWeightGrams > 0) {
            $weightInKg = 1;
        }

        return $ongkirPerKg * $weightInKg;
    }

    /**
     * Bagian: Checkout produk (buy now atau dari keranjang).
     */
    public function checkout(Request $request)
    {
        $mode = $request->input('mode', 'buy_now');

        if ($mode === 'cart') {
            $cartIds = $request->input('cart_ids', []);
            if (empty($cartIds)) {
                return redirect()->route('cart.index')->with('error', 'Pilih minimal satu produk untuk di checkout.');
            }

            $cartItems = Keranjang::whereIn('id', $cartIds)
                ->where('user_id', Auth::id())
                ->with('product')
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan atau keranjang kosong.');
            }

            foreach ($cartItems as $item) {
                if ($item->jumlah > $item->product->jumlah_stok) {
                    return redirect()->route('cart.index')->with('error', "Stok {$item->product->nama_produk} tidak mencukupi.");
                }
            }

            $items = $cartItems->map(fn ($cart) => (object) [
                'product' => $cart->product,
                'qty' => $cart->jumlah,
                'subtotal' => $cart->product->harga * $cart->jumlah,
                'cart_id' => $cart->id,
            ]);
        } else {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'qty' => 'nullable|integer|min:1',
            ]);

            $product = Product::findOrFail($request->product_id);
            $qty = min($request->qty ?? 1, $product->jumlah_stok);

            $items = collect([
                (object) [
                    'product' => $product,
                    'qty' => $qty,
                    'subtotal' => $product->harga * $qty,
                ],
            ]);
        }

        $grandTotal = $items->sum('subtotal');
        $totalWeight = $items->sum(fn ($item) => $item->product->berat * $item->qty);
        $isSayuran = $items->contains(fn ($item) => $item->product->kategori === 'Sayuran');

        $allowedProvinces = ['Banten', 'Daerah Khusus Ibukota Jakarta', 'Jawa Barat', 'Jawa Tengah', 'Daerah Istimewa Yogyakarta', 'Jawa Timur'];
        $provinces = Province::whereIn('name', $allowedProvinces)->orderBy('name')->get();

        $sayuranProvince = null;
        $sayuranCity = null;
        if ($isSayuran) {
            $kecamatans = Kecamatan::whereIn('name', ['Sumbersari', 'Patrang', 'Kaliwates'])->orderBy('name')->get();
            $sayuranProvince = Province::where('name', 'Jawa Timur')->first();
            $sayuranCity = City::where('name', 'Kabupaten Jember')->first();
        } else {
            $kecamatans = collect();
        }

        $availableRewards = PenukaranReward::with('reward')
            ->where('id_akun', Auth::id())
            ->where('status_reward', 'Tersedia')
            ->where('batas_berlaku', '>', now())
            ->get();

        return view('pelanggan.transaksi.checkout.produk.index', compact(
            'items', 'grandTotal', 'mode', 'isSayuran', 'kecamatans', 'provinces', 'sayuranProvince', 'sayuranCity', 'totalWeight', 'availableRewards'
        ));
    }

    /**
     * Bagian: Checkout layanan.
     */
    public function checkoutLayanan(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
        ]);

        $layanan = Layanan::findOrFail($request->layanan_id);
        $grandTotal = $layanan->harga;
        $totalWeight = 1000;

        $isLayananGeofencing = true;
        $allowedKecamatans = ['Sumbersari', 'Patrang', 'Kaliwates'];

        $provinces = Province::all();
        $kecamatans = Kecamatan::whereIn('name', $allowedKecamatans)->orderBy('name')->get();
        $layananProvince = Province::where('name', 'Jawa Timur')->first();
        $layananCity = City::where('name', 'Kabupaten Jember')->first();

        return view('pelanggan.transaksi.checkout.layanan.index', compact(
            'layanan', 'grandTotal', 'isLayananGeofencing', 'kecamatans', 'provinces', 'layananProvince', 'layananCity', 'totalWeight'
        ));
    }

    /**
     * Bagian: Simpan transaksi produk.
     * Alur: validasi -> hitung total/ongkir/diskon -> buat transaksi -> proses pembayaran.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.catatan' => 'nullable|string',
            'nama_penerima' => 'required|string',
            'no_hp' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'metode_pembayaran' => 'required|in:qris,bca,mandiri,cod',
            'id_penukaran_reward' => 'nullable|exists:penukaran_reward,id',
        ]);

        $itemsData = $request->input('items');
        $products = Product::whereIn('id', collect($itemsData)->pluck('product_id'))->get()->keyBy('id');
        $totalWeight = 0;

        foreach ($itemsData as $item) {
            $product = $products->get($item['product_id']);
            if ($product->jumlah_stok < $item['jumlah']) {
                return back()->with('error', "Stok {$product->nama_produk} tidak mencukupi.");
            }
            $totalWeight += ($product->berat * $item['jumlah']);
            if ($product->kategori === 'Sayuran') {
                $kecamatan = Kecamatan::find($request->kecamatan_id);
                if (! in_array($kecamatan->name, ['Sumbersari', 'Patrang', 'Kaliwates'])) {
                    return back()->with('error', 'Produk sayur hanya bisa dikirim ke Sumbersari, Patrang, dan Kaliwates.');
                }
            }
        }

        $ongkir = $this->calculateOngkir($request->kecamatan_id, $totalWeight);

        return DB::transaction(function () use ($request, $itemsData, $products, $ongkir) {
            $grandTotal = 0;
            foreach ($itemsData as $item) {
                $grandTotal += $products->get($item['product_id'])->harga * $item['jumlah'];
            }

            $discount = 0;
            if ($request->id_penukaran_reward) {
                $redemption = PenukaranReward::with('reward')
                    ->where('id', $request->id_penukaran_reward)
                    ->where('id_akun', Auth::id())
                    ->where('status_reward', 'Tersedia')
                    ->first();

                if ($redemption && $grandTotal >= $redemption->reward->minimal_pembelian) {
                    $discount = $redemption->reward->diskon;
                    $redemption->update([
                        'status_reward' => 'Digunakan',
                        'tanggal_penukaran' => now(),
                    ]);
                }
            }

            $finalTotal = max(0, $grandTotal - $discount);
            $poin = max(0, floor($finalTotal / 10000));

            // If total is 0, always set status to Menunggu Konfirmasi (nothing to pay)
            if ($finalTotal + $ongkir <= 0) {
                $status = 'Menunggu Konfirmasi';
            } else {
                $status = ($request->metode_pembayaran == 'cod') ? 'Menunggu Konfirmasi' : 'Menunggu Pembayaran';
            }

            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'id_penukaran_reward' => $request->id_penukaran_reward,
                'kecamatan_id' => $request->kecamatan_id,
                'tanggal_transaksi' => now('Asia/Jakarta'),
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => $status,
                'alamat_pengiriman' => $request->alamat_lengkap,
                'nama_penerima' => $request->nama_penerima,
                'no_hp' => $request->no_hp,
                'poin' => $poin,
                'batas_pembayaran' => now('Asia/Jakarta')->addHours(24),
                'ongkir' => $ongkir,
            ]);

            foreach ($itemsData as $item) {
                $product = $products->get($item['product_id']);
                DetailTransaksiProduk::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $product->id,
                    'jumlah' => $item['jumlah'],
                    'total_harga' => $product->harga * $item['jumlah'],
                    'catatan' => $item['catatan'] ?? null,
                ]);
                $product->decrement('jumlah_stok', $item['jumlah']);
                if (isset($item['cart_id'])) {
                    Keranjang::where('id', $item['cart_id'])->delete();
                }
            }

            // Skip Midtrans for COD or zero-total transactions
            if ($request->metode_pembayaran == 'cod' || $status === 'Menunggu Konfirmasi') {
                return redirect()->route('transaksi.show', $transaksi->order_id)->with('success', 'Pesanan berhasil dibuat!');
            } else {
                return $this->generateMidtransPayment($transaksi);
            }
        });
    }

    /**
     * Bagian: Simpan transaksi layanan.
     */
    public function storeLayanan(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'catatan' => 'nullable|string',
            'nama_penerima' => 'required|string',
            'no_hp' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'metode_pembayaran' => 'required|in:qris,bca,mandiri,cod',
        ]);

        $layanan = Layanan::findOrFail($request->layanan_id);
        $ongkir = $this->calculateOngkir($request->kecamatan_id, 1000);

        return DB::transaction(function () use ($request, $layanan, $ongkir) {
            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'kecamatan_id' => $request->kecamatan_id,
                'tanggal_transaksi' => now('Asia/Jakarta'),
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => ($request->metode_pembayaran == 'cod') ? 'Menunggu Konfirmasi' : 'Menunggu Pembayaran',
                'alamat_pengiriman' => $request->alamat_lengkap,
                'nama_penerima' => $request->nama_penerima,
                'no_hp' => $request->no_hp,
                'poin' => floor($layanan->harga / 50000),
                'batas_pembayaran' => now('Asia/Jakarta')->addHours(24),
                'ongkir' => $ongkir,
            ]);

            DetailTransaksiLayanan::create([
                'transaksi_id' => $transaksi->id,
                'layanan_id' => $layanan->id,
                'total_harga' => $layanan->harga,
                'catatan' => $request->catatan,
            ]);

            if ($request->metode_pembayaran == 'cod') {
                return redirect()->route('transaksi.show', $transaksi->order_id)->with('success', 'Pesanan COD berhasil dibuat!');
            } else {
                return $this->generateMidtransPayment($transaksi);
            }
        });
    }

    /**
     * Bagian: Helper pembuatan pembayaran Midtrans.
     */
    private function generateMidtransPayment(Transaksi $transaksi)
    {
        try {
            $totalAmount = (int) ($transaksi->detailProduks->sum('total_harga') + $transaksi->detailLayanans->sum('total_harga') + $transaksi->ongkir);

            $params = [
                'payment_type' => ($transaksi->metode_pembayaran == 'mandiri') ? 'echannel' : ($transaksi->metode_pembayaran == 'qris' ? 'qris' : 'bank_transfer'),
                'transaction_details' => [
                    'order_id' => $transaksi->order_id,
                    'gross_amount' => $totalAmount,
                ],
                'customer_details' => [
                    'first_name' => $transaksi->nama_penerima,
                    'email' => $transaksi->user->email,
                    'phone' => $transaksi->no_hp,
                ],
                'expiry' => [
                    'start_time' => Carbon::parse($transaksi->tanggal_transaksi)->format('Y-m-d H:i:s O'),
                    'unit' => 'hours',
                    'duration' => 24,
                ],
            ];

            if ($transaksi->metode_pembayaran == 'bca') {
                $params['bank_transfer'] = ['bank' => 'bca'];
            } elseif ($transaksi->metode_pembayaran == 'mandiri') {
                $params['echannel'] = [
                    'bill_info1' => 'Pembayaran HydroMart',
                    'bill_info2' => $transaksi->order_id,
                ];
            }

            $response = CoreApi::charge($params);
            $paymentCode = null;
            if ($transaksi->metode_pembayaran == 'bca') {
                $paymentCode = $response->va_numbers[0]->va_number;
            } elseif ($transaksi->metode_pembayaran == 'mandiri') {
                $paymentCode = json_encode(['bill_key' => $response->bill_key, 'biller_code' => $response->biller_code]);
            } elseif ($transaksi->metode_pembayaran == 'qris') {
                foreach ($response->actions as $action) {
                    if ($action->name == 'generate-qr-code') {
                        $paymentCode = $action->url;
                        break;
                    }
                }
            }

            $transaksi->update(['kode_pembayaran' => $paymentCode]);

            return redirect()->route('transaksi.pembayaran', $transaksi->order_id)->with('success', 'Pesanan berhasil dibuat!');
        } catch (Exception $e) {
            return redirect()->route('transaksi.show', $transaksi->order_id)->with('error', 'Gagal menghubungkan ke Midtrans: '.$e->getMessage());
        }
    }

    /**
     * Bagian: Detail transaksi pelanggan.
     */
    public function show($order_id)
    {
        $this->syncExpiredTransactions();

        $transaksi = Transaksi::where('user_id', Auth::id())
            ->where('order_id', $order_id)
            ->with(['detailProduks.produk', 'detailProduks.ulasan', 'detailLayanans.layanan', 'detailLayanans.ulasan', 'kecamatan.city.province'])
            ->firstOrFail();

        $trackingData = null;
        if (in_array($transaksi->status, ['Dikirim', 'Selesai']) && $transaksi->nomor_resi && $transaksi->ekspedisi) {
            if ($transaksi->ekspedisi !== 'Kurir Lokal') {
                $cacheKey = 'tracking_'.$transaksi->nomor_resi;
                $trackingData = Cache::remember($cacheKey, 3600, function () use ($transaksi) {
                    if (strtoupper($transaksi->nomor_resi) === 'TESTING123') {
                        return $this->getMockTrackingData($transaksi->nomor_resi);
                    }
                    try {
                        $apiKey = config('services.binderbyte.key');
                        $response = Http::get('https://api.binderbyte.com/v1/track', [
                            'api_key' => $apiKey,
                            'courier' => strtolower($transaksi->ekspedisi),
                            'awb' => $transaksi->nomor_resi,
                        ]);
                        if ($response->successful()) {
                            $data = $response->json();
                            if (isset($data['status']) && $data['status'] === 200) {
                                return $data;
                            }
                        }
                    } catch (Exception $e) {
                        Log::error('BinderByte API Show Error (Pelanggan): '.$e->getMessage());
                    }

                    return null;
                });
            }
        }

        return view('pelanggan.transaksi.show', compact('transaksi', 'trackingData'));
    }

    /**
     * Bagian: Helper data tracking mock untuk pengujian.
     */
    private function getMockTrackingData($resi)
    {
        return [
            'status' => 200,
            'message' => 'Successfully tracked package (MOCK)',
            'data' => [
                'summary' => [
                    'awb' => $resi,
                    'courier' => 'JNE',
                    'service' => 'REG',
                    'status' => 'DELIVERED',
                    'date' => now()->format('Y-m-d H:i:s'),
                    'description' => 'Paket telah diterima',
                ],
                'history' => [
                    ['date' => now()->subHours(2)->format('Y-m-d H:i:s'), 'description' => 'PAKET DITERIMA', 'location' => 'JEMBER'],
                    ['date' => now()->subHours(5)->format('Y-m-d H:i:s'), 'description' => 'PAKET KELUAR GUDANG', 'location' => 'JEMBER'],
                ],
            ],
        ];
    }

    /**
     * Bagian: Halaman informasi pembayaran transaksi.
     */
    public function pembayaran($order_id)
    {
        $this->syncExpiredTransactions();

        $transaksi = Transaksi::where('user_id', Auth::id())
            ->where('order_id', $order_id)
            ->with(['detailProduks.produk', 'detailLayanans.layanan'])
            ->firstOrFail();

        // Jika status sudah berubah dari Menunggu Pembayaran, arahkan ke show
        if (! in_array($transaksi->status, ['Menunggu Pembayaran', 'Menunggu Konfirmasi'])) {
            return redirect()->route('transaksi.show', $transaksi->order_id);
        }

        return view('pelanggan.transaksi.pembayaran', compact('transaksi'));
    }

    /**
     * Bagian: Endpoint polling status transaksi (AJAX).
     */
    public function checkStatus($order_id)
    {
        $transaksi = Transaksi::where('user_id', Auth::id())
            ->where('order_id', $order_id)
            ->firstOrFail();

        return response()->json([
            'status' => $transaksi->status,
            'is_processed' => ! in_array($transaksi->status, ['Menunggu Pembayaran', 'Menunggu Konfirmasi']),
            'redirect_url' => route('transaksi.show', $transaksi->order_id),
        ]);
    }

    /**
     * Bagian: Pembatalan transaksi oleh pelanggan.
     */
    public function cancel($order_id)
    {
        try {
            $transaksi = Transaksi::where('order_id', $order_id)
                ->where('user_id', Auth::id())
                ->where('status', 'Menunggu Pembayaran')
                ->firstOrFail();
            $transaksi->markAsCancelled();

            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal membatalkan pesanan: '.$e->getMessage());
        }
    }

    /**
     * Bagian: Konfirmasi transaksi selesai oleh pelanggan.
     */
    public function selesai($order_id)
    {
        try {
            $transaksi = Transaksi::where('order_id', $order_id)
                ->where('user_id', Auth::id())
                ->where('status', 'Dikirim')
                ->firstOrFail();

            $transaksi->markAsSelesai();

            return back()->with('success', 'Pesanan telah selesai. Terima kasih telah berbelanja!');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menyelesaikan pesanan: '.$e->getMessage());
        }
    }

    /**
     * Bagian: Riwayat transaksi pelanggan per tab status.
     */
    public function history(Request $request)
    {
        $this->syncExpiredTransactions();

        $tab = $request->get('tab', 'menunggu-pembayaran');
        $query = Transaksi::where('user_id', Auth::id())
            ->with(['detailProduks.produk', 'detailLayanans.layanan', 'kecamatan'])
            ->orderBy('tanggal_transaksi', 'desc');

        if ($tab === 'menunggu-pembayaran') {
            $query->whereIn('status', ['Menunggu Pembayaran', 'Menunggu Konfirmasi']);
        } elseif ($tab === 'diproses') {
            $query->whereIn('status', ['Diproses', 'Dikirim']);
        } elseif ($tab === 'riwayat') {
            $query->whereIn('status', ['Selesai', 'Dibatalkan']);
        }

        $transaksis = $query->paginate(10)->withQueryString();

        return view('pelanggan.transaksi.history', compact('transaksis', 'tab'));
    }

    /**
     * Bagian: Sinkronisasi transaksi kedaluwarsa (auto-cancel).
     */
    private function syncExpiredTransactions()
    {
        Transaksi::where('user_id', Auth::id())
            ->where('status', 'Menunggu Pembayaran')
            ->where('batas_pembayaran', '<', now())
            ->get()
            ->each(fn ($transaksi) => $transaksi->markAsCancelled());
    }
}
