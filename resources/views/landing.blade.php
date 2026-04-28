@extends('layouts.app')

@section('title', 'Kelola Bisnis Hidroponik Anda')

@section('content')
<div class="w-full">
    <section class="max-w-6xl mx-auto px-4 py-16 md:py-24 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 text-left">
            <span class="inline-block px-4 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-full mb-6">
                Sistem Informasi Terintegrasi
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                Kelola Bisnis <span class="text-green-600">Hidroponik</span> Anda dengan Lebih Efisien
            </h1>
            <p class="text-gray-500 text-lg mb-10 leading-relaxed max-w-lg">
                HydroMart adalah platform web app yang mengintegrasikan penjualan online, manajemen produk, dan laporan bisnis dalam satu sistem yang mudah digunakan.
            </p>
            <div class="flex gap-4">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-200">
                    Mulai Sekarang
                </a>
                <a href="#tentang" class="px-8 py-4 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
        <div class="flex-1 flex justify-center">
            <div class="relative w-80 h-80 bg-green-50 rounded-[3rem] flex items-center justify-center border-2 border-green-100/50">
                <div class="w-56 h-56 bg-green-200 rounded-full flex items-center justify-center opacity-60">
                     <span class="text-green-600 font-bold">+500 Produk Terjual</span>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gray-50/50 py-12">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center">
                <h3 class="text-3xl font-extrabold text-gray-900">50+</h3>
                <p class="text-gray-400 text-xs mt-2 italic font-medium">Produk Selada Hidroponik</p>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center">
                <h3 class="text-3xl font-extrabold text-gray-900">500+</h3>
                <p class="text-gray-400 text-xs mt-2 italic font-medium">Transaksi Berhasil</p>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center">
                <h3 class="text-3xl font-extrabold text-gray-900">50+</h3>
                <p class="text-gray-400 text-xs mt-2 italic font-medium">Pelanggan Aktif</p>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center">
                <h3 class="text-3xl font-extrabold text-gray-900">99%</h3>
                <p class="text-gray-400 text-xs mt-2 italic font-medium">Kepuasan Pelanggan</p>
            </div>
        </div>
    </section>

    <section id="tentang" class="max-w-6xl mx-auto px-4 py-24 flex flex-col-reverse md:flex-row items-center gap-20">
        <div class="flex-1">
             <div class="w-full aspect-square bg-green-50 rounded-[3rem] border-2 border-green-100/50 p-12">
                 <div class="w-full h-full bg-green-200/50 rounded-2xl"></div>
             </div>
        </div>
        <div class="flex-1 text-left">
            <span class="inline-block px-4 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-full mb-6">
                Tentang HydroMart
            </span>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Solusi Digital untuk Bisnis Hidroponik Modern</h2>
            <p class="text-gray-500 mb-8 leading-relaxed">
                HydroMart merupakan sistem informasi berbasis web app yang dirancang untuk mendukung aktivitas penjualan serta pengelolaan operasional pada bisnis hidroponik. Platform ini mengintegrasikan proses penjualan, pengelolaan data, serta penyajian laporan secara sistematis.
            </p>
            <ul class="space-y-4">
                <li class="flex items-center gap-3 text-sm font-medium text-gray-700 italic">
                    <span class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-white text-[10px]">✓</span> Integrasi E-Commerce dan Manajemen Stok
                </li>
                <li class="flex items-center gap-3 text-sm font-medium text-gray-700 italic">
                    <span class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-white text-[10px]">✓</span> Pemrosesan Transaksi Otomatis
                </li>
                <li class="flex items-center gap-3 text-sm font-medium text-gray-700 italic">
                    <span class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-white text-[10px]">✓</span> Laporan Penjualan Real-Time
                </li>
            </ul>
        </div>
    </section>

    <section id="produk" class="max-w-6xl mx-auto px-4 py-16">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="inline-block px-4 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-full mb-4">
                    Produk Kami
                </span>
                <h2 class="text-3xl font-bold text-gray-900">Produk Hidroponik Berkualitas</h2>
            </div>
            <a href="#" class="px-6 py-2 border border-green-600 text-green-600 font-bold rounded-lg text-sm hover:bg-green-50 transition">Lihat Semua Produk</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @php
                $products = [
                    ['name' => 'Selada', 'price' => '10.000', 'category' => 'Sayuran'],
                    ['name' => 'Daun Mint Segar', 'price' => '9.000', 'category' => 'Herbal'],
                    ['name' => 'Netpot Jaring 7cm', 'price' => '300', 'category' => 'Alat'],
                    ['name' => 'Pompa Yamano 103', 'price' => '74.000', 'category' => 'Alat'],
                ];
            @endphp

            @foreach($products as $p)
            <div class="group bg-white border border-gray-100 rounded-3xl p-4 hover:shadow-xl hover:shadow-green-100/50 transition duration-300">
                <div class="aspect-square bg-green-50 rounded-2xl mb-4 flex items-center justify-center overflow-hidden">
                    <div class="w-20 h-20 bg-green-200 rounded-full opacity-50 group-hover:scale-110 transition duration-300"></div>
                </div>
                <p class="text-[10px] font-bold text-green-600 uppercase">{{ $p['category'] }}</p>
                <h4 class="font-bold text-gray-900 mt-1">{{ $p['name'] }}</h4>
                <p class="text-green-600 font-bold text-sm mt-3">Rp {{ $p['price'] }} <span class="text-gray-300 text-[10px] font-normal">/ pcs</span></p>
            </div>
            @endforeach
        </div>
    </section>
</div>
@endsection