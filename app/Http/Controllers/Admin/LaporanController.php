<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailTransaksiLayanan;
use App\Models\DetailTransaksiProduk;
use App\Models\Keuangan;
use App\Models\Transaksi;
use App\Models\Ulasan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Modul: Admin - Laporan Tahunan & Bulanan
 * Fitur: Menyajikan statistik tahunan (grafik keuangan, grafik ulasan) dan data bulanan berjalan (top seller, volume penjualan).
 */
class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan utama.
     */
    public function index(Request $request): View
    {
        $tahun = (int) $request->query('tahun', now()->year);
        $currentMonth = now()->month;

        // 1. Ambil daftar tahun yang tersedia dari data transaksi, keuangan, dan ulasan
        $tahunKeuangan = Keuangan::where('is_delete', false)->selectRaw('YEAR(tanggal) as tahun')->pluck('tahun');
        $tahunTransaksi = Transaksi::selectRaw('YEAR(tanggal_transaksi) as tahun')->pluck('tahun');
        $tahunUlasan = Ulasan::active()->selectRaw('YEAR(tanggal_ulasan) as tahun')->pluck('tahun');

        $years = collect([now()->year])
            ->merge($tahunKeuangan)
            ->merge($tahunTransaksi)
            ->merge($tahunUlasan)
            ->filter()
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        // 2. Data Grafik Finansial Tahunan (Pemasukan, Pengeluaran, Kas/Profit-Loss)
        // Ambil transaksi selesai/sukses
        $transaksis = Transaksi::with(['detailProduks', 'detailLayanans', 'rewardRedemption.reward'])
            ->whereYear('tanggal_transaksi', $tahun)
            ->whereIn('status', ['settlement', 'success', 'Selesai', 'Diproses', 'Dikirim'])
            ->get();

        $transaksiBulanan = array_fill(1, 12, 0.0);
        foreach ($transaksis as $t) {
            $month = (int) Carbon::parse($t->tanggal_transaksi)->month;
            $transaksiBulanan[$month] += (float) $t->total_harga;
        }

        // Ambil keuangan tipe pendapatan/pemasukan
        $keuanganPemasukan = Keuangan::where('is_delete', false)
            ->whereYear('tanggal', $tahun)
            ->whereIn('tipe_laporan', ['pendapatan', 'pemasukan'])
            ->get();

        $keuanganPemasukanBulanan = array_fill(1, 12, 0.0);
        foreach ($keuanganPemasukan as $k) {
            $month = (int) Carbon::parse($k->tanggal)->month;
            $keuanganPemasukanBulanan[$month] += (float) $k->nominal;
        }

        // Ambil keuangan tipe pengeluaran
        $keuanganPengeluaran = Keuangan::where('is_delete', false)
            ->whereYear('tanggal', $tahun)
            ->where('tipe_laporan', 'pengeluaran')
            ->get();

        $pengeluaranBulanan = array_fill(1, 12, 0.0);
        foreach ($keuanganPengeluaran as $k) {
            $month = (int) Carbon::parse($k->tanggal)->month;
            $pengeluaranBulanan[$month] += (float) $k->nominal;
        }

        // Susun array final untuk Chart.js (12 bulan)
        $pemasukanData = [];
        $pengeluaranData = [];
        $kasData = [];

        for ($m = 1; $m <= 12; $m++) {
            $pemasukan = $transaksiBulanan[$m] + $keuanganPemasukanBulanan[$m];
            $pengeluaran = $pengeluaranBulanan[$m];
            $pemasukanData[] = $pemasukan;
            $pengeluaranData[] = $pengeluaran;
            $kasData[] = $pemasukan - $pengeluaran;
        }

        // 3. Laporan Produk & Layanan Terjual (Bulan Berjalan Saat Ini pada Tahun Terpilih)
        // Top Best Seller of the Month
        $topProduct = DetailTransaksiProduk::whereHas('transaksi', function ($query) use ($tahun, $currentMonth) {
            $query->whereYear('tanggal_transaksi', $tahun)
                ->whereMonth('tanggal_transaksi', $currentMonth)
                ->whereIn('status', ['settlement', 'success', 'Selesai', 'Diproses', 'Dikirim']);
        })
            ->select('produk_id', DB::raw('SUM(jumlah) as total_qty'))
            ->groupBy('produk_id')
            ->orderByDesc('total_qty')
            ->with('produk')
            ->first();

        $topLayanan = DetailTransaksiLayanan::whereHas('transaksi', function ($query) use ($tahun, $currentMonth) {
            $query->whereYear('tanggal_transaksi', $tahun)
                ->whereMonth('tanggal_transaksi', $currentMonth)
                ->whereIn('status', ['settlement', 'success', 'Selesai', 'Diproses', 'Dikirim']);
        })
            ->select('layanan_id', DB::raw('COUNT(*) as total_count'))
            ->groupBy('layanan_id')
            ->orderByDesc('total_count')
            ->with('layanan')
            ->first();

        // Tabel Volume Penjualan Bulanan
        $produkTerjual = DetailTransaksiProduk::whereHas('transaksi', function ($query) use ($tahun, $currentMonth) {
            $query->whereYear('tanggal_transaksi', $tahun)
                ->whereMonth('tanggal_transaksi', $currentMonth)
                ->whereIn('status', ['settlement', 'success', 'Selesai', 'Diproses', 'Dikirim']);
        })
            ->select('produk_id', DB::raw('SUM(jumlah) as total_qty'), DB::raw('SUM(total_harga) as total_omset'))
            ->groupBy('produk_id')
            ->with('produk')
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->produk?->nama_produk ?? 'Produk Tidak Dikenal',
                    'tipe' => 'Produk',
                    'qty' => $item->total_qty,
                    'omset' => $item->total_omset,
                ];
            });

        $layananTerjual = DetailTransaksiLayanan::whereHas('transaksi', function ($query) use ($tahun, $currentMonth) {
            $query->whereYear('tanggal_transaksi', $tahun)
                ->whereMonth('tanggal_transaksi', $currentMonth)
                ->whereIn('status', ['settlement', 'success', 'Selesai', 'Diproses', 'Dikirim']);
        })
            ->select('layanan_id', DB::raw('COUNT(*) as total_qty'), DB::raw('SUM(total_harga) as total_omset'))
            ->groupBy('layanan_id')
            ->with('layanan')
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->layanan?->nama_layanan ?? 'Layanan Tidak Dikenal',
                    'tipe' => 'Layanan',
                    'qty' => $item->total_qty,
                    'omset' => $item->total_omset,
                ];
            });

        $volumePenjualan = $produkTerjual->concat($layananTerjual)->sortByDesc('qty')->values();

        // 4. Grafik Kepuasan Pelanggan: Tren Rating Toko
        $ulasanBulanan = Ulasan::active()
            ->whereYear('tanggal_ulasan', $tahun)
            ->selectRaw('MONTH(tanggal_ulasan) as bulan, AVG(rating) as avg_rating')
            ->groupBy('bulan')
            ->pluck('avg_rating', 'bulan')
            ->toArray();

        $ratingData = [];
        for ($m = 1; $m <= 12; $m++) {
            $ratingData[] = isset($ulasanBulanan[$m]) ? round((float) $ulasanBulanan[$m], 2) : 0.0;
        }

        $bulanNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return view('admin.laporan.index', compact(
            'tahun',
            'years',
            'pemasukanData',
            'pengeluaranData',
            'kasData',
            'topProduct',
            'topLayanan',
            'volumePenjualan',
            'ratingData',
            'bulanNames',
            'currentMonth'
        ));
    }
}
