<?php

use App\Models\PenukaranReward;
use App\Models\Transaksi;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Command bawaan untuk menampilkan kutipan inspiratif.
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Menandai transaksi menunggu pembayaran sebagai dibatalkan saat melewati batas bayar.
Schedule::call(function () {
    Transaksi::where('status', 'Menunggu Pembayaran')
        ->where('batas_pembayaran', '<', now())
        ->get()
        ->each(fn ($transaksi) => $transaksi->markAsCancelled());
})->everyMinute();

// Mengubah status reward menjadi kedaluwarsa saat melewati batas berlaku.
Schedule::call(function () {
    PenukaranReward::where('status_reward', 'Tersedia')
        ->where('batas_berlaku', '<', now())
        ->update(['status_reward' => 'Kedaluwarsa']);
})->everyMinute();
