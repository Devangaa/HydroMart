@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="w-full min-h-screen bg-gray-50/50 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumb --}}
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('landing') }}" class="text-sm font-bold text-gray-400 hover:text-green-600 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Beranda
                    </a>
                </li>
            </ol>
        </nav>

        <div data-aos="fade-right" class="mb-8">
            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">
                Pesanan Saya
            </span>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-4 tracking-tight">Daftar Transaksi</h1>
            <p class="text-gray-500 text-sm mt-2 font-medium">Pantau status pesanan dan lihat riwayat belanja Anda</p>
        </div>

        <div data-aos="fade-up">
        {{-- Tabs --}}
            <div class="flex gap-4 mb-6 border-b border-gray-200">
                <a href="{{ route('transaksi.history', ['tab' => 'aktif']) }}" 
                class="px-4 py-3 font-bold text-sm transition-colors border-b-2 {{ $tab == 'aktif' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-green-600' }}">
                    Sedang Berlangsung
                </a>
                <a href="{{ route('transaksi.history', ['tab' => 'riwayat']) }}" 
                class="px-4 py-3 font-bold text-sm transition-colors border-b-2 {{ $tab == 'riwayat' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-green-600' }}">
                    Riwayat
                </a>
            </div>

            <div class="space-y-6">
                @forelse($transaksis as $transaksi)
                    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                        {{-- Header Transaksi --}}
                        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50/50">
                            <div class="flex items-center gap-4">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 font-bold uppercase">Tanggal Belanja</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}</span>
                                </div>
                                <div class="w-px h-8 bg-gray-200 hidden md:block"></div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 font-bold uppercase">ID Order</span>
                                    <span class="text-sm font-semibold text-gray-900">#{{ $transaksi->id }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="px-4 py-1.5 text-xs font-bold rounded-xl uppercase tracking-wider
                                    @if($transaksi->status == 'Menunggu Pembayaran') bg-yellow-100 text-yellow-700
                                    @elseif($transaksi->status == 'Diproses') bg-blue-100 text-blue-700
                                    @elseif($transaksi->status == 'Dikirim') bg-purple-100 text-purple-700
                                    @elseif($transaksi->status == 'Selesai') bg-green-100 text-green-700
                                    @elseif($transaksi->status == 'Dibatalkan') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $transaksi->status }}
                                </span>
                            </div>
                        </div>

                        {{-- Body Transaksi (Daftar Produk) --}}
                        <div class="p-6 space-y-4">
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
                                <div class="flex items-start md:items-center gap-4 py-2">
                                    <img src="{{ $photoUrl }}" alt="{{ $detail->produk->nama_produk }}" class="w-16 h-16 object-cover rounded-2xl border border-gray-100 flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-base font-bold text-gray-900 truncate">{{ $detail->produk->nama_produk }}</h4>
                                        <p class="text-sm text-gray-500 mt-0.5">{{ $detail->jumlah }} {{ $detail->produk->unit ?? 'pcs' }} x Rp{{ number_format($detail->produk->harga, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-xs text-gray-400 font-bold uppercase mb-1">Subtotal</p>
                                        <p class="text-sm font-black text-gray-900">Rp{{ number_format($detail->total_harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Footer Transaksi --}}
                        <div class="p-6 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                            <div class="w-full md:w-auto">
                                <p class="text-xs text-gray-400 font-bold uppercase mb-1 text-center md:text-left">Total Biaya Transaksi</p>
                                <p class="text-xl font-black text-green-600 text-center md:text-left">Rp{{ number_format($transaksi->total_harga ?? $transaksi->detailProduks->sum('total_harga'), 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-3 w-full md:w-auto">
                                @if($transaksi->status == 'Menunggu Pembayaran')
                                    <form action="{{ route('transaksi.cancel', $transaksi->id) }}" method="POST" class="w-full md:w-auto">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')" class="w-full md:w-auto px-6 py-3 border-2 border-red-100 text-red-600 font-bold rounded-xl hover:bg-red-50 transition text-sm">
                                            Batalkan Pesanan
                                        </button>
                                    </form>
                                @endif
                                <button class="w-full md:w-auto px-6 py-3 bg-green-100 text-green-700 font-black rounded-xl hover:bg-green-200 transition text-sm text-center">
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-[2rem] border border-dashed border-gray-200">
                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-bold text-gray-900">Belum ada transaksi</h3>
                        <p class="mt-2 text-gray-500 font-medium text-sm">Anda belum memiliki {{ $tab == 'aktif' ? 'pesanan yang sedang berlangsung' : 'riwayat pesanan' }}.</p>
                        <div class="mt-6">
                            <a href="{{ route('produk.index') }}" class="inline-flex items-center px-6 py-3 shadow-lg shadow-green-100 font-black rounded-xl text-white bg-green-600 hover:bg-green-700 transition">
                                Mulai Belanja
                            </a>
                        </div>
                    </div>
                @endforelse

                <div class="mt-8">
                    {{ $transaksis->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection