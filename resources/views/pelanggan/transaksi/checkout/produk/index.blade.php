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
            $product = $items->first()->product;
            $qty = $items->first()->qty;
            $productFoto = $product->foto_produk;
            if (is_array($productFoto)) {
                $productFoto = $productFoto[0] ?? null;
            } elseif (is_string($productFoto)) {
                $decodedFoto = json_decode($productFoto, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedFoto)) {
                    $productFoto = $decodedFoto[0] ?? $productFoto;
                }
            }
            $photoUrl = $productFoto ? asset('uploads/produk/' . $productFoto) : 'https://ui-avatars.com/api/?name=' . urlencode($product->nama_produk);
        @endphp

        <form action="{{ route('checkout.produk.store') }}" method="POST" 
              x-data="checkoutSummary({{ $grandTotal }})"
              class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf
            
            {{-- Reward Selection Modal --}}
            <div id="rewardModal" class="fixed inset-0 z-[9999] overflow-y-auto" x-show="showRewardModal" x-cloak>
                <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                    <div class="fixed inset-0 transition-opacity bg-black/40 backdrop-blur-sm" @click="showRewardModal = false"></div>

                    <div class="relative inline-block w-full max-w-lg overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[2.5rem]"
                         x-show="showRewardModal" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                        
                        <div class="p-8 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                            <div>
                                <h3 class="text-xl font-black text-gray-900">Pilih Reward</h3>
                                <p class="text-xs text-gray-400 font-bold mt-1">Gunakan reward yang Anda miliki</p>
                            </div>
                            <button type="button" @click="showRewardModal = false" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="p-8 max-h-[60vh] overflow-y-auto space-y-8 custom-scrollbar">
                            {{-- Reward Bisa Digunakan --}}
                            <div>
                                <h4 class="text-[10px] font-black text-green-600 uppercase tracking-[0.2em] mb-4">Tersedia</h4>
                                <div class="space-y-3">
                                    <template x-for="reward in availableRewards.filter(r => subtotal >= r.reward.minimal_pembelian)" :key="reward.id">
                                        <button type="button" @click="selectReward(reward)" 
                                                class="w-full flex items-center gap-4 p-4 rounded-2xl border border-gray-100 hover:border-green-500 hover:bg-green-50/30 transition-all text-left group">
                                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-black text-gray-900" x-text="reward.reward.nama_reward"></p>
                                                <p class="text-xs font-bold text-green-600">Potongan Rp<span x-text="formatCurrency(reward.reward.diskon)"></span></p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[9px] text-gray-400 font-bold uppercase mb-0.5">Berlaku s/d</p>
                                                <p class="text-[10px] font-black text-gray-600" x-text="new Date(reward.batas_berlaku).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})"></p>
                                            </div>
                                        </button>
                                    </template>
                                    <template x-if="availableRewards.filter(r => subtotal >= r.reward.minimal_pembelian).length === 0">
                                        <p class="text-center py-4 text-xs text-gray-400 font-medium italic">Tidak ada reward yang memenuhi syarat minimal belanja</p>
                                    </template>
                                </div>
                            </div>

                            {{-- Reward Belum Memenuhi Syarat --}}
                            <div x-show="availableRewards.filter(r => subtotal < r.reward.minimal_pembelian).length > 0">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Belum Memenuhi Syarat</h4>
                                <div class="space-y-3">
                                    <template x-for="reward in availableRewards.filter(r => subtotal < r.reward.minimal_pembelian)" :key="reward.id">
                                        <div class="w-full flex items-center gap-4 p-4 rounded-2xl border border-gray-100 bg-gray-50/50 opacity-60 filter grayscale text-left">
                                            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-black text-gray-900" x-text="reward.reward.nama_reward"></p>
                                                <p class="text-xs font-bold text-red-500">Min. belanja Rp<span x-text="formatCurrency(reward.reward.minimal_pembelian)"></span></p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[9px] font-black text-red-400 uppercase">Kurang Rp<span x-text="formatCurrency(reward.reward.minimal_pembelian - subtotal)"></span></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 bg-gray-50 border-t border-gray-100">
                            <button type="button" @click="showRewardModal = false" class="w-full py-4 bg-white border border-gray-200 text-gray-600 font-bold rounded-2xl hover:bg-gray-100 transition shadow-sm">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Utama (Kiri) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Detail Produk Card --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-200 flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center border border-green-100 text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Detail Pesanan</h3>
                            <p class="text-gray-400 text-xs font-medium">Informasi produk yang akan dipesan</p>
                        </div>
                    </div>

                    <div class="p-8 space-y-8">
                        @foreach($items as $index => $item)
                            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 pb-8 @if(!$loop->last) border-b border-gray-100 @endif">
                                <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product->id }}">
                                @if(isset($item->cart_id))
                                    <input type="hidden" name="items[{{ $index }}][cart_id]" value="{{ $item->cart_id }}">
                                @endif
                                
                                @php
                                    $productFoto = $item->product->foto_produk;
                                    if (is_array($productFoto)) {
                                        $productFoto = $productFoto[0] ?? null;
                                    } elseif (is_string($productFoto)) {
                                        $decodedFoto = json_decode($productFoto, true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decodedFoto)) {
                                            $productFoto = $decodedFoto[0] ?? $productFoto;
                                        }
                                    }
                                    $itemPhotoUrl = $productFoto ? asset('uploads/produk/' . $productFoto) : 'https://ui-avatars.com/api/?name=' . urlencode($item->product->nama_produk);
                                @endphp

                                <div class="flex gap-4 items-start">
                                    <img src="{{ $itemPhotoUrl }}" alt="{{ $item->product->nama_produk }}" 
                                         class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-2xl border border-gray-100 shrink-0">
                                    
                                    <div class="flex-1 min-w-0 sm:hidden">
                                        <h4 class="text-sm font-black text-gray-900 leading-tight line-clamp-2">{{ $item->product->nama_produk }}</h4>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mt-1">{{ $item->product->kategori }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="hidden sm:flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-lg font-black text-gray-900 leading-tight">{{ $item->product->nama_produk }}</h4>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">{{ $item->product->kategori }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[9px] text-gray-400 uppercase font-black leading-none mb-1">Subtotal</p>
                                            <p class="text-lg font-black text-green-600 leading-none">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-center justify-between gap-4 bg-gray-50/50 p-3 rounded-2xl border border-gray-100">
                                        <div class="flex gap-6">
                                            <div class="space-y-0.5">
                                                <p class="text-[9px] text-gray-400 font-bold uppercase">Harga</p>
                                                <p class="text-xs font-black text-gray-700 leading-none">Rp{{ number_format($item->product->harga, 0, ',', '.') }}</p>
                                            </div>
                                            <div class="sm:hidden space-y-0.5">
                                                <p class="text-[9px] text-gray-400 font-bold uppercase text-green-600/70">Subtotal</p>
                                                <p class="text-sm font-black text-green-600 leading-none">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        
                                        @if($mode === 'buy_now')
                                            <div class="flex items-center bg-white border border-gray-200 rounded-xl p-1 shadow-sm">
                                                <button type="button" onclick="let qty = document.getElementById('item-qty'); qty.value = Math.max(1, parseInt(qty.value) - 1); updateTotal({{ $item->product->harga }}, {{ $item->product->berat }});"
                                                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-green-600 rounded-lg transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                                </button>
                                                <input type="number" name="items[{{ $index }}][jumlah]" id="item-qty" value="{{ $item->qty }}" min="1" max="{{ $item->product->jumlah_stok }}"
                                                       class="w-10 text-center bg-transparent border-none focus:ring-0 text-sm font-black text-gray-900 px-0"
                                                       oninput="updateTotal({{ $item->product->harga }}, {{ $item->product->berat }})">
                                                <button type="button" onclick="let qty = document.getElementById('item-qty'); qty.value = Math.min({{ $item->product->jumlah_stok }}, parseInt(qty.value) + 1); updateTotal({{ $item->product->harga }}, {{ $item->product->berat }});"
                                                        class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-green-600 rounded-lg transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                                </button>
                                            </div>
                                        @else
                                            <input type="hidden" name="items[{{ $index }}][jumlah]" value="{{ $item->qty }}">
                                            <div class="bg-white px-3 py-1.5 rounded-lg border border-gray-100 shadow-sm">
                                                <p class="text-xs font-black text-gray-900">{{ $item->qty }} {{ $item->product->unit }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Catatan per Produk --}}
                                    <div class="mt-4">
                                        <label class="block text-[9px] text-gray-400 uppercase font-black mb-1.5 flex items-center gap-1.5 ml-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Catatan untuk produk ini
                                        </label>
                                        <textarea name="items[{{ $index }}][catatan]" 
                                                  rows="1" 
                                                  placeholder="Contoh: Titip di satpam, atau warna cadangan..."
                                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-green-500/20 focus:border-green-500 focus:outline-none transition text-xs placeholder-gray-300"></textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if($isSayuran)
                            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6 flex items-start gap-4">
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-amber-600 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-amber-900 mb-1">Pengiriman Sayuran Terbatas</h4>
                                    <p class="text-xs text-amber-700 leading-relaxed">
                                        Produk sayuran hanya dapat dikirim ke Kecamatan Sumbersari, Patrang, dan Kaliwates, Kabupaten Jember, Jawa Timur.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Reward Selection Card --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-200 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center border border-amber-100 text-amber-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Reward & Voucher</h3>
                                <p class="text-gray-400 text-xs font-medium">Gunakan reward untuk mendapatkan potongan harga</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <input type="hidden" name="id_penukaran_reward" :value="rewardId">
                        
                        <template x-if="!rewardId">
                            <button type="button" @click="showRewardModal = true" 
                                    class="w-full flex items-center justify-between p-4 bg-gray-50 border border-dashed border-gray-200 rounded-2xl hover:bg-amber-50/50 hover:border-amber-200 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-gray-400 group-hover:text-amber-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-500 group-hover:text-amber-700">Pilih Reward Pelanggan</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </template>

                        <template x-if="rewardId">
                            <div class="flex items-center justify-between p-4 bg-amber-50 border border-amber-200 rounded-2xl">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-amber-600 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-amber-900" x-text="rewardName"></h4>
                                        <p class="text-xs font-bold text-amber-600">Potongan Rp<span x-text="formatCurrency(rewardDiscount)"></span></p>
                                    </div>
                                </div>
                                <button type="button" @click="removeReward()" class="p-2 text-amber-400 hover:text-red-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Informasi Pengiriman Card --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100">
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

                        {{-- Lokasi: Provinsi, Kota, Kecamatan --}}
                        @if($isSayuran)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Provinsi</label>
                                    <input type="hidden" name="province_id" value="{{ $sayuranProvince->id ?? '' }}">
                                    <input type="text" value="{{ $sayuranProvince->name ?? 'Jawa Timur' }}" readonly
                                           class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kota / Kabupaten</label>
                                    <input type="hidden" name="city_id" value="{{ $sayuranCity->id ?? '' }}">
                                    <input type="text" value="{{ $sayuranCity->name ?? 'Kabupaten Jember' }}" readonly
                                           class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 cursor-not-allowed">
                                </div>
                                <div class="relative" x-data="{
                                    open: false,
                                    search: '',
                                    selected: '',
                                    selectedName: '',
                                    options: {{ json_encode($kecamatans->map(fn($k) => ['id' => $k->id, 'name' => $k->name])) }},
                                    get filteredOptions() {
                                        if (!this.search) return this.options;
                                        return this.options.filter(o => o.name.toLowerCase().includes(this.search.toLowerCase()));
                                    }
                                }">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kecamatan</label>
                                    <input type="hidden" name="kecamatan_id" :value="selected">
                                    <button type="button" @click="open = !open; if(open) $nextTick(() => $refs.kecSearch.focus())" 
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 transition text-sm font-semibold text-left flex items-center justify-between">
                                        <span :class="selectedName ? 'text-gray-800' : 'text-gray-400'" x-text="selectedName || 'Pilih Kecamatan'"></span>
                                        <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="open" x-cloak @click.away="open = false" 
                                         class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden">
                                        <div class="p-2 border-b border-gray-50 bg-gray-50/50">
                                            <input type="text" x-model="search" x-ref="kecSearch" placeholder="Cari kecamatan..."
                                                   class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none placeholder-gray-300 font-medium">
                                        </div>
                                        <div class="max-h-52 overflow-y-auto">
                                            <template x-if="filteredOptions.length === 0">
                                                <p class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ditemukan</p>
                                            </template>
                                            <template x-for="opt in filteredOptions" :key="opt.id">
                                                <button type="button" @click="selected = opt.id; selectedName = opt.name; open = false; fetchOngkirAjax(opt.id, getCurrentWeight(), getCurrentSubtotal(), '{{ rtrim((string)parse_url(url('/'), PHP_URL_PATH), '/') }}');"
                                                        class="w-full text-left px-4 py-3 text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-600 transition" x-text="opt.name"></button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div x-data="checkoutDropdown(false, {{ json_encode($provinces) }}, '{{ rtrim((string)parse_url(url('/'), PHP_URL_PATH), '/') }}')" x-init="init()" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {{-- Provinsi --}}
                                <div class="relative" x-data="{ open: false }">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Provinsi</label>
                                    <input type="hidden" name="province_id" :value="selectedProvince">
                                    <button type="button" @click="open = !open; if(open) $nextTick(() => $refs.provSearch.focus())" 
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-left flex items-center justify-between gap-2">
                                        <span :class="selectedProvinceName ? 'text-gray-800' : 'text-gray-400'" x-text="selectedProvinceName || 'Pilih Provinsi'"></span>
                                        <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
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
                                    <button type="button" @click="if(selectedProvince) { open = !open; if(open) $nextTick(() => $refs.citySearch.focus()) }" :disabled="!selectedProvince"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-left flex items-center justify-between gap-2" :class="{'opacity-50 cursor-not-allowed': !selectedProvince}">
                                        <span :class="selectedCityName ? 'text-gray-800' : 'text-gray-400'" x-text="loadingCities ? 'Memuat...' : (selectedCityName || 'Pilih Kota')"></span>
                                        <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
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
                            <span class="font-bold text-gray-900" x-text="'Rp' + formatCurrency(subtotal)"></span>
                        </div>
                        <template x-if="rewardDiscount > 0">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Reward</span>
                                <span class="font-bold text-amber-600" x-text="'- Rp' + formatCurrency(rewardDiscount)"></span>
                            </div>
                        </template>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ongkir</span>
                            <span class="font-bold text-green-600" x-text="ongkirText"></span>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="font-bold text-gray-900 block">Total Pembayaran</span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Mendapat <span x-text="Math.max(0, Math.floor((subtotal - rewardDiscount) / 10000))"></span> Poin</span>
                            </div>
                            <span class="text-xl font-black text-green-600" x-text="'Rp' + formatCurrency(finalTotal)"></span>
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
                                'name' => 'Cash on Delivery',
                                'desc' => 'Bayar saat barang diterima',
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
                                                Hanya tersedia untuk Jember (Sumbersari, Patrang, Kaliwates)
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
                    Buat Pesanan
                </button>
            </div>

        </form>
    </div>
