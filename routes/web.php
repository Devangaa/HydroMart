<?php

use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RewardController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LayananController as PublicLayananController;
use App\Http\Controllers\Pelanggan\KeranjangController;
use App\Http\Controllers\Pelanggan\RewardController as PelangganRewardController;
use App\Http\Controllers\Pelanggan\TransaksiController;
use App\Http\Controllers\Pelanggan\UlasanController;
use App\Http\Controllers\ProductController as PublicProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WilayahController;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Halaman landing (publik).
Route::get('/', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    $topProducts = Product::where('is_delete', 0)
        ->orderByRaw('jumlah_stok = 0 ASC')
        ->orderBy('total_terjual', 'desc')
        ->orderBy('id', 'desc')
        ->limit(8)
        ->get();

    return view('landing', compact('topProducts'));
})->name('landing');

// Halaman katalog publik.
Route::get('/produk', [PublicProductController::class, 'index'])->name('produk.index');
Route::get('/produk/{slug}', [PublicProductController::class, 'show'])->name('produk.show');
Route::get('/layanan', [PublicLayananController::class, 'index'])->name('layanan.index');
Route::get('/layanan/{slug}', [PublicLayananController::class, 'show'])->name('layanan.show');

// Route khusus pengguna yang belum login.
Route::middleware(['guest'])->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp']);
    Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp']);

    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'updatePassword'])->name('password.update');
});

// Route untuk pengguna terautentikasi.
Route::middleware(['auth'])->group(function () {

    // Route admin.
    Route::middleware('role:admin')->name('admin.')->group(function () {
        // ===== Dashboard Admin =====
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // ===== Kelola Produk =====
        Route::resource('kelola-produk', AdminProductController::class)->names([
            'index' => 'produk.index',
            'create' => 'produk.create',
            'store' => 'produk.store',
            'show' => 'produk.show',
            'edit' => 'produk.edit',
            'update' => 'produk.update',
            'destroy' => 'produk.destroy',
        ]);

        // ===== Kelola Layanan =====
        Route::resource('kelola-layanan', LayananController::class)->names([
            'index' => 'layanan.index',
            'create' => 'layanan.create',
            'store' => 'layanan.store',
            'show' => 'layanan.show',
            'edit' => 'layanan.edit',
            'update' => 'layanan.update',
            'destroy' => 'layanan.destroy',
        ]);

        // ===== Kelola Transaksi =====
        Route::get('/kelola-transaksi', [AdminTransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/kelola-transaksi/{order_id}', [AdminTransaksiController::class, 'show'])->name('transaksi.show');
        Route::post('/kelola-transaksi/{order_id}/status', [AdminTransaksiController::class, 'updateStatus'])->name('transaksi.status');
        Route::post('/kelola-transaksi/{order_id}/resi', [AdminTransaksiController::class, 'updateResi'])->name('transaksi.resi');
        Route::post('/kelola-transaksi/ulasan/{id}/reply', [AdminTransaksiController::class, 'replyUlasan'])->name('transaksi.reply-ulasan');

        // ===== Kelola Reward =====
        Route::get('/kelola-reward/customers', [RewardController::class, 'customers'])->name('reward.customers');
        Route::get('/kelola-reward/customers/{id}', [RewardController::class, 'customerShow'])->name('reward.customer-show');
        Route::resource('kelola-reward', RewardController::class)->names([
            'index' => 'reward.index',
            'create' => 'reward.create',
            'store' => 'reward.store',
            'show' => 'reward.show',
            'edit' => 'reward.edit',
            'update' => 'reward.update',
            'destroy' => 'reward.destroy',
        ]);
    });

    // Route pelanggan.
    Route::middleware(['role:pelanggan'])->group(function () {
        // ===== Keranjang =====
        Route::get('/keranjang', [KeranjangController::class, 'index'])->name('cart.index');
        Route::post('/keranjang/tambah', [KeranjangController::class, 'store'])->name('cart.store');
        Route::patch('/keranjang/update/{cart}', [KeranjangController::class, 'update'])->name('cart.update');
        Route::delete('/keranjang/hapus/{cart}', [KeranjangController::class, 'destroy'])->name('cart.destroy');

        // ===== Halaman Checkout Produk =====
        Route::get('/checkout', [TransaksiController::class, 'checkout'])->name('checkout.produk.index');
        Route::post('/checkout/store', [TransaksiController::class, 'store'])->name('checkout.produk.store');

        // ===== Halaman Checkout Layanan =====
        Route::get('/checkout-layanan', [TransaksiController::class, 'checkoutLayanan'])->name('checkout.layanan.index');
        Route::post('/checkout-layanan/store', [TransaksiController::class, 'storeLayanan'])->name('checkout.layanan.store');
        Route::get('/cek-ongkir', [TransaksiController::class, 'cekOngkir'])->name('cek-ongkir');

        // ===== Halaman Transaksi & Pembayaran =====
        Route::get('/pesanan-saya', [TransaksiController::class, 'history'])->name('transaksi.history');
        Route::get('/transaksi/{order_id}', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::get('/pembayaran/{order_id}', [TransaksiController::class, 'pembayaran'])->name('transaksi.pembayaran');
        Route::get('/transaksi/{order_id}/status-check', [TransaksiController::class, 'checkStatus'])->name('transaksi.status-check');
        Route::post('/transaksi/{order_id}/cancel', [TransaksiController::class, 'cancel'])->name('transaksi.cancel');
        Route::post('/transaksi/{order_id}/selesai', [TransaksiController::class, 'selesai'])->name('transaksi.selesai');
        Route::post('/transaksi/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');

        // ===== Halaman Reward Pelanggan =====
        Route::get('/reward', [PelangganRewardController::class, 'index'])->name('reward.index');
        Route::get('/reward/saya', [PelangganRewardController::class, 'myRewards'])->name('reward.my-rewards');
        Route::get('/reward/{id}', [PelangganRewardController::class, 'show'])->name('reward.show');
        Route::post('/reward/{id}/claim', [PelangganRewardController::class, 'claim'])->name('reward.claim');
    });

    // ===== Profil Pengguna =====
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Endpoint wilayah untuk dropdown alamat.
Route::get('/provinces', [WilayahController::class, 'getProvinces']);
Route::get('/provinces-transaction', [WilayahController::class, 'getProvincesForTransaction']);
Route::get('/cities/{provinceId}', [WilayahController::class, 'getCitiesByProvince']);
Route::get('/kecamatan/{cityId}', [WilayahController::class, 'getKecamatanByCity']);
Route::get('/kecamatan-transaction/{cityId}', [WilayahController::class, 'getKecamatanByTransactionCity']);
