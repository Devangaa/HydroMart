@extends('layouts.app')

@section('title', 'Checkout Layanan')

@section('content')
<div class="w-full min-h-screen bg-gray-50/50 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumb --}}
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('layanan.index') }}" class="text-sm font-bold text-gray-400 hover:text-green-600 transition-colors flex items-center gap-2">
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
                Checkout Layanan
            </span>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-4 tracking-tight">Checkout Layanan</h1>
            <p class="text-gray-500 text-sm mt-2 font-medium">Lengkapi informasi pengiriman dan metode pembayaran</p>
        </div>

        @if($isLayananGeofencing)
            <div class="mb-8 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-4">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-amber-800">Wilayah Layanan Terbatas</h4>
                    <p class="text-xs text-amber-700 mt-1 leading-relaxed">
                        Maaf, layanan ini saat ini hanya tersedia untuk wilayah <strong>Jawa Timur, Kota Jember</strong> (Kecamatan: Sumbersari, Patrang, Kaliwates).
                    </p>
                </div>
            </div>
        @endif

        @php
            $fotoLayanan = $layanan->foto_layanan;
            if (is_array($fotoLayanan)) {
                $fotoLayanan = $fotoLayanan[0] ?? null;
            } elseif (is_string($fotoLayanan)) {
                $decodedFoto = json_decode($fotoLayanan, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedFoto)) {
                    $fotoLayanan = $decodedFoto[0] ?? $fotoLayanan;
                }
            }
            $photoUrl = $fotoLayanan ? asset('uploads/layanan/' . $fotoLayanan) : 'https://ui-avatars.com/api/?name=' . urlencode($layanan->nama_layanan);
        @endphp

        <form action="{{ route('checkout.layanan.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            <input type="hidden" name="layanan_id" value="{{ $layanan->id }}">

            {{-- Kolom Utama (Kiri) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Detail Layanan Card --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-200 flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center border border-green-100 text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Detail Layanan</h3>
                            <p class="text-gray-400 text-xs font-medium">Informasi layanan yang akan dipesan</p>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                            <div class="flex gap-4 items-start">
                                <img src="{{ $photoUrl }}" alt="{{ $layanan->nama_layanan }}" 
                                     class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-2xl border border-gray-100 shrink-0">
                                
                                <div class="flex-1 min-w-0 sm:hidden">
                                    <h4 class="text-sm font-black text-gray-900 leading-tight line-clamp-2">{{ $layanan->nama_layanan }}</h4>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mt-1">{{ $layanan->kategori ?? 'Layanan' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="hidden sm:flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-lg font-black text-gray-900 leading-tight">{{ $layanan->nama_layanan }}</h4>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">{{ $layanan->kategori ?? 'Layanan' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[9px] text-gray-400 uppercase font-black leading-none mb-1">Harga</p>
                                        <p class="text-lg font-black text-green-600 leading-none">Rp{{ number_format($layanan->harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="bg-gray-50/50 p-3 rounded-2xl border border-gray-100 sm:hidden">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase">Harga</p>
                                    <p class="text-sm font-black text-green-600 leading-none">Rp{{ number_format($layanan->harga, 0, ',', '.') }}</p>
                                </div>

                                {{-- Catatan --}}
                                <div class="mt-4">
                                    <label class="block text-[9px] text-gray-400 uppercase font-black mb-1.5 flex items-center gap-1.5 ml-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Catatan untuk pesanan ini
                                    </label>
                                    <textarea name="catatan" 
                                              rows="2" 
                                              placeholder="Contoh: Jam kedatangan, detail permintaan khusus..."
                                              class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 focus:outline-none transition text-xs placeholder-gray-300"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informasi Lokasi Card --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100">
                    <div class="p-8 border-b border-gray-200 flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center border border-blue-100 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Lokasi & Penerima</h3>
                            <p class="text-gray-400 text-xs font-medium">Tentukan lokasi pengerjaan layanan</p>
                        </div>
                    </div>

                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Pemesan</label>
                                <input type="text" name="nama_penerima" value="{{ auth()->user()->nama_lengkap ?? auth()->user()->username }}"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300"
                                       placeholder="Masukkan nama pemesan"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor HP</label>
                                <input type="tel" name="no_hp" value="{{ auth()->user()->no_hp ?? '' }}"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300"
                                       placeholder="Masukkan nomor hp"
                                       required>
                            </div>
                        </div>

                        <div x-data="checkoutDropdown({{ $isLayananGeofencing ? 'true' : 'false' }}, {{ json_encode($provinces) }}, '{{ rtrim((string)parse_url(url('/'), PHP_URL_PATH), '/') }}', {{ json_encode($kecamatans ?? []) }}, {{ json_encode($layananProvince ?? null) }}, {{ json_encode($layananCity ?? null) }})" x-init="init()" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Provinsi --}}
                            <div class="relative" x-data="{ open: false }">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Provinsi</label>
                                <input type="hidden" name="province_id" :value="selectedProvince">
                                <button type="button" @click="if(!isSayuran) { open = !open; if(open) $nextTick(() => $refs.provSearch.focus()) }" 
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-left flex items-center justify-between gap-2"
                                        :class="isSayuran ? 'opacity-70 cursor-not-allowed bg-gray-100' : ''">
                                    <span :class="selectedProvinceName ? 'text-gray-800' : 'text-gray-400'" x-text="selectedProvinceName || 'Pilih Provinsi'"></span>
                                </button>
                                <div x-show="open" x-cloak @click.away="open = false" class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden">
                                    <div class="p-2 border-b border-gray-50 bg-gray-50/50">
                                        <input type="text" x-model="provinceSearch" x-ref="provSearch" placeholder="Cari provinsi..."
                                               class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none placeholder-gray-300 font-medium">
                                    </div>
                                    <div class="max-h-52 overflow-y-auto">
                                        <template x-if="filteredProvinces.length === 0">
                                            <p class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ditemukan</p>
                                        </template>
                                        <template x-for="prov in filteredProvinces" :key="prov.id">
                                            <button type="button" @click="selectProvince(prov); open = false" class="w-full text-left px-4 py-3 text-sm hover:bg-green-50 transition" x-text="prov.name"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            {{-- Kota --}}
                            <div class="relative" x-data="{ open: false }">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kota</label>
                                <input type="hidden" name="city_id" :value="selectedCity">
                                <button type="button" @click="if(selectedProvince && !isSayuran) { open = !open; if(open) $nextTick(() => $refs.citySearch.focus()) }" :disabled="!selectedProvince || isSayuran"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-left flex items-center justify-between gap-2" :class="{'opacity-70 cursor-not-allowed bg-gray-100': isSayuran || !selectedProvince}">
                                    <span :class="selectedCityName ? 'text-gray-800' : 'text-gray-400'" x-text="loadingCities ? 'Memuat...' : (selectedCityName || 'Pilih Kota')"></span>
                                </button>
                                <div x-show="open" x-cloak @click.away="open = false" class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden">
                                    <div class="p-2 border-b border-gray-50 bg-gray-50/50">
                                        <input type="text" x-model="citySearch" x-ref="citySearch" placeholder="Cari kota..."
                                               class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none placeholder-gray-300 font-medium">
                                    </div>
                                    <div class="max-h-52 overflow-y-auto">
                                        <template x-if="filteredCities.length === 0">
                                            <p class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ditemukan</p>
                                        </template>
                                        <template x-for="city in filteredCities" :key="city.id">
                                            <button type="button" @click="selectCity(city); open = false" class="w-full text-left px-4 py-3 text-sm hover:bg-green-50 transition" x-text="city.name"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            {{-- Kecamatan --}}
                            <div class="relative" x-data="{ open: false }">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kecamatan</label>
                                <input type="hidden" name="kecamatan_id" :value="selectedKecamatan">
                                <button type="button" @click="if(selectedCity) { open = !open; if(open) $nextTick(() => $refs.kecSearch.focus()) }" :disabled="!selectedCity"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-left flex items-center justify-between gap-2" :class="{'opacity-50 cursor-not-allowed': !selectedCity}">
                                    <span :class="selectedKecamatanName ? 'text-gray-800' : 'text-gray-400'" x-text="loadingKecamatan ? 'Memuat...' : (selectedKecamatanName || 'Pilih Kecamatan')"></span>
                                    <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" x-cloak @click.away="open = false" class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden">
                                    <div class="p-2 border-b border-gray-50 bg-gray-50/50">
                                        <input type="text" x-model="kecamatanSearch" x-ref="kecSearch" placeholder="Cari kecamatan..."
                                               class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none placeholder-gray-300 font-medium">
                                    </div>
                                    <div class="max-h-52 overflow-y-auto">
                                        <template x-if="filteredKecamatan.length === 0">
                                            <p class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ditemukan</p>
                                        </template>
                                        <template x-for="kec in filteredKecamatan" :key="kec.id">
                                            <button type="button" @click="selectKecamatan(kec); open = false" class="w-full text-left px-4 py-3 text-sm hover:bg-green-50 transition" x-text="kec.name"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                            <span class="font-bold text-gray-900">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ongkir</span>
                            <span class="font-bold text-green-600" id="ongkir-text">Pilih Lokasi</span>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex justify-between">
                            <span class="font-bold text-gray-900">Total</span>
                            <span class="text-xl font-black text-green-600" id="total">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
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

                    <div class="p-6 space-y-3" x-data="paymentMethod()">
                        @php
                        $methods = [
                            [
                                'id' => 'cod',
                                'name' => 'Bayar di Lokasi (COD)',
                                'desc' => 'Bayar setelah layanan selesai',
                                'logo' => null,
                                'bg' => 'bg-amber-50',
                                'icon' => 'wallet',
                                'is_cod' => true
                            ],
                            [
                                'id' => 'bca',
                                'name' => 'BCA Virtual Account',
                                'desc' => 'Transfer via BCA m-Banking atau ATM',
                                'logo' => 'bca.png',
                                'bg' => 'bg-blue-50',
                                'is_cod' => false
                            ],
                            [
                                'id' => 'mandiri',
                                'name' => 'Mandiri Bill Payment',
                                'desc' => 'Bayar via Mandiri Livin atau ATM',
                                'logo' => 'mandiri.png',
                                'bg' => 'bg-yellow-50',
                                'is_cod' => false
                            ],
                            [
                                'id' => 'qris',
                                'name' => 'QRIS (Gopay/OVO/Dana)',
                                'desc' => 'Scan kode QR dengan aplikasi e-wallet',
                                'logo' => 'qris.png',
                                'bg' => 'bg-red-50',
                                'is_cod' => false
                            ],
                        ];
                        @endphp

                        <input type="hidden" name="metode_pembayaran" :value="selectedMethod">

                        @foreach($methods as $method)
                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition relative group"
                            :class="selectedMethod === '{{ $method['id'] }}' 
                                        ? 'border-green-500 bg-green-50' 
                                        : ({{ $method['is_cod'] ? 'isCodDisabled' : 'false' }} ? 'border-gray-200 bg-gray-50 opacity-50 cursor-not-allowed' : 'border-gray-200 hover:border-green-300 bg-white')"
                            @click="if (!({{ $method['is_cod'] ? 'isCodDisabled' : 'false' }})) selectedMethod = '{{ $method['id'] }}'">
                            
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-14 h-14 {{ $method['bg'] }} rounded-lg flex items-center justify-center border border-gray-100 shrink-0 p-1">
                                    @if($method['is_cod'])
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    @else
                                        <img src="{{ asset('img/logos/' . $method['logo']) }}" 
                                            alt="Logo {{ $method['name'] }}" 
                                            class="max-w-full max-h-full object-contain">
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <span class="font-semibold text-gray-900 text-sm block">{{ $method['name'] }}</span>
                                    <span class="text-xs text-gray-500">{{ $method['desc'] }}</span>
                                    @if($method['is_cod'])
                                        <template x-if="isCodDisabled">
                                            <p class="text-xs text-red-600 mt-1 font-medium">
                                                Hanya tersedia untuk wilayah Jember tertentu
                                            </p>
                                        </template>
                                    @endif
                                </div>

                                <div x-show="selectedMethod === '{{ $method['id'] }}'" class="text-green-600 flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Tombol Submit --}}
                <button type="submit" class="w-full bg-green-600 text-white font-bold py-4 px-6 rounded-xl hover:bg-green-700 shadow-lg shadow-green-100 transition duration-200">
                    Pesan Layanan Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<div id="checkout-layanan-config" class="hidden" data-config='@json([
    "totalWeight" => $totalWeight,
    "grandTotal" => $grandTotal,
    "basePath" => rtrim((string) parse_url(url("/"), PHP_URL_PATH), "/"),
    "useDistrictsApi" => false,
])'></div>

<!--
<script>
    function formatCurrency(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    async function fetchOngkirAjax(kecamatanId, totalWeight, grandTotal, basePath) {
        const ongkirText = document.getElementById('ongkir-text');
        const totalText = document.getElementById('total');
        
        ongkirText.textContent = 'Menghitung...';
        ongkirText.classList.remove('text-green-600');
        ongkirText.classList.add('text-gray-400');

        try {
            const response = await fetch(`${basePath}/cek-ongkir?kecamatan_id=${kecamatanId}&total_weight=${totalWeight}`);
            const data = await response.json();

            if (data.success) {
                if (data.ongkir === 0) {
                    ongkirText.textContent = 'Gratis';
                    ongkirText.classList.add('text-green-600');
                    ongkirText.classList.remove('text-gray-400', 'text-gray-900');
                } else {
                    ongkirText.textContent = data.formatted_ongkir;
                    ongkirText.classList.add('text-gray-900');
                    ongkirText.classList.remove('text-green-600', 'text-gray-400');
                }

                totalText.textContent = `Rp${formatCurrency(grandTotal + data.ongkir)}`;
            } else {
                ongkirText.textContent = 'Gagal memuat';
            }
        } catch (error) {
            console.error('Error fetching ongkir:', error);
            ongkirText.textContent = 'Gagal memuat';
        }
    }

    // Alpine.js component for payment method
    function paymentMethod() {
        return {
            selectedMethod: 'bca', // Default to BCA
            isCodDisabled: true,

            init() {
                this.updateCodAvailability();
                const observer = new MutationObserver(() => this.updateCodAvailability());
                const provinceInput = document.querySelector('input[name="province_id"]');
                const cityInput = document.querySelector('input[name="city_id"]');
                const kecamatanInput = document.querySelector('input[name="kecamatan_id"]');
                if (provinceInput) observer.observe(provinceInput, { attributes: true });
                if (cityInput) observer.observe(cityInput, { attributes: true });
                if (kecamatanInput) observer.observe(kecamatanInput, { attributes: true });
            },

            updateCodAvailability() {
                let provinceName = '';
                let cityName = '';
                let kecamatanName = '';

                const dropdownDiv = document.querySelector('[x-data*="checkoutDropdown"]');
                if (dropdownDiv && dropdownDiv._x_dataStack && dropdownDiv._x_dataStack[0]) {
                    provinceName = dropdownDiv._x_dataStack[0].selectedProvinceName || '';
                    cityName = dropdownDiv._x_dataStack[0].selectedCityName || '';
                    kecamatanName = dropdownDiv._x_dataStack[0].selectedKecamatanName || '';
                }

                const isJawatimur = provinceName.toLowerCase().includes('jawa timur');
                const isJember = cityName.toLowerCase().includes('jember');
                const allowedKecamatan = ['Sumbersari', 'Patrang', 'Kaliwates'];
                const isAllowedKecamatan = allowedKecamatan.some(k => kecamatanName.toLowerCase().includes(k.toLowerCase()));

                this.isCodDisabled = !(isJawatimur && isJember && isAllowedKecamatan);

                if (this.isCodDisabled && this.selectedMethod === 'cod') {
                    this.selectedMethod = 'bca';
                }
            },
        }
    }

    // Alpine.js component for cascading dropdowns
    function checkoutDropdown(isSayuran, initialProvinces, basePath = '', restrictedKecamatans = [], lockedProvince = null, lockedCity = null) {
        return {
            isSayuran: isSayuran,
            provinces: initialProvinces,
            cities: [],
            kecamatan: restrictedKecamatans,
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
                this.onProvinceChange();
            },

            selectCity(city) {
                this.selectedCity = city.id;
                this.selectedCityName = city.name;
                this.citySearch = '';
                this.selectedKecamatan = '';
                this.selectedKecamatanName = '';
                this.onCityChange();
            },

            selectKecamatan(kec) {
                this.selectedKecamatan = kec.id;
                this.selectedKecamatanName = kec.name;
                this.kecamatanSearch = '';
                
                // Fetch Ongkir
                const totalWeight = {{ $totalWeight }};
                const grandTotal = {{ $grandTotal }};
                fetchOngkirAjax(kec.id, totalWeight, grandTotal, basePath);
            },

            async init() {
                if (this.isSayuran && lockedProvince && lockedCity) {
                    this.selectedProvince = lockedProvince.id;
                    this.selectedProvinceName = lockedProvince.name;
                    this.selectedCity = lockedCity.id;
                    this.selectedCityName = lockedCity.name;
                }
            },

            async onProvinceChange() {
                if (!this.selectedProvince || this.isSayuran) return;
                this.loadingCities = true;
                try {
                    const res = await fetch(`${basePath}/api/cities-transaction/${this.selectedProvince}`);
                    this.cities = await res.json();
                } catch (e) { console.error(e); }
                finally { this.loadingCities = false; }
            },

            async onCityChange() {
                if (!this.selectedCity || this.isSayuran) return;
                this.loadingKecamatan = true;
                try {
                    const res = await fetch(`${basePath}/api/districts/${this.selectedCity}`);
                    this.kecamatan = await res.json();
                } catch (e) { console.error(e); }
                finally { this.loadingKecamatan = false; }
            }
        }
    }
</script>
-->
@endsection