</div>

@php
    $checkoutProdukConfig = [
        'mode' => $mode ?? 'cart',
        'grandTotal' => $grandTotal,
        'totalWeight' => $totalWeight,
        'basePath' => rtrim((string) parse_url(url('/'), PHP_URL_PATH), '/'),
        'availableRewards' => $availableRewards,
    ];
    if (($mode ?? '') === 'buy_now' && isset($items) && $items->count() > 0) {
        $checkoutProdukConfig['productPrice'] = $items->first()->product->harga;
        $checkoutProdukConfig['productWeight'] = $items->first()->product->berat;
    }
@endphp
<div id="checkout-produk-config" class="hidden" data-config="{{ json_encode($checkoutProdukConfig) }}"></div>

<!--
<script>
    // Format currency to Indonesian Rupiah format (Rp1.234.567)
    function formatCurrency(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function getCurrentWeight() {
        @if(isset($mode) && $mode === 'buy_now' && isset($items) && $items->count() > 0)
            const qtyInput = document.getElementById('item-qty');
            const qty = parseInt(qtyInput?.value) || 1;
            return {{ $items->first()->product->berat }} * qty;
        @else
            return {{ $totalWeight }};
        @endif
    }

    function getCurrentSubtotal() {
        @if(isset($mode) && $mode === 'buy_now' && isset($items) && $items->count() > 0)
            const qtyInput = document.getElementById('item-qty');
            const qty = parseInt(qtyInput?.value) || 1;
            return {{ $items->first()->product->harga }} * qty;
        @else
            return {{ $grandTotal }};
        @endif
    }

    function updateTotal(productPrice, productWeight) {
        @if(isset($mode) && $mode === 'buy_now')
            const qtyInput = document.getElementById('item-qty');
            if (!qtyInput) return;

            const qty = parseInt(qtyInput.value) || 1;
            const subtotal = productPrice * qty;
            const totalWeight = (productWeight || 0) * qty;
            
            // Get Alpine component data
            const summary = document.querySelector('[x-data*="checkoutSummary"]')._x_dataStack[0];
            summary.updateSubtotal(subtotal);
            
            // Recalculate ongkir if kecamatan is already selected
            const kecamatanInput = document.querySelector('input[name="kecamatan_id"]');
            const kecamatanId = kecamatanInput ? kecamatanInput.value : null;
            
            if (kecamatanId) {
                const basePath = '{{ rtrim((string)parse_url(url('/'), PHP_URL_PATH), '/') }}';
                fetchOngkirAjax(kecamatanId, totalWeight, subtotal, basePath);
            }
        @endif
    }

    async function fetchOngkirAjax(kecamatanId, totalWeight, grandTotal, basePath) {
        // Get Alpine component data
        const summary = document.querySelector('[x-data*="checkoutSummary"]')._x_dataStack[0];
        
        summary.updateOngkir(0, 'Menghitung...');

        try {
            const response = await fetch(`${basePath}/cek-ongkir?kecamatan_id=${kecamatanId}&total_weight=${totalWeight}`);
            const data = await response.json();

            if (data.success) {
                summary.updateOngkir(data.ongkir, data.ongkir === 0 ? 'Gratis' : data.formatted_ongkir);
            } else {
                summary.updateOngkir(0, 'Gagal memuat');
            }
        } catch (error) {
            console.error('Error fetching ongkir:', error);
            summary.updateOngkir(0, 'Gagal memuat');
        }
    }

    // Alpine.js component for checkout summary and rewards
    function checkoutSummary(initialSubtotal) {
        return {
            subtotal: initialSubtotal,
            ongkir: 0,
            ongkirText: 'Pilih Lokasi',
            rewardId: '',
            rewardDiscount: 0,
            rewardName: '',
            showRewardModal: false,
            availableRewards: @json($availableRewards),

            get finalTotal() {
                return Math.max(0, this.subtotal - this.rewardDiscount + this.ongkir);
            },

            formatCurrency(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            },

            selectReward(redemption) {
                this.rewardId = redemption.id;
                this.rewardDiscount = redemption.reward.diskon;
                this.rewardName = redemption.reward.nama_reward;
                this.showRewardModal = false;
            },

            removeReward() {
                this.rewardId = '';
                this.rewardDiscount = 0;
                this.rewardName = '';
            },

            updateOngkir(amount, formatted) {
                this.ongkir = amount;
                this.ongkirText = formatted;
            },

            updateSubtotal(newSubtotal) {
                this.subtotal = newSubtotal;
                // Check if currently selected reward is still valid
                if (this.rewardId) {
                    const selected = this.availableRewards.find(r => r.id == this.rewardId);
                    if (selected && this.subtotal < selected.reward.minimal_pembelian) {
                        this.removeReward();
                    }
                }
            }
        }
    }

    // Alpine.js component for payment method
    function paymentMethod() {
        return {
            selectedMethod: 'bca', // Default to BCA
            isCodDisabled: true,

            init() {
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
                            if (data.selectedName) {
                                kecamatanName = data.selectedName || '';
                                break;
                            }
                        }
                    }
                }

                // Check if location is valid for COD
                const isJawatimur = provinceName.toLowerCase().includes('jawa timur');
                const isJember = cityName.toLowerCase().includes('jember');
                const allowedKecamatan = ['Sumbersari', 'Patrang', 'Kaliwates'];
                const isAllowedKecamatan = allowedKecamatan.some(k => kecamatanName.toLowerCase().includes(k.toLowerCase()));

                this.isCodDisabled = !(isJawatimur && isJember && isAllowedKecamatan && kecamatanName);

                // If COD becomes disabled and it was selected, switch to BCA
                if (this.isCodDisabled && this.selectedMethod === 'cod') {
                    this.selectedMethod = 'bca';
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
                
                // Fetch Ongkir
                fetchOngkirAjax(kec.id, getCurrentWeight(), getCurrentSubtotal(), basePath);
            },

            notifyLocationChange() {
                setTimeout(() => {
                    const paymentDiv = document.querySelector('[x-data*="paymentMethod"]');
                    if (paymentDiv && paymentDiv._x_dataStack && paymentDiv._x_dataStack[0]) {
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
                if (!this.selectedProvince) return;
                this.loadingCities = true;
                try {
                    const apiPath = basePath ? `${basePath}/api/cities-transaction/${this.selectedProvince}` : `/api/cities-transaction/${this.selectedProvince}`;
                    const response = await fetch(apiPath);
                    this.cities = await response.json();
                } catch (error) {
                    console.error('Error fetching cities:', error);
                } finally {
                    this.loadingCities = false;
                }
            },

            async onCityChange() {
                if (!this.selectedCity) return;
                this.loadingKecamatan = true;
                try {
                    const apiPath = basePath ? `${basePath}/api/districts-transaction/${this.selectedCity}` : `/api/districts-transaction/${this.selectedCity}`;
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
        @if(isset($mode) && $mode === 'buy_now' && isset($product))
            updateTotal({{ $product->harga }}, {{ $product->berat }});
        @endif
    });
</script>
-->

@endsection
