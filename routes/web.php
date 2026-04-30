<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\ProductController as PublicProductController;
use App\Http\Controllers\Admin\LayananController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('landing');
})->name('landing');

Route::get('/produk', [PublicProductController::class, 'index'])->name('produk.index');
Route::get('/produk/{id}', [PublicProductController::class, 'show'])->name('produk.show');

Route::middleware(['guest'])->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {
    
    Route::middleware('role:admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard'); 
        })->name('dashboard');

        Route::resource('kelola-produk', AdminProductController::class)->names([
            'index'   => 'produk.index',
            'create'  => 'produk.create',
            'store'   => 'produk.store',
            'show'    => 'produk.show',
            'edit'    => 'produk.edit',
            'update'  => 'produk.update',
            'destroy' => 'produk.destroy',
        ]);

        Route::resource('kelola-layanan', LayananController::class)->names([
            'index'   => 'layanan.index',
            'create'  => 'layanan.create',
            'store'   => 'layanan.store',
            'show'    => 'layanan.show',
            'edit'    => 'layanan.edit',
            'update'  => 'layanan.update',
            'destroy' => 'layanan.destroy',
        ]);
    });

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});