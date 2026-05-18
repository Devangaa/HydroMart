@extends('layouts.app')

@section('title', $product->nama_produk)

@section('content')
<div class="w-full min-h-screen bg-gray-50/50 pb-20" 
     x-data="{ 
        {{-- Logika untuk menentukan foto default saat halaman dimuat --}}
        defaultAvatar: 'https://ui-avatars.com/api/?name={{ urlencode($product->nama_produk) }}',
        activePhoto: '{{ (is_array($product->foto_produk) && count($product->foto_produk) > 0) ? asset('uploads/produk/' . $product->foto_produk[0]) : '' }}',
        
        {{-- Inisialisasi activePhoto jika kosong --}}
        init() {
            if (!this.activePhoto) this.activePhoto = this.defaultAvatar;
        },
        quantity: 1, 
        maxStock: {{ $product->jumlah_stok ?? 0}},
        validateInput() {
            if (this.quantity === '' || isNaN(this.quantity)) this.quantity = 1;
            if (parseInt(this.quantity) > this.maxStock) this.quantity = this.maxStock;
            if (parseInt(this.quantity) < 1) this.quantity = 1;
        }
     }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            
            {{-- BAGIAN KIRI: FOTO & GALERI --}}
            <div class="space-y-6" data-aos="fade-right">
                <div class="bg-white rounded-[3rem] overflow-hidden aspect-square flex items-center justify-center relative shadow-sm border border-gray-100">
                    {{-- Foto Utama menggunakan x-bind (:src) --}}
                    <img :src="activePhoto" 
                         alt="{{ $product->nama_produk }}" 
                         class="w-full h-full object-cover transition-all duration-500">
                </div>
                
                {{-- Loop Galeri Foto --}}
                <div class="flex gap-4 overflow-x-auto pb-2">
                    @if(is_array($product->foto_produk) && count($product->foto_produk) > 0)
                        {{-- Tampilkan Foto-foto yang ada di database --}}
                        @foreach($product->foto_produk as $foto)
                        <div @click="activePhoto = '{{ asset('uploads/produk/'.$foto) }}'" 
                            class="w-20 h-20 flex-shrink-0 bg-white rounded-2xl overflow-hidden shadow-sm cursor-pointer border-2 transition-all"
                            :class="activePhoto.includes('{{ $foto }}') ? 'border-green-500 scale-95' : 'border-transparent hover:border-gray-200'">
                            <img src="{{ asset('uploads/produk/'.$foto) }}" class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    @else
                        {{-- JIKA TIDAK ADA FOTO: Tampilkan satu thumbnail placeholder --}}
                        <div @click="activePhoto = defaultAvatar" 
                            class="w-20 h-20 flex-shrink-0 bg-white rounded-2xl overflow-hidden shadow-sm cursor-pointer border-2 transition-all border-green-500 scale-95">
                            <img :src="defaultAvatar" class="w-full h-full object-cover">
                        </div>
                    @endif
                </div>
            </div>

            {{-- BAGIAN KANAN: DETAIL PRODUK --}}
            <div class="space-y-8" data-aos="fade-left">
                <div>
                    <span class="text-green-600 text-xs font-black uppercase tracking-widest">{{ $product->kategori }}</span>
                    <h1 class="text-3xl font-black text-gray-900 mt-2 tracking-tight">{{ $product->nama_produk }}</h1>
                    
                    <div class="flex items-center gap-4 mt-4">
                        @if($product->total_ulasan > 0)
                        <div class="flex items-center gap-1 text-yellow-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            <span class="text-gray-900 font-black text-sm ml-1">{{ number_format($product->average_rating, 1) }}</span>
                        </div>
                        <span class="text-gray-400 text-sm font-bold">({{ $product->total_ulasan }} ulasan)</span>
                        <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                        @endif
                        <span class="text-green-600 text-sm font-black">Stok: {{ $product->jumlah_stok }} {{ $product->unit }}</span>
                    </div>
                </div>

                <div class="bg-green-50 rounded-[2rem] p-8 border border-green-100/50">
                    <p class="text-green-700 text-[10px] font-black uppercase tracking-widest mb-1">Harga per {{ $product->unit }}</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-4xl font-black text-green-700">Rp{{ number_format($product->harga, 0, ',', '.') }}</span>
                        <span class="text-green-600/60 font-bold">/ {{ $product->unit }}</span>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Deskripsi Produk</h3>
                    <p class="text-gray-600 leading-relaxed font-medium">
                        {!! $product->deskripsi ? nl2br(e($product->deskripsi)) : 'Sayuran segar berkualitas tinggi, dipanen langsung dari Greenhouse Jember.' !!}
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">

                    @if($product->jumlah_stok <= 0)
                        <button disabled class="flex-1 bg-gray-100 text-gray-400 font-black py-3 px-6 rounded-xl cursor-not-allowed text-sm">
                            Stok Habis
                        </button>
                    @else
                    <div class="flex items-center bg-gray-100 rounded-xl p-1 w-fit border border-gray-200">
                        <button type="button" 
                                @click="if(quantity > 1) quantity--" 
                                class="w-10 h-10 flex items-center justify-center text-2xl font-bold text-gray-500 hover:text-green-600 transition select-none">
                            -
                        </button>

                        <input type="number" 
                            x-model="quantity" 
                            @input="validateInput()"
                            @blur="validateInput()"
                            class="w-12 bg-transparent text-center font-black text-gray-900 border-none focus:ring-0 text-lg [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">

                        <button type="button" 
                                @click="if(quantity < maxStock) quantity++" 
                                class="w-10 h-10 flex items-center justify-center text-2xl font-bold text-gray-500 hover:text-green-600 transition select-none">
                            +
                        </button>
                    </div>
                        @auth
                            <form action="{{ route('cart.store') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="jumlah" :value="quantity">
                                <button type="submit" class="w-full bg-green-100 text-green-700 font-black py-4 px-8 rounded-2xl hover:bg-green-200 transition-all text-sm flex items-center justify-center">
                                    + Keranjang
                                </button>
                            </form>
                            
                            <a :href="'{{ route('checkout.produk.index') }}?product_id={{ $product->id }}&qty=' + quantity"
                               class="flex-1 flex items-center justify-center bg-green-600 text-white font-black py-4 px-8 rounded-2xl hover:bg-green-700 shadow-lg shadow-green-100 transition-all text-sm">
                                Beli Sekarang
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="flex-1 flex items-center justify-center bg-green-100 text-green-700 font-black py-4 px-8 rounded-2xl hover:bg-green-200 transition-all text-sm">
                                + Keranjang
                            </a>

                            <a href="{{ route('login') }}" class="flex-1 flex items-center justify-center bg-green-600 text-white font-black py-4 px-8 rounded-2xl hover:bg-green-700 shadow-lg shadow-green-100 transition-all text-sm">
                                Beli Sekarang
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>

        {{-- INFO DETAIL & ULASAN --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-20">
            {{-- Informasi Produk --}}
            <div class="bg-white rounded-[2.5rem] border border-gray-100 p-10 shadow-sm" data-aos="fade-up">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <h2 class="text-xl font-black text-gray-900">Informasi Produk</h2>
                </div>
                
                <div class="space-y-4">
                    @php
                        $details = [
                            'Nama Produk' => $product->nama_produk,
                            'Kategori' => $product->kategori,
                            'Berat' => ($product->berat ?? '200') . ' gram / ' . $product->unit,
                            'Metode Tanam' => 'Sistem Hidroponik NFT',
                            'Kondisi' => 'Segar Harian',
                            'Asal' => 'Jember, Jawa Timur',
                        ];
                    @endphp
                    @foreach($details as $label => $value)
                    <div class="flex justify-between py-4 border-b border-gray-50 last:border-0">
                        <span class="text-gray-400 font-bold text-sm">{{ $label }}</span>
                        <span class="text-gray-900 font-black text-sm text-right">
                            @if($label == 'Kategori')
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-lg text-[10px] uppercase">{{ $value }}</span>
                            @elseif($label == 'Kondisi')
                                <span class="bg-green-500 text-white px-3 py-1 rounded-lg text-[10px] uppercase font-black">{{ $value }}</span>
                            @else
                                {{ $value }}
                            @endif
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Ulasan --}}
            <div class="bg-white rounded-[2.5rem] border border-gray-100 p-10 shadow-sm" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                    </div>
                    <h2 class="text-xl font-black text-gray-900">Ulasan Pembeli</h2>
                </div>

                @if($product->total_ulasan > 0)
                <div class="flex items-center gap-8 mb-10">
                    <div class="text-center">
                        <p class="text-6xl font-black text-gray-900">{{ number_format($product->average_rating, 1) }}</p>
                        <p class="text-xs font-bold text-gray-400">{{ $product->total_ulasan }} Ulasan</p>
                    </div>
                    
                    @php
                        $counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                        foreach($product->ulasans as $ulasan) {
                            if(isset($counts[$ulasan->rating])) $counts[$ulasan->rating]++;
                        }
                    @endphp
                    <div class="flex-1 space-y-2">
                        @foreach([5, 4, 3, 2, 1] as $star)
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-black text-gray-400 w-2">{{ $star }}</span>
                            <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="bg-yellow-400 h-full rounded-full" style="width: {{ ($counts[$star] / $product->total_ulasan) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-6 max-h-[400px] overflow-y-auto pr-4 custom-scrollbar">
                    @foreach($product->ulasans as $ulasan)
                    <div class="pb-6 border-b border-gray-50 last:border-0">
                        <div class="flex justify-between mb-2">
                            <div>
                                @php
                                    $reviewerName = $ulasan->user->username ?? 'Pelanggan';
                                    $nameLen = strlen($reviewerName);
                                    $censoredName = $nameLen > 2 ? substr($reviewerName, 0, 1) . str_repeat('*', $nameLen - 2) . substr($reviewerName, -1) : substr($reviewerName, 0, 1) . '*';
                                @endphp
                                <p class="font-black text-sm text-gray-900">{{ $censoredName }}</p>
                                <div class="flex items-center gap-0.5 mt-0.5">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-2.5 h-2.5 {{ $i <= $ulasan->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400">{{ \Carbon\Carbon::parse($ulasan->tanggal_ulasan)->locale('id')->translatedFormat('d F Y') }}</span>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed font-medium">{{ $ulasan->komentar ?: 'Tidak ada komentar.' }}</p>
                        
                        @if($ulasan->balasan)
                        <div class="mt-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-1">Balasan Admin</p>
                            <p class="text-xs text-gray-500 leading-relaxed font-medium">{{ $ulasan->balasan }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.921-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                    </div>
                    <p class="text-gray-400 font-bold text-sm">Belum ada ulasan untuk produk ini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Toast Notification Success --}}
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 4000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8"
             class="fixed bottom-8 right-8 z-[100] max-w-sm bg-white border border-green-100 rounded-2xl shadow-2xl shadow-green-200/50 p-2 pr-6 flex items-center gap-4">
            
            <div class="w-12 h-12 bg-green-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <div class="flex-1">
                <p class="font-black text-sm text-gray-900 leading-tight">Berhasil!</p>
                <p class="font-bold text-[10px] text-gray-400 mt-0.5">{{ session('success') }}</p>
            </div>

            <button @click="show = false" class="text-gray-300 hover:text-gray-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif
</div>
@endsection