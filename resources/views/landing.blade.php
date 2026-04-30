@extends('layouts.app')

@section('title', 'Kelola Bisnis Hidroponik Anda')

@section('content')
<div class="w-full">
    <section id="hero" class="max-w-6xl mx-auto px-4 py-16 md:py-24 flex flex-col md:flex-row items-center gap-12">
        <div data-aos="fade-right" class="flex-1 text-left">
            <span class="inline-block px-4 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-full mb-6">
                HydroMart
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                Platform Untuk Kebutuhan <span class="text-green-600">Hidroponik</span> Anda
            </h1>
            <p class="text-gray-500 text-lg mb-10 leading-relaxed max-w-lg">
                HydroMart menyediakan produk dan layanan hidroponik modern, mulai dari selada, nutrisi, benih, hingga peralatan hidroponik, serta layanan pembuatan dan instalasi media hidroponik yang praktis, efisien, dan mudah digunakan.
            </p>
            
            <div class="flex gap-4">
                @if (Auth::check())
                    <a href="{{ route('produk.index') }}" class="px-8 py-4 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-200">
                        Mulai Sekarang
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-200">
                        Mulai Sekarang
                    </a>
                @endif
            </div>
        </div>

        <div data-aos="fade" class="flex-1 flex justify-center md:justify-end">
            <div class="relative w-full max-w-[420px] aspect-square bg-green-50 rounded-[3rem] border-8 border-white shadow-2xl shadow-green-100/50 overflow-hidden group">
                
                <img src="{{ asset('img/hero-hydro.webp') }}" 
                    alt="Hidroponik" 
                    class="w-full h-full object-cover transition duration-700 group-hover:scale-110">

                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>

                <div class="absolute bottom-8 right-8 bg-white/95 backdrop-blur-sm px-5 py-3 rounded-2xl shadow-xl border border-white flex items-center gap-2 animate-bounce-slow">
                    <div class="flex -space-x-2">
                        <div class="w-6 h-6 rounded-full bg-green-500 border-2 border-white flex items-center justify-center text-[10px] text-white font-bold shadow-sm">✓</div>
                    </div>
                    <span class="text-green-700 font-extrabold text-sm tracking-tight">+500 Produk Terjual</span>
                </div>

                <div class="absolute -top-10 -left-10 w-32 h-32 bg-green-200/30 rounded-full blur-3xl"></div>
            </div>
        </div>

        <style>
            @keyframes bounce-slow {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-5px); }
            }
            .animate-bounce-slow {
                animation: bounce-slow 3s infinite;
            }
        </style>
    </section>

    <section data-aos="fade-up" class="bg-green-100 py-12">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center">
                <h3 class="text-3xl font-extrabold text-green-900">50+</h3>
                <p class="text-gray-400 text-xs mt-2 font-medium">Produk Selada Hidroponik</p>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center">
                <h3 class="text-3xl font-extrabold text-green-900">500+</h3>
                <p class="text-gray-400 text-xs mt-2font-medium">Transaksi Berhasil</p>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center">
                <h3 class="text-3xl font-extrabold text-green-900">50+</h3>
                <p class="text-gray-400 text-xs mt-2 font-medium">Pelanggan Aktif</p>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center">
                <h3 class="text-3xl font-extrabold text-green-900">99%</h3>
                <p class="text-gray-400 text-xs mt-2 font-medium">Kepuasan Pelanggan</p>
            </div>
        </div>
    </section>

    <section data-aos="fade-up" id="tentang" class="max-w-6xl mx-auto px-4 py-24 flex flex-col-reverse md:flex-row items-center gap-20">
        <div class="flex-1">
            <div class="w-full aspect-square bg-green-50 rounded-[3rem] border-2 border-green-100/50 p-6 md:p-12 flex items-center justify-center overflow-hidden">
                
                <img src="{{ asset('img/hydro-about.webp') }}" 
                    alt="Tentang HydroMart" 
                    class="w-full h-full object-cover rounded-2xl shadow-md transition duration-500 hover:scale-110">
                    
            </div>
        </div>

        <div class="flex-1 text-left">
            <span class="inline-block px-4 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-full mb-6">
                Tentang HydroMart
            </span>
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Solusi Digital untuk Hidroponik Modern</h2>
            <p class="text-gray-500 mb-8 leading-relaxed">
                HydroMart menyediakan kebutuhan hidroponik secara praktis dan efisien. Melalui website, Anda dapat dengan mudah memesan selada, nutrisi, benih, hingga peralatan hidroponik dalam satu platform untuk mendukung hasil panen yang optimal.
            </p>
            <ul class="space-y-4">
                <li class="flex items-center gap-3 text-sm font-medium text-gray-700 italic">
                    <span class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-white text-[10px]">✓</span> Belanja Produk dengan Sekali Klik
                </li>
                <li class="flex items-center gap-3 text-sm font-medium text-gray-700 italic">
                    <span class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-white text-[10px]">✓</span> Tampilan  yang Simpel & Ringan
                </li>
                <li class="flex items-center gap-3 text-sm font-medium text-gray-700 italic">
                    <span class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-white text-[10px]">✓</span> Antarmuka User-Friendly
                </li>
            </ul>
        </div>
    </section>

    <section data-aos="fade-up" class="bg-green-100 py-24">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-16">
                <span class="px-4 py-1.5 bg-green-200 text-green-700 text-xs font-bold rounded-full mb-4 inline-block">
                    Keuntungan
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Mengapa Memilih HydroMart?</h2>
                <p class="text-gray-500 max-w-2xl mx-auto leading-relaxed">
                    Dengan HydroMart, Anda dapat memenuhi kebutuhan hidroponik dengan lebih mudah, cepat, dan praktis melalui sistem online, kapan saja dan di mana saja.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-green-100/50 flex flex-col gap-4 group hover:shadow-xl transition duration-300">
                    <div class="w-14 h-14 bg-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-200 group-hover:scale-110 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Belanja Praktis & Cepat</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Pesan semua kebutuhan hidroponik secara online dalam satu platform tanpa repot.</p>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-green-100/50 flex flex-col gap-4 group hover:shadow-xl transition duration-300">
                    <div class="w-14 h-14 bg-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-200 group-hover:scale-110 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Stok Akurat & Update</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Informasi produk diperbarui secara otomatis, memastikan barang yang Anda beli selalu tersedia.</p>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-green-100/50 flex flex-col gap-4 group hover:shadow-xl transition duration-300">
                    <div class="w-14 h-14 bg-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-200 group-hover:scale-110 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Transaksi Transparan</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Pantau status pesanan dan riwayat pembelian Anda kapan saja dengan mudah.</p>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-green-100/50 flex flex-col gap-4 group hover:shadow-xl transition duration-300">
                    <div class="w-14 h-14 bg-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-200 group-hover:scale-110 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Akses Nyaman</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Desain antarmuka yang simpel memudahkan Anda untuk berbelanja tanpa kendala teknis.</p>
                </div>
            </div>
        </div>
    </section>

    <section data-aos="fade-up" class="py-24">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-20">
                <span class="px-4 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-full mb-4 inline-block">
                    Cara Kerja
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Mudah dan Cepat</h2>
                <p class="text-gray-500">Empat langkah sederhana untuk mulai menggunakan HydroMart</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-12 relative">

                <div class="text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-green-600 text-white rounded-full flex items-center justify-center text-xl font-black mb-6 shadow-xl shadow-green-200 relative z-10">
                        1
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Daftar Akun</h4>
                    <p class="text-gray-500 text-xs leading-relaxed">Buat akun gratis untuk mulai mengakses platform HydroMart.</p>
                </div>

                <div class="text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-green-600 text-white rounded-full flex items-center justify-center text-xl font-black mb-6 shadow-xl shadow-green-200 relative z-10">
                        2
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Jelajahi Produk</h4>
                    <p class="text-gray-500 text-xs leading-relaxed">Lihat berbagai produk dan layanan hidroponik berkualitas.</p>
                </div>

                <div class="text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-green-600 text-white rounded-full flex items-center justify-center text-xl font-black mb-6 shadow-xl shadow-green-200 relative z-10">
                        3
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Tambah Keranjang</h4>
                    <p class="text-gray-500 text-xs leading-relaxed">Pilih produk favorit Anda dan tambahkan ke keranjang belanja.</p>
                </div>

                <div class="text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-green-600 text-white rounded-full flex items-center justify-center text-xl font-black mb-6 shadow-xl shadow-green-200 relative z-10">
                        4
                    </div>
                    <h4 class="font-bold text-gray-900 mb-2">Checkout</h4>
                    <p class="text-gray-500 text-xs leading-relaxed">Selesaikan pembayaran dan terima produk di lokasi Anda.</p>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection