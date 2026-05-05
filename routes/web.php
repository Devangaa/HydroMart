<?php

use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\WilayahController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LayananController as PublicLayananController;
use App\Http\Controllers\ProductController as PublicProductController;
use App\Http\Controllers\ProfileController;
use App\Models\City;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return view('landing');
})->name('landing');

Route::get('/produk', [PublicProductController::class, 'index'])->name('produk.index');
Route::get('/produk/{slug}', [PublicProductController::class, 'show'])->name('produk.show');

Route::get('/layanan', [PublicLayananController::class, 'index'])->name('layanan.index');
Route::get('/layanan/{slug}', [PublicLayananController::class, 'show'])->name('layanan.show');

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

Route::middleware(['auth'])->group(function () {

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
    });

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// API Routes untuk Cascading Dropdown Wilayah
Route::get('/api/provinces', [WilayahController::class, 'getProvinces']);
Route::get('/api/cities/{provinceId}', [WilayahController::class, 'getCitiesByProvince']);
Route::get('/api/kecamatan/{cityId}', [WilayahController::class, 'getKecamatanByCity']);

Route::get('/cities/{province_id}', function ($province_id) {
    return City::where('province_id', $province_id)->get();
});

Route::get('/kecamatans/{city_id}', function ($city_id) {
    return Kecamatan::where('city_id', $city_id)->get();
});
