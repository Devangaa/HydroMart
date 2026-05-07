@extends('layouts.app')

@section('title', 'Checkout Produk')

@section('content')
<div class="w-full min-h-screen bg-gray-50/50 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumb --}}
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('produk.index') }}" class="text-sm font-bold text-gray-400 hover:text-green-600 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Katalog
                    </a>
                </li>
            </ol>
        </nav>

        <div class="mb-8">
            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">
                Checkout
            </span>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-4 tracking-tight">Checkout Produk</h1>
            <p class="text-gray-500 text-sm mt-2 font-medium">Lengkapi informasi pengiriman dan metode pembayaran</p>
        </div>

        @php
            $productFoto = $product->foto_produk;
            if (is_array($productFoto)) {
                $productFoto = $productFoto[0] ?? null;
            } elseif (is_string($productFoto)) {
                $decodedFoto = json_decode($productFoto, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedFoto)) {
                    $productFoto = $decodedFoto[0] ?? $productFoto;
                }
            }
            $photoUrl = $productFoto ? asset('storage/' . $productFoto) : 'https://ui-avatars.com/api/?name=' . urlencode($product->nama_produk);
        @endphp

        <form action="{{ route('checkout.produk.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            {{-- Kolom Utama (Kiri) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Detail Produk --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-200 flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center border border-green-100 text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Detail Produk</h3>
                            <p class="text-gray-400 text-xs font-medium">Informasi produk yang akan dipesan</p>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="flex gap-6 pb-6 border-b border-gray-100">
                            <img src="{{ $photoUrl }}" alt="{{ $product->nama_produk }}" 
                                 class="w-24 h-24 object-cover rounded-xl border border-gray-200">
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $product->nama_produk }}</h4>
                                <p class="text-sm text-gray-600 mb-3">{{ $product->deskripsi }}</p>
                                <div class="flex items-center gap-6">
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase font-bold">Harga per {{ $product->unit }}</p>
                                        <p class="text-xl font-black text-green-600">Rp{{ number_format($product->harga, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase font-bold">Stok Tersedia</p>
                                        <p class="text-xl font-black text-gray-900">{{ $product->jumlah_stok }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-bold text-gray-700 mb-3">Jumlah Pembelian</label>
                            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl w-fit">
                                <button type="button" 
                                        onclick="let qty = document.querySelector('input[name=jumlah]'); qty.value = Math.max(1, parseInt(qty.value) - 1); updateTotal({{ $product->harga }});"
                                        class="px-4 py-2 text-gray-600 hover:text-green-600 font-bold transition">-</button>
                                <input type="number" name="jumlah" value="{{ $qty }}" min="1" max="{{ $product->jumlah_stok }}"
                                       class="w-20 text-center py-2 font-bold text-gray-900 bg-transparent border-none focus:ring-0"
                                       oninput="updateTotal({{ $product->harga }});"
                                       required>
                                <button type="button"
                                        onclick="let qty = document.querySelector('input[name=jumlah]'); qty.value = Math.min({{ $product->jumlah_stok }}, parseInt(qty.value) + 1); updateTotal({{ $product->harga }});"
                                        class="px-4 py-2 text-gray-600 hover:text-green-600 font-bold transition">+</button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Maksimal {{ $product->jumlah_stok }}</p>
                        </div>

                        @if($isSayuran)
                        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-bold text-yellow-800">Pengiriman Sayuran Terbatas</h4>
                                    <p class="text-sm text-yellow-700 mt-1">Produk sayuran hanya dapat dikirim ke Kecamatan Sumbersari, Patrang, dan Kaliwates, Kabupaten Jember, Jawa Timur.</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="mt-6">
                            <label class="block text-sm font-bold text-gray-700 mb-3">Catatan Pesanan (Opsional)</label>
                            <textarea name="catatan" rows="3" placeholder="Tambahkan catatan khusus untuk pesanan Anda..."
                                      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Informasi Pengiriman --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-200 flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center border border-blue-100 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Alamat Pengiriman</h3>
                            <p class="text-gray-400 text-xs font-medium">Tentukan lokasi penerima pesanan</p>
                        </div>
                    </div>

                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Penerima</label>
                                <input type="text" name="nama_penerima" value="{{ auth()->user()->nama_lengkap ?? auth()->user()->username }}"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300"
                                       placeholder="Masukkan nama penerima"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor HP</label>
                                <input type="tel" name="no_hp" value="{{ auth()->user()->no_hp ?? '' }}"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300"
                                       placeholder="Masukkan nomor hp penerima"
                                       required>
                            </div>
                        </div>

                        {{-- Provinsi, Kota, Kecamatan --}}
                        @if($isSayuran)
                            {{-- MODE SAYURAN: Readonly Provinsi & Kota, Dropdown Kecamatan --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                {{-- Provinsi (Readonly) --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Provinsi</label>
                                    <input type="hidden" name="province_id" value="{{ $sayuranProvince->id ?? '' }}">
                                    <input type="text"
                                           value="{{ $sayuranProvince->name ?? 'Jawa Timur' }}"
                                           readonly
                                           class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 cursor-not-allowed">
                                    <p class="text-xs text-gray-400 mt-1">Pengiriman sayuran hanya ke Jawa Timur</p>
                                </div>

                                {{-- Kota (Readonly) --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kota / Kabupaten</label>
                                    <input type="hidden" name="city_id" value="{{ $sayuranCity->id ?? '' }}">
                                    <input type="text"
                                           value="{{ $sayuranCity->name ?? 'Kabupaten Jember' }}"
                                           readonly
                                           class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 cursor-not-allowed">
                                    <p class="text-xs text-gray-400 mt-1">Hanya wilayah Kab. Jember</p>
                                </div>

                                {{-- Kecamatan Dropdown (Hanya 3 pilihan) --}}
                                <div class="relative"
                                     x-data="{
                                         open: false,
                                         selected: '',
                                         selectedName: '',
                                         search: '',
                                         options: {{ json_encode($kecamatans->map(fn($k) => ['id' => $k->id, 'name' => $k->name])) }},
                                         get filtered() {
                                             if (!this.search) return this.options;
                                             const q = this.search.toLowerCase();
                                             return this.options.filter(o => o.name.toLowerCase().includes(q));
                                         },
                                         select(opt) {
                                             this.selected = opt.id;
                                             this.selectedName = opt.name;
                                             this.search = '';
                                             this.open = false;
                                         }
                                     }"
                                     @keydown.escape="open = false">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kecamatan</label>
                                    <input type="hidden" name="kecamatan_id" :value="selected">

                                    <button type="button"
                                        @click="open = !open"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition text-sm font-semibold text-left flex items-center justify-between gap-2"
                                        :class="{ 'ring-2 ring-green-500 border-green-300': open, 'hover:border-gray-300': true }"
                                    >
                                        <span :class="selectedName ? 'text-gray-800' : 'text-gray-400'"
                                              x-text="selectedName || 'Pilih Kecamatan'"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    <div x-show="open"
                                         x-cloak
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 -translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-2"
                                         class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden z-50">
                                        <div class="px-3 pt-3 pb-2 border-b border-gray-50">
                                            <input type="text" x-model="search" placeholder="Cari kecamatan..."
                                                   class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-100 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none placeholder-gray-300">
                                        </div>
                                        <div class="max-h-52 overflow-y-auto">
                                            <template x-if="filtered.length === 0">
                                                <p class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ditemukan</p>
                                            </template>
                                            <template x-for="opt in filtered" :key="opt.id">
                                                <button type="button"
                                                    @click="select(opt)"
                                                    class="w-full text-left px-4 py-3 text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-600 transition"
                                                    :class="selected == opt.id ? 'bg-green-50 text-green-600' : ''"
                                                    x-text="opt.name">
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>


                            </div>

                        @else
                            {{-- MODE NORMAL: Cascading Dropdown via Alpine.js --}}
                            <div x-data="checkoutDropdown(false, {{ json_encode($provinces) }}, '{{ rtrim((string)parse_url(url('/'), PHP_URL_PATH), '/') }}')" x-init="init()" class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                {{-- Provinsi Custom Dropdown --}}
                                <div class="relative" x-data="{ open: false }" @keydown.escape="open = false">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Provinsi</label>
                                    <input type="hidden" name="province_id" :value="selectedProvince">

                                    <button type="button"
                                        @click="open = !open"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition text-sm font-semibold text-left flex items-center justify-between gap-2"
                                        :class="{ 'ring-2 ring-green-500 border-green-300': open, 'hover:border-gray-300': true }"
                                    >
                                        <span :class="selectedProvinceName ? 'text-gray-800' : 'text-gray-400'"
                                              x-text="selectedProvinceName || 'Pilih Provinsi'"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    <div x-show="open"
                                         x-cloak
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 -translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-2"
                                         class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden z-50">
                                        <div class="px-3 pt-3 pb-2 border-b border-gray-50">
                                            <input type="text" x-model="provinceSearch" placeholder="Cari provinsi..."
                                                   class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-100 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none placeholder-gray-300">
                                        </div>
                                        <div class="max-h-52 overflow-y-auto">
                                            <template x-if="filteredProvinces.length === 0">
                                                <p class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ditemukan</p>
                                            </template>
                                            <template x-for="prov in filteredProvinces" :key="prov.id">
                                                <button type="button"
                                                    @click="selectProvince(prov); open = false"
                                                    class="w-full text-left px-4 py-3 text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-600 transition"
                                                    :class="selectedProvince == prov.id ? 'bg-green-50 text-green-600' : ''"
                                                    x-text="prov.name">
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                {{-- Kota / Kabupaten Custom Dropdown --}}
                                <div class="relative" x-data="{ open: false }" @keydown.escape="open = false">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kota / Kabupaten</label>
                                    <input type="hidden" name="city_id" :value="selectedCity">

                                    <button type="button"
                                        @click="if (selectedProvince && !loadingCities) open = !open"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition text-sm font-semibold text-left flex items-center justify-between gap-2"
                                        :class="{
                                            'ring-2 ring-green-500 border-green-300': open,
                                            'hover:border-gray-300': selectedProvince && !loadingCities,
                                            'opacity-50 cursor-not-allowed': !selectedProvince || loadingCities
                                        }"
                                        :disabled="!selectedProvince || loadingCities"
                                    >
                                        <span class="flex items-center gap-2" :class="selectedCityName ? 'text-gray-800' : 'text-gray-400'">
                                            <svg x-show="loadingCities" class="animate-spin h-4 w-4 text-green-500 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            <span x-text="loadingCities ? 'Memuat...' : (selectedCityName || 'Pilih Kota / Kabupaten')"></span>
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    <div x-show="open"
                                         x-cloak
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 -translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-2"
                                         class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden z-50">
                                        <div class="px-3 pt-3 pb-2 border-b border-gray-50">
                                            <input type="text" x-model="citySearch" placeholder="Cari kota..."
                                                   class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-100 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none placeholder-gray-300">
                                        </div>
                                        <div class="max-h-52 overflow-y-auto">
                                            <template x-if="filteredCities.length === 0">
                                                <p class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ditemukan</p>
                                            </template>
                                            <template x-for="city in filteredCities" :key="city.id">
                                                <button type="button"
                                                    @click="selectCity(city); open = false"
                                                    class="w-full text-left px-4 py-3 text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-600 transition"
                                                    :class="selectedCity == city.id ? 'bg-green-50 text-green-600' : ''"
                                                    x-text="city.name">
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                {{-- Kecamatan Custom Dropdown --}}
                                <div class="relative" x-data="{ open: false }" @keydown.escape="open = false">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kecamatan</label>
                                    <input type="hidden" name="kecamatan_id" :value="selectedKecamatan">

                                    <button type="button"
                                        @click="if (selectedCity && !loadingKecamatan) open = !open"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition text-sm font-semibold text-left flex items-center justify-between gap-2"
                                        :class="{
                                            'ring-2 ring-green-500 border-green-300': open,
                                            'hover:border-gray-300': selectedCity && !loadingKecamatan,
                                            'opacity-50 cursor-not-allowed': !selectedCity || loadingKecamatan
                                        }"
                                        :disabled="!selectedCity || loadingKecamatan"
                                    >
                                        <span class="flex items-center gap-2" :class="selectedKecamatanName ? 'text-gray-800' : 'text-gray-400'">
                                            <svg x-show="loadingKecamatan" class="animate-spin h-4 w-4 text-green-500 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            <span x-text="loadingKecamatan ? 'Memuat...' : (selectedKecamatanName || 'Pilih Kecamatan')"></span>
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    <div x-show="open"
                                         x-cloak
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 -translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-2"
                                         class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden z-50">
                                        <div class="px-3 pt-3 pb-2 border-b border-gray-50">
                                            <input type="text" x-model="kecamatanSearch" placeholder="Cari kecamatan..."
                                                   class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-100 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none placeholder-gray-300">
                                        </div>
                                        <div class="max-h-52 overflow-y-auto">
                                            <template x-if="filteredKecamatan.length === 0">
                                                <p class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ditemukan</p>
                                            </template>
                                            <template x-for="kec in filteredKecamatan" :key="kec.id">
                                                <button type="button"
                                                    @click="selectKecamatan(kec); open = false"
                                                    class="w-full text-left px-4 py-3 text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-600 transition"
                                                    :class="selectedKecamatan == kec.id ? 'bg-green-50 text-green-600' : ''"
                                                    x-text="kec.name">
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" rows="3" placeholder="Jl. Contoh No. 123, RT/RW 01/02..."
                                      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300"
                                      required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Kanan --}}
            <div class="lg:col-span-1 lg:sticky lg:top-24 lg:self-start h-fit space-y-6">
                {{-- Ringkasan Pesanan --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center border border-purple-100 text-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm">Ringkasan Pesanan</h3>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-bold text-gray-900" id="subtotal">Rp{{ number_format($product->harga * $qty, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ongkir</span>
                            <span class="font-bold text-green-600">Gratis</span>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex justify-between">
                            <span class="font-bold text-gray-900">Total</span>
                            <span class="text-xl font-black text-green-600" id="total">Rp{{ number_format($product->harga * $qty, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center border border-blue-100 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10M7 20h10M5 8h14a2 2 0 002-2V4a2 2 0 00-2-2H5a2 2 0 00-2 2v2a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm">Metode Pembayaran</h3>
                        </div>
                    </div>

                    <div class="p-6 space-y-4" x-data="paymentMethod()">
                        {{-- COD Option --}}
                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition"
                               :class="selectedMethod === 'cod' 
                                   ? 'border-green-500 bg-green-50' 
                                   : (isCodDisabled ? 'border-gray-200 bg-gray-50 opacity-50 cursor-not-allowed' : 'border-gray-200 hover:border-green-300 bg-white')"
                               @click="isCodDisabled ? $event.preventDefault() : ''">
                            <input type="radio" name="metode_pembayaran" value="cod" 
                                   class="text-green-600"
                                   :disabled="isCodDisabled"
                                   @change="selectedMethod = 'cod'">
                            <div class="ml-3">
                                <span class="font-semibold text-gray-900 text-sm block">Cash on Delivery</span>
                                <span class="text-xs text-gray-500">Bayar saat barang diterima</span>
                                <template x-if="isCodDisabled">
                                    <p class="text-xs text-red-600 mt-1 font-medium">
                                        Hanya tersedia untuk Jember (Sumbersari, Patrang, Kaliwates)
                                    </p>
                                </template>
                            </div>
                        </label>

                        {{-- Transfer Option --}}
                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition"
                               :class="selectedMethod === 'midtrans' 
                                   ? 'border-green-500 bg-green-50' 
                                   : 'border-gray-200 hover:border-green-300 bg-white'">
                            <input type="radio" name="metode_pembayaran" value="midtrans" 
                                   class="text-green-600"
                                   @change="selectedMethod = 'midtrans'">
                            <div class="ml-3">
                                <span class="font-semibold text-gray-900 text-sm block">Transfer Bank / E-Wallet</span>
                                <span class="text-xs text-gray-500">Via Midtrans (QRIS, Transfer Bank, dll)</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Tombol Submit --}}
                <button type="submit" class="w-full bg-green-600 text-white font-bold py-4 px-6 rounded-xl hover:bg-green-700 shadow-lg shadow-green-100 transition duration-200">
                    Buat Pesanan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Format currency to Indonesian Rupiah format (Rp1.234.567)
    function formatCurrency(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Update total price based on quantity
    function updateTotal(productPrice) {
        const qtyInput = document.querySelector('input[name=jumlah]');
        if (!qtyInput) return;

        const qty = parseInt(qtyInput.value) || 1;
        const total = productPrice * qty;
        const subtotalElement = document.getElementById('subtotal');
        const totalElement = document.getElementById('total');
        
        const formattedTotal = `Rp${formatCurrency(total)}`;
        
        if (subtotalElement) {
            subtotalElement.textContent = formattedTotal;
        }
        if (totalElement) {
            totalElement.textContent = formattedTotal;
        }
    }

    // Alpine.js component for payment method
    function paymentMethod() {
        return {
            selectedMethod: 'midtrans', // Default to transfer
            isCodDisabled: true,

            init() {
                // Set default radio button
                setTimeout(() => {
                    const midtransRadio = document.querySelector('input[name="metode_pembayaran"][value="midtrans"]');
                    if (midtransRadio) {
                        midtransRadio.checked = true;
                    }
                }, 0);

                // Check COD availability
                this.updateCodAvailability();

                // Listen for location changes
                const observer = new MutationObserver(() => {
                    this.updateCodAvailability();
                });

                // Watch for changes in the hidden input fields
                const provinceInput = document.querySelector('input[name="province_id"]');
                const cityInput = document.querySelector('input[name="city_id"]');
                const kecamatanInput = document.querySelector('input[name="kecamatan_id"]');

                if (provinceInput) observer.observe(provinceInput, { attributes: true });
                if (cityInput) observer.observe(cityInput, { attributes: true });
                if (kecamatanInput) observer.observe(kecamatanInput, { attributes: true });
            },

            updateCodAvailability() {
                let provinceName = 'Jawa Timur';
                let cityName = 'Kabupaten Jember';
                let kecamatanName = '';

                // Try to get kecamatan from Alpine components
                // First check for normal mode (checkoutDropdown)
                const dropdownDiv = document.querySelector('[x-data*="checkoutDropdown"]');
                if (dropdownDiv && dropdownDiv._x_dataStack && dropdownDiv._x_dataStack[0]) {
                    provinceName = dropdownDiv._x_dataStack[0].selectedProvinceName || provinceName;
                    cityName = dropdownDiv._x_dataStack[0].selectedCityName || cityName;
                    kecamatanName = dropdownDiv._x_dataStack[0].selectedKecamatanName || '';
                }

                // For sayuran mode, look for the kecamatan select component
                if (!kecamatanName) {
                    const kecamatanSelectors = document.querySelectorAll('[x-data*="open: false"]');
                    for (let selector of kecamatanSelectors) {
                        if (selector._x_dataStack && selector._x_dataStack[0]) {
                            const data = selector._x_dataStack[0];
                            if (data.selectedName || data.select) {
                                // This looks like the sayuran kecamatan selector
                                kecamatanName = data.selectedName || '';
                                break;
                            }
                        }
                    }
                }

                // Last fallback: search all x-data components for the one with selectedName
                if (!kecamatanName) {
                    const allComponents = document.querySelectorAll('[x-data]');
                    for (let comp of allComponents) {
                        if (comp._x_dataStack && comp._x_dataStack[0]) {
                            const data = comp._x_dataStack[0];
                            if (data.selectedName && (data.selectedName === 'Sumbersari' || data.selectedName === 'Patrang' || data.selectedName === 'Kaliwates')) {
                                kecamatanName = data.selectedName;
                                break;
                            }
                        }
                    }
                }

                console.log('Location Check:', { provinceName, cityName, kecamatanName });

                // Check if location is valid for COD
                const isJawatimur = provinceName.toLowerCase().includes('jawa timur');
                const isJember = cityName.toLowerCase().includes('jember');
                const allowedKecamatan = ['Sumbersari', 'Patrang', 'Kaliwates'];
                const isAllowedKecamatan = allowedKecamatan.some(k => kecamatanName.toLowerCase().includes(k.toLowerCase()));

                console.log('COD Availability Check:', { isJawatimur, isJember, isAllowedKecamatan, hasKecamatan: !!kecamatanName });

                this.isCodDisabled = !(isJawatimur && isJember && isAllowedKecamatan && kecamatanName);

                console.log('isCodDisabled:', this.isCodDisabled);

                // If COD becomes disabled and it was selected, switch to transfer
                if (this.isCodDisabled && this.selectedMethod === 'cod') {
                    this.selectedMethod = 'midtrans';
                    const midtransRadio = document.querySelector('input[name="metode_pembayaran"][value="midtrans"]');
                    if (midtransRadio) midtransRadio.checked = true;
                }
            },
        }
    }

    // Alpine.js component for cascading dropdowns
    function checkoutDropdown(isSayuran, initialProvinces, basePath = '') {
        return {
            provinces: initialProvinces,
            cities: [],
            kecamatan: [],

            selectedProvince: '',
            selectedCity: '',
            selectedKecamatan: '',

            selectedProvinceName: '',
            selectedCityName: '',
            selectedKecamatanName: '',

            provinceSearch: '',
            citySearch: '',
            kecamatanSearch: '',

            loadingCities: false,
            loadingKecamatan: false,

            isProvinceDisabled: isSayuran,
            isCityDisabled: isSayuran,

            get filteredProvinces() {
                if (!this.provinceSearch) return this.provinces;
                const q = this.provinceSearch.toLowerCase();
                return this.provinces.filter(p => p.name.toLowerCase().includes(q));
            },

            get filteredCities() {
                if (!this.citySearch) return this.cities;
                const q = this.citySearch.toLowerCase();
                return this.cities.filter(c => c.name.toLowerCase().includes(q));
            },

            get filteredKecamatan() {
                if (!this.kecamatanSearch) return this.kecamatan;
                const q = this.kecamatanSearch.toLowerCase();
                return this.kecamatan.filter(k => k.name.toLowerCase().includes(q));
            },

            selectProvince(prov) {
                this.selectedProvince = prov.id;
                this.selectedProvinceName = prov.name;
                this.provinceSearch = '';
                this.selectedCity = '';
                this.selectedCityName = '';
                this.selectedKecamatan = '';
                this.selectedKecamatanName = '';
                this.cities = [];
                this.kecamatan = [];
                this.onProvinceChange();
                this.notifyLocationChange();
            },

            selectCity(city) {
                this.selectedCity = city.id;
                this.selectedCityName = city.name;
                this.citySearch = '';
                this.selectedKecamatan = '';
                this.selectedKecamatanName = '';
                this.kecamatan = [];
                this.onCityChange();
                this.notifyLocationChange();
            },

            selectKecamatan(kec) {
                this.selectedKecamatan = kec.id;
                this.selectedKecamatanName = kec.name;
                this.kecamatanSearch = '';
                this.notifyLocationChange();
            },

            notifyLocationChange() {
                // Trigger update in payment method component with a slight delay
                setTimeout(() => {
                    const paymentDiv = document.querySelector('[x-data*="paymentMethod"]');
                    if (paymentDiv && paymentDiv._x_dataStack && paymentDiv._x_dataStack[0]) {
                        console.log('Notifying payment method of location change');
                        paymentDiv._x_dataStack[0].updateCodAvailability();
                    }
                }, 50);
            },

            async init() {
                if (isSayuran) {
                    const jawatimur = this.provinces.find(p => p.name === 'Jawa Timur');
                    if (jawatimur) {
                        this.selectedProvince = jawatimur.id;
                        this.selectedProvinceName = jawatimur.name;
                        await this.onProvinceChange();
                        
                        const jember = this.cities.find(c => c.name.toLowerCase().includes('kabupaten jember'));
                        if (jember) {
                            this.selectedCity = jember.id;
                            this.selectedCityName = jember.name;
                            await this.onCityChange();
                        }
                    }
                }
                this.notifyLocationChange();
            },

            async onProvinceChange() {
                if (!this.selectedProvince) {
                    this.cities = [];
                    this.kecamatan = [];
                    this.selectedCity = '';
                    this.selectedCityName = '';
                    this.selectedKecamatan = '';
                    this.selectedKecamatanName = '';
                    return;
                }

                this.loadingCities = true;
                this.cities = [];
                this.kecamatan = [];

                try {
                    const apiPath = basePath ? `${basePath}/api/cities/${this.selectedProvince}` : `/api/cities/${this.selectedProvince}`;
                    const response = await fetch(apiPath);
                    this.cities = await response.json();
                } catch (error) {
                    console.error('Error fetching cities:', error);
                } finally {
                    this.loadingCities = false;
                }
            },

            async onCityChange() {
                if (!this.selectedCity) {
                    this.kecamatan = [];
                    this.selectedKecamatan = '';
                    this.selectedKecamatanName = '';
                    return;
                }

                this.loadingKecamatan = true;
                this.kecamatan = [];

                try {
                    const apiPath = basePath ? `${basePath}/api/districts/${this.selectedCity}` : `/api/districts/${this.selectedCity}`;
                    const response = await fetch(apiPath);
                    let kecamatan = await response.json();

                    if (isSayuran) {
                        const allowedKecamatan = ['Sumbersari', 'Patrang', 'Kaliwates'];
                        kecamatan = kecamatan.filter(k => allowedKecamatan.includes(k.name));
                    }

                    this.kecamatan = kecamatan;
                } catch (error) {
                    console.error('Error fetching kecamatan:', error);
                } finally {
                    this.loadingKecamatan = false;
                }
            },
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateTotal({{ $product->harga }});
    });
</script>

@endsection
