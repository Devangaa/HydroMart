<?php

use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('/cities/{province_id}', [WilayahController::class, 'getCitiesByProvince']);
    Route::get('/districts/{city_id}', [WilayahController::class, 'getKecamatanByCity']);
});
