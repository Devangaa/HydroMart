@extends('layouts.app')

{{-- ============================================================================= --}}
{{-- FILE: admin/transaksi/show.blade.php --}}
{{-- HALAMAN: Detail Transaksi Admin --}}
{{-- DESKRIPSI: Detail transaksi untuk verifikasi, update status, dan kelola pesanan. --}}
{{-- ============================================================================= --}}

@section('title', 'Detail Transaksi #' . $transaksi->order_id)

@section('content')
{{-- Bagian: Kontainer Halaman --}}
<div class="w-full min-h-screen bg-gray-50/50 pb-20" x-data="{ 
    showCancelModal: false, 
    showResiModal: {{ $errors->hasAny(['nomor_resi', 'ekspedisi']) ? 'true' : 'false' }},
    showReplyModal: false,
    isSubmittingReply: false,
    replyTarget: { id: null, name: '', comment: '' },
    replyConfirmModal: false
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8" data-aos="fade-right">
            <div>
                <a href="{{ route('admin.transaksi.index') }}" class="text-sm font-bold text-gray-400 hover:text-green-600 transition-colors flex items-center gap-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar Transaksi
                </a>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Detail Transaksi <span class="text-green-600">Admin</span></h1>
                <p class="text-gray-500 text-sm mt-2 font-medium">Order ID: <span class="text-gray-900 font-bold">{{ $transaksi->order_id }}</span></p>
            </div>
            <div>
                <span class="inline-block px-4 py-1.5 text-xs font-bold rounded-xl uppercase tracking-wider
                    @if($transaksi->status == 'Menunggu Pembayaran') bg-yellow-100 text-yellow-700
                    @elseif($transaksi->status == 'Menunggu Konfirmasi') bg-yellow-100 text-yellow-700
                    @elseif($transaksi->status == 'Diproses') bg-blue-100 text-blue-700
                    @elseif($transaksi->status == 'Dikirim') bg-purple-100 text-purple-700
                    @elseif($transaksi->status == 'Selesai') bg-green-100 text-green-700
                    @elseif($transaksi->status == 'Dibatalkan') bg-red-100 text-red-700
                    @else bg-gray-100 text-gray-700 @endif">
                    {{ $transaksi->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
            {{-- Kolom Kiri --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Action Panel Admin --}}
                @if(!in_array($transaksi->status, ['Selesai', 'Dibatalkan']))
                <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] shadow-sm border border-gray-100 p-5 md:p-8" data-aos="fade-up">
                    <h3 class="text-base md:text-lg font-black text-gray-900 mb-4">Aksi Admin</h3>
                    
                    <div class="flex flex-wrap gap-4">
                        {{-- Button for Menunggu Konfirmasi (COD) --}}
                        @if($transaksi->status == 'Menunggu Konfirmasi' && $transaksi->metode_pembayaran == 'cod')
                            <form action="{{ route('admin.transaksi.status', $transaksi->order_id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="Diproses">
                                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                                    Konfirmasi & Proses Pesanan
                                </button>
                            </form>
                        @endif

                        {{-- Button for Diproses --}}
                        @if($transaksi->status == 'Diproses')
                            {{-- Manual Send for local areas or COD --}}
                            @if($isLocal || $transaksi->metode_pembayaran == 'cod')
                                <form action="{{ route('admin.transaksi.status', $transaksi->order_id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="Dikirim">
                                    <button type="submit" class="px-6 py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition shadow-lg shadow-purple-100">
                                        Kirim Pesanan (Manual)
                                    </button>
                                </form>
                            @endif

                            {{-- Resi Input for all --}}
                            <button @click="showResiModal = true" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                                Input Nomor Resi
                            </button>
                        @endif

                        {{-- Button for Dikirim --}}
                        @if($transaksi->status == 'Dikirim')
                            <form action="{{ route('admin.transaksi.status', $transaksi->order_id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="Selesai">
                                <button type="submit" class="px-6 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-100">
                                    Selesaikan Pesanan
                                </button>
                            </form>
                        @endif

                        {{-- Cancel Button (available if not cancelled/finished/shipped/processed) --}}
                        @if(!in_array($transaksi->status, ['Selesai', 'Dibatalkan', 'Dikirim', 'Diproses']))
                            <button @click="showCancelModal = true" class="px-6 py-3 border-2 border-red-100 text-red-600 font-bold rounded-xl hover:bg-red-50 transition">
                                Batalkan Pesanan
                            </button>
                        @endif
                    </div>

                    @if($transaksi->status == 'Menunggu Pembayaran' && $transaksi->metode_pembayaran != 'cod')
                        <p class="text-xs text-amber-600 mt-4 font-medium flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Menunggu konfirmasi pembayaran otomatis dari sistem (Midtrans).
                        </p>
                    @endif
                </div>
                @endif

                {{-- Daftar Produk --}}
                <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
                    <div class="p-5 md:p-8 border-b border-gray-50 bg-gray-50/30">
                        <h3 class="font-black text-gray-900 flex items-center gap-2 text-sm md:text-base">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Daftar Produk
                        </h3>
                    </div>
                    <div class="p-5 md:p-8 space-y-6">
                        @foreach($transaksi->detailProduks as $detail)
                            @php
                                $fotoProduk = $detail->produk->foto_produk;
                                if (is_array($fotoProduk)) {
                                    $fotoProduk = $fotoProduk[0] ?? null;
                                } elseif (is_string($fotoProduk)) {
                                    $decodedFoto = json_decode($fotoProduk, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedFoto)) {
                                        $fotoProduk = $decodedFoto[0] ?? $fotoProduk;
                                    }
                                }
                                $photoUrl = $fotoProduk ? asset('uploads/produk/' . $fotoProduk) : 'https://ui-avatars.com/api/?name=' . urlencode($detail->produk->nama_produk);
                            @endphp
                            <div class="flex items-center gap-4 md:gap-6">
                                <img src="{{ $photoUrl }}" alt="{{ $detail->produk->nama_produk }}" class="w-14 h-14 md:w-20 md:h-20 object-cover rounded-xl md:rounded-2xl border border-gray-100 shadow-sm shrink-0">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm md:text-base font-bold text-gray-900 truncate">{{ $detail->produk->nama_produk }}</h4>
                                    <p class="text-xs md:text-sm text-gray-500 mt-0.5">{{ $detail->jumlah }} {{ $detail->produk->unit ?? 'pcs' }} x Rp{{ number_format($detail->produk->harga, 0, ',', '.') }}</p>
                                    @if($detail->catatan)
                                        <p class="text-[10px] md:text-xs text-amber-600 mt-1 italic font-medium">"{{ $detail->catatan }}"</p>
                                    @endif
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-xs md:text-sm font-black text-gray-900">Rp{{ number_format($detail->total_harga, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            {{-- Review Section --}}
                            @if($detail->ulasan)
                            <div class="mt-4 ml-14 md:ml-26 bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="flex text-amber-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $i <= $detail->ulasan->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $detail->ulasan->tanggal_ulasan->format('d M Y') }}</span>
                                    </div>
                                    @if(!$detail->ulasan->balasan)
                                        <button @click="showReplyModal = true; replyTarget = { id: {{ $detail->ulasan->id }}, name: '{{ addslashes($detail->produk->nama_produk) }}', comment: '{{ addslashes($detail->ulasan->komentar) }}' }" 
                                            class="text-[10px] font-bold text-green-600 hover:text-green-700 uppercase tracking-wider">
                                            Beri Balasan
                                        </button>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-700 leading-relaxed font-medium">{{ $detail->ulasan->komentar }}</p>
                                
                                @if($detail->ulasan->balasan)
                                <div class="mt-3 pt-3 border-t border-gray-200/50">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] font-black text-green-600 uppercase">Balasan Admin</span>
                                        <span class="text-[10px] text-gray-400 font-medium">{{ $detail->ulasan->tanggal_balasan->format('d M Y') }}</span>
                                    </div>
                                    <p class="text-xs text-gray-600">{{ $detail->ulasan->balasan }}</p>
                                </div>
                                @endif
                            </div>
                            @endif
                        @endforeach

                        @foreach($transaksi->detailLayanans as $detail)
                            @php
                                $fotoLayanan = $detail->layanan->foto_layanan;
                                if (is_array($fotoLayanan)) {
                                    $fotoLayanan = $fotoLayanan[0] ?? null;
                                } elseif (is_string($fotoLayanan)) {
                                    $decodedFoto = json_decode($fotoLayanan, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedFoto)) {
                                        $fotoLayanan = $decodedFoto[0] ?? $fotoLayanan;
                                    }
                                }
                                $photoUrl = $fotoLayanan ? asset('uploads/layanan/' . $fotoLayanan) : 'https://ui-avatars.com/api/?name=' . urlencode($detail->layanan->nama_layanan);
                            @endphp
                            <div class="flex items-center gap-4 md:gap-6">
                                <img src="{{ $photoUrl }}" alt="{{ $detail->layanan->nama_layanan }}" class="w-14 h-14 md:w-20 md:h-20 object-cover rounded-xl md:rounded-2xl border border-gray-100 shadow-sm shrink-0">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm md:text-base font-bold text-gray-900 truncate">{{ $detail->layanan->nama_layanan }}</h4>
                                    <p class="text-xs md:text-sm text-gray-500 mt-0.5">Layanan x Rp{{ number_format($detail->layanan->harga, 0, ',', '.') }}</p>
                                    @if($detail->catatan)
                                        <p class="text-[10px] md:text-xs text-amber-600 mt-1 italic font-medium">"{{ $detail->catatan }}"</p>
                                    @endif
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-xs md:text-sm font-black text-gray-900">Rp{{ number_format($detail->total_harga, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            {{-- Review Section --}}
                            @if($detail->ulasan)
                            <div class="mt-4 ml-14 md:ml-26 bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="flex text-amber-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $i <= $detail->ulasan->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $detail->ulasan->tanggal_ulasan->format('d M Y') }}</span>
                                    </div>
                                    @if(!$detail->ulasan->balasan)
                                        <button @click="showReplyModal = true; replyTarget = { id: {{ $detail->ulasan->id }}, name: '{{ addslashes($detail->layanan->nama_layanan) }}', comment: '{{ addslashes($detail->ulasan->komentar) }}' }" 
                                            class="text-[10px] font-bold text-green-600 hover:text-green-700 uppercase tracking-wider">
                                            Beri Balasan
                                        </button>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-700 leading-relaxed font-medium">"{{ $detail->ulasan->komentar }}"</p>
                                
                                @if($detail->ulasan->balasan)
                                <div class="mt-3 pt-3 border-t border-gray-200/50">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] font-black text-green-600 uppercase">Balasan Admin</span>
                                        <span class="text-[10px] text-gray-400 font-medium">{{ $detail->ulasan->tanggal_balasan->format('d M Y') }}</span>
                                    </div>
                                    <p class="text-xs text-gray-600">"{{ $detail->ulasan->balasan }}"</p>
                                </div>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Informasi Pengiriman --}}
                <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
                    <div class="p-5 md:p-8 border-b border-gray-50 bg-gray-50/30">
                        <h3 class="font-black text-gray-900 flex items-center gap-2 text-sm md:text-base">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            Informasi Pengiriman
                        </h3>
                    </div>
                    <div class="p-5 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 md:mb-2">Penerima</p>
                            <p class="text-sm md:text-base font-bold text-gray-900">{{ $transaksi->nama_penerima }}</p>
                            <p class="text-xs md:text-sm text-gray-500">{{ $transaksi->no_hp }}</p>
                            <p class="text-xs text-gray-400 mt-1">Username: {{ $transaksi->user->username }} ({{ $transaksi->user->email }})</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 md:mb-2">Alamat</p>
                            <p class="text-xs md:text-sm text-gray-900 leading-relaxed">
                                {{ $transaksi->alamat_pengiriman }}<br>
                                {{ $transaksi->kecamatan->name }}, {{ $transaksi->kecamatan->city->name }}<br>
                                {{ $transaksi->kecamatan->city->province->name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 md:mb-2">Ekspedisi</p>
                            <p class="text-xs md:text-sm font-bold text-gray-900">{{ $transaksi->ekspedisi ?? '-' }}</p>
                            @if($transaksi->nomor_resi)
                                <p class="text-[10px] text-green-600 font-bold mt-1 uppercase">Resi: {{ $transaksi->nomor_resi }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Lacak Pesanan --}}
                @if($trackingData && isset($trackingData['data']))
                <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
                    <div class="p-5 md:p-8 border-b border-gray-50 bg-gray-50/30">
                        <h3 class="font-black text-gray-900 flex items-center gap-2 text-sm md:text-base">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Lacak Pesanan
                        </h3>
                    </div>
                    <div class="p-5 md:p-8">
                        <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:ml-[1.35rem] md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-100 before:to-transparent">
                            @foreach($trackingData['data']['history'] as $index => $history)
                            <div class="relative flex items-center justify-between md:justify-start group {{ $index === 0 ? 'is-active' : '' }}">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white {{ $index === 0 ? 'bg-green-500 shadow-lg shadow-green-200' : 'bg-gray-200' }} text-white shadow shrink-0 md:order-1 transition-all duration-300 z-10">
                                    @if($index === 0)
                                        <svg class="fill-current w-3 h-3" viewBox="0 0 12 12">
                                            <path d="M12 2.4L4.8 9.6 0 4.8l1.2-1.2 3.6 3.6L10.8 1.2z" />
                                        </svg>
                                    @else
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    @endif
                                </div>
                                <div class="w-[calc(100%-4rem)] md:w-full ml-4 md:ml-8 p-4 rounded-2xl {{ $index === 0 ? 'bg-green-50 border border-green-100 shadow-sm' : 'bg-gray-50 border border-gray-100' }} transition-all duration-300 hover:shadow-md">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-1 mb-1">
                                        <div class="font-bold text-gray-900 text-xs md:text-sm leading-tight">{{ $history['description'] ?? $history['desc'] ?? '-' }}</div>
                                        <time class="font-bold {{ $index === 0 ? 'text-green-600' : 'text-gray-400' }} text-[10px] md:text-xs whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($history['date'] ?? $history['time'] ?? now())->format('d M Y, H:i') }}
                                        </time>
                                    </div>
                                    <div class="text-gray-500 text-[10px] md:text-xs font-medium flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                        {{ $history['location'] ?? 'Lokasi tidak spesifik' }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Kolom Kanan --}}
            <div class="space-y-6">
                {{-- Ringkasan Pembayaran --}}
                <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-left">
                    <div class="p-5 md:p-6 border-b border-gray-50 bg-gray-50/30">
                        <h3 class="font-black text-gray-900 text-xs md:text-sm">Ringkasan Pembayaran</h3>
                    </div>
                    <div class="p-5 md:p-6 space-y-4">
                        <div class="flex justify-between text-xs md:text-sm">
                            <span class="text-gray-500">Metode</span>
                            <span class="font-bold text-gray-900 uppercase">
                                @if($transaksi->metode_pembayaran == 'bca') BCA Virtual Account
                                @elseif($transaksi->metode_pembayaran == 'mandiri') Mandiri Bill
                                @elseif($transaksi->metode_pembayaran == 'qris') QRIS
                                @elseif($transaksi->metode_pembayaran == 'cod') Cash On Delivery
                                @else {{ strtoupper($transaksi->metode_pembayaran) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between text-xs md:text-sm">
                            <span class="text-gray-500">Total Produk/Layanan</span>
                            <span class="font-bold text-gray-900">Rp{{ number_format($transaksi->detailProduks->sum('total_harga') + $transaksi->detailLayanans->sum('total_harga'), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs md:text-sm">
                            <span class="text-gray-500">Biaya Pengiriman</span>
                            <span class="font-bold text-green-600">Rp{{ number_format($transaksi->ongkir, 0, ',', '.') }}</span>
                        </div>
                        <hr class="border-gray-50">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-900 text-sm">Total Bayar</span>
                            <span class="text-lg md:text-xl font-black text-green-600">Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Info Tambahan --}}
                <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] shadow-sm border border-gray-100 p-5 md:p-6" data-aos="fade-left" data-aos-delay="100">
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tanggal Transaksi</p>
                            <p class="text-xs md:text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->locale('id')->translatedFormat('d F Y, H:i') }} WIB</p>
                        </div>
                        @if($transaksi->batas_pembayaran && $transaksi->status == 'Menunggu Pembayaran')
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Batas Pembayaran</p>
                            <p class="text-xs md:text-sm font-bold text-red-600">{{ \Carbon\Carbon::parse($transaksi->batas_pembayaran)->locale('id')->translatedFormat('d F Y, H:i') }} WIB</p>
                        </div>
                        @endif
                        @if($transaksi->status == 'Selesai')
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Poin Pelanggan</p>
                            <p class="text-xs md:text-sm font-bold text-purple-600">+{{ $transaksi->poin }} Poin</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Bagian: Modal Terkait --}}
    @include('admin.transaksi.modal-resi')
    @include('admin.transaksi.modal-cancel')
    @include('admin.transaksi.modal-reply')
    @include('admin.transaksi.modal-reply-confirm')
</div>
@endsection
