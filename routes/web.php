<?php

use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RewardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LayananController as PublicLayananController;
use App\Http\Controllers\Pelanggan\KeranjangController;
use App\Http\Controllers\Pelanggan\TransaksiController;
use App\Http\Controllers\Pelanggan\UlasanController;
use App\Http\Controllers\ProductController as PublicProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WilayahController;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// LANDING PAGE
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

// PRODUK & LAYANAN
Route::get('/produk', [PublicProductController::class, 'index'])->name('produk.index');
Route::get('/produk/{slug}', [PublicProductController::class, 'show'])->name('produk.show');

Route::get('/layanan', [PublicLayananController::class, 'index'])->name('layanan.index');
Route::get('/layanan/{slug}', [PublicLayananController::class, 'show'])->name('layanan.show');

// GUEST ONLY
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

// KALAU SUDAH LOGIN
Route::middleware(['auth'])->group(function () {

    // ADMIN ONLY
    Route::middleware('role:admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('kelola-produk', AdminProductController::class)->names([
            'index' => 'produk.index',
            'create' => 'produk.create',
            'store' => 'produk.store',
            'show' => 'produk.show',
            'edit' => 'produk.edit',
            'update' => 'produk.update',
            'destroy' => 'produk.destroy',
        ]);

        Route::resource('kelola-layanan', LayananController::class)->names([
            'index' => 'layanan.index',
            'create' => 'layanan.create',
            'store' => 'layanan.store',
            'show' => 'layanan.show',
            'edit' => 'layanan.edit',
            'update' => 'layanan.update',
            'destroy' => 'layanan.destroy',
        ]);

        Route::get('/kelola-transaksi', [App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/kelola-transaksi/{order_id}', [App\Http\Controllers\Admin\TransaksiController::class, 'show'])->name('transaksi.show');
        Route::post('/kelola-transaksi/{order_id}/status', [App\Http\Controllers\Admin\TransaksiController::class, 'updateStatus'])->name('transaksi.status');
        Route::post('/kelola-transaksi/{order_id}/resi', [App\Http\Controllers\Admin\TransaksiController::class, 'updateResi'])->name('transaksi.resi');
        Route::post('/kelola-transaksi/ulasan/{id}/reply', [App\Http\Controllers\Admin\TransaksiController::class, 'replyUlasan'])->name('transaksi.reply-ulasan');

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

    // PELANGGAN ONLY
    Route::middleware(['role:pelanggan'])->group(function () {
        // CART
        Route::get('/keranjang', [KeranjangController::class, 'index'])->name('cart.index');
        Route::post('/keranjang/tambah', [KeranjangController::class, 'store'])->name('cart.store');
        Route::patch('/keranjang/update/{cart}', [KeranjangController::class, 'update'])->name('cart.update');
        Route::delete('/keranjang/hapus/{cart}', [KeranjangController::class, 'destroy'])->name('cart.destroy');

        Route::get('/checkout', [TransaksiController::class, 'checkout'])->name('checkout.produk.index');
        Route::post('/checkout/store', [TransaksiController::class, 'store'])->name('checkout.produk.store');

        Route::get('/checkout-layanan', [TransaksiController::class, 'checkoutLayanan'])->name('checkout.layanan.index');
        Route::post('/checkout-layanan/store', [TransaksiController::class, 'storeLayanan'])->name('checkout.layanan.store');

        Route::get('/cek-ongkir', [TransaksiController::class, 'cekOngkir'])->name('cek-ongkir');
        Route::get('/pesanan-saya', [TransaksiController::class, 'history'])->name('transaksi.history');
        Route::get('/transaksi/{order_id}', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::get('/pembayaran/{order_id}', [TransaksiController::class, 'pembayaran'])->name('transaksi.pembayaran');
        Route::get('/transaksi/{order_id}/status-check', [TransaksiController::class, 'checkStatus'])->name('transaksi.status-check');
        Route::post('/transaksi/{order_id}/cancel', [TransaksiController::class, 'cancel'])->name('transaksi.cancel');
        Route::post('/transaksi/{order_id}/selesai', [TransaksiController::class, 'selesai'])->name('transaksi.selesai');
        Route::post('/transaksi/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');

        // REWARDS
        Route::get('/reward', [App\Http\Controllers\Pelanggan\RewardController::class, 'index'])->name('reward.index');
        Route::get('/reward/saya', [App\Http\Controllers\Pelanggan\RewardController::class, 'myRewards'])->name('reward.my-rewards');
        Route::get('/reward/{id}', [App\Http\Controllers\Pelanggan\RewardController::class, 'show'])->name('reward.show');
        Route::post('/reward/{id}/claim', [App\Http\Controllers\Pelanggan\RewardController::class, 'claim'])->name('reward.claim');
    });

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// DROPDOWN WILAYAH
Route::get('/provinces', [WilayahController::class, 'getProvinces']);
Route::get('/provinces-transaction', [WilayahController::class, 'getProvincesForTransaction']);
Route::get('/cities/{provinceId}', [WilayahController::class, 'getCitiesByProvince']);
Route::get('/kecamatan/{cityId}', [WilayahController::class, 'getKecamatanByCity']);
Route::get('/kecamatan-transaction/{cityId}', [WilayahController::class, 'getKecamatanByTransactionCity']);
