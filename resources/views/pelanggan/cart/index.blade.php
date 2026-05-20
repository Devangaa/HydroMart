@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="w-full min-h-screen bg-gray-50/50 pb-32 lg:pb-20" x-data="cartHandler()">
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
                Keranjang Belanja
            </span>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-4 tracking-tight">Keranjang Anda</h1>
            <p class="text-gray-500 text-sm mt-2 font-medium">Kelola produk pilihan Anda sebelum melakukan pembayaran</p>
        </div>

        @if($cartItems->isEmpty())
            <div class="bg-white rounded-[2.5rem] p-16 text-center shadow-sm border border-gray-100">
                <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-green-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-2">Keranjangmu masih kosong</h2>
                <p class="text-gray-500 mb-8 font-medium">Ayo mulai belanja produk segar dari Greenhouse HydroMart!</p>
                <a href="{{ route('produk.index') }}" class="inline-flex items-center px-10 py-4 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 transition shadow-lg shadow-green-100 active:scale-95">
                    Mulai Belanja
                </a>
            </div>
        @else
            <form action="{{ route('checkout.produk.index') }}" method="GET">
                <input type="hidden" name="mode" value="cart">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    {{-- Daftar Produk --}}
                    <div class="lg:col-span-2 space-y-4">
                        @foreach($cartItems as $index => $item)
                            <div class="bg-white rounded-3xl p-4 sm:p-6 shadow-sm border border-gray-100 group transition-all hover:shadow-md hover:border-green-100 relative" 
                                 x-data="{ 
                                    qty: {{ $item->jumlah }}, 
                                    price: {{ $item->product->harga }},
                                    loading: false,
                                    increment() { if(this.qty < {{ $item->product->jumlah_stok }}) { this.qty++; $dispatch('update-cart', { id: {{ $item->id }}, qty: this.qty }); } },
                                    decrement() { if(this.qty > 1) { this.qty--; $dispatch('update-cart', { id: {{ $item->id }}, qty: this.qty }); } }
                                 }">
                                
                                {{-- Top Section: Checkbox, Image, Info, Delete --}}
                                <div class="flex gap-4 items-center">
                                    {{-- Checkbox --}}
                                    <div>
                                        <label class="relative flex items-center cursor-pointer">
                                            <input type="checkbox" name="cart_ids[]" value="{{ $item->id }}" 
                                                   class="peer hidden"
                                                   @change="updateSummary()"
                                                   @if($item->product->jumlah_stok <= 0) disabled @endif>
                                            <div class="w-6 h-6 border-2 border-gray-200 rounded-lg flex items-center justify-center transition-all peer-checked:bg-green-600 peer-checked:border-green-600 peer-disabled:bg-gray-100 peer-disabled:border-gray-100 peer-checked:[&_svg]:opacity-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white opacity-0 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </label>
                                    </div>

                                    {{-- Image --}}
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-gray-50 flex-shrink-0 overflow-hidden border border-gray-100 group-hover:scale-105 transition-transform">
                                        @php
                                            $productFoto = $item->product->foto_produk;
                                            if (is_array($productFoto)) {
                                                $productFoto = $productFoto[0] ?? null;
                                            }
                                            $photoUrl = $productFoto ? asset('uploads/produk/' . $productFoto) : 'https://ui-avatars.com/api/?name=' . urlencode($item->product->nama_produk);
                                        @endphp
                                        <img src="{{ $photoUrl }}" alt="{{ $item->product->nama_produk }}" class="w-full h-full object-cover">
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-grow min-w-0">
                                        <div class="flex justify-between items-start gap-2">
                                            <div>
                                                <h3 class="text-sm sm:text-base font-black text-gray-900 leading-tight line-clamp-2">{{ $item->product->nama_produk }}</h3>
                                                <p class="text-[9px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">{{ $item->product->kategori }}</p>
                                            </div>
                                            <button type="button" @click="removeItem({{ $item->id }})" class="flex-shrink-0 p-2 text-gray-300 hover:text-red-500 transition-colors bg-gray-50 rounded-xl">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bottom Section: Price & Qty --}}
                                <div class="mt-4 pt-4 border-t border-gray-50 flex flex-wrap items-center justify-between gap-4">
                                    <div class="flex gap-6 sm:gap-10">
                                        <div class="space-y-0.5">
                                            <p class="text-[9px] text-gray-400 font-bold uppercase">Harga</p>
                                            <p class="text-xs font-black text-gray-700 leading-none">Rp{{ number_format($item->product->harga, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="space-y-0.5">
                                            <p class="text-[9px] text-gray-400 font-bold uppercase text-green-600/70">Subtotal</p>
                                            <p class="text-sm sm:text-base font-black text-green-600 leading-none">Rp<span x-text="formatNumber(qty * price)"></span></p>
                                        </div>
                                    </div>

                                    @if($item->product->jumlah_stok <= 0)
                                        <span class="text-[9px] font-black text-red-500 bg-red-50 px-3 py-1.5 rounded-lg uppercase border border-red-100">Habis</span>
                                    @else
                                        {{-- Quantity Control --}}
                                        <div class="flex items-center bg-gray-50 rounded-xl p-1 border border-gray-100">
                                            <button type="button" @click="decrement()" 
                                                    class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center text-gray-400 hover:text-green-600 hover:bg-white hover:shadow-sm rounded-lg transition-all disabled:opacity-30"
                                                    :disabled="qty <= 1 || loading">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                            </button>
                                            <input type="number" x-model.number="qty" 
                                                   @input.debounce.500ms="$dispatch('update-cart', { id: {{ $item->id }}, qty: qty })"
                                                   class="w-10 text-center bg-transparent border-none focus:ring-0 text-sm font-black text-gray-900 px-0"
                                                   :disabled="loading">
                                            <button type="button" @click="increment()" 
                                                    class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center text-gray-400 hover:text-green-600 hover:bg-white hover:shadow-sm rounded-lg transition-all disabled:opacity-30"
                                                    :disabled="qty >= {{ $item->product->jumlah_stok }} || loading">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Ringkasan Pesanan --}}
                    <div class="lg:col-span-1 lg:sticky lg:top-24 sticky bottom-4 z-[40]">
                        <div class="bg-white rounded-3xl lg:rounded-[2rem] p-4 lg:p-8 shadow-2xl lg:shadow-sm border border-gray-100 overflow-hidden transition-all duration-300">
                            <div class="hidden lg:block absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-full -mr-16 -mt-16 opacity-50"></div>
                            
                            <h3 class="hidden lg:block text-xl font-black text-gray-900 mb-6 relative">Ringkasan Pesanan</h3>
                            
                            <div class="hidden lg:flex justify-between text-gray-400 font-bold text-[12px] uppercase tracking-wider mb-6 relative">
                                <span>Produk Terpilih</span>
                                <span class="text-gray-900" x-text="selectedCount + ' Item'"></span>
                            </div>

                            <div class="flex lg:flex-col items-center lg:items-start justify-between gap-4 relative">
                                <div class="lg:pt-6 lg:border-t lg:border-gray-100 lg:mb-10 w-full lg:w-auto">
                                    <p class="text-[9px] lg:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 leading-none">Total Pembayaran</p>
                                    <div class="text-xl lg:text-3xl font-black text-green-600 leading-none">
                                        Rp<span x-text="formatNumber(totalPrice)"></span>
                                    </div>
                                </div>

                                <button type="submit" 
                                        class="flex-grow lg:w-full py-3.5 lg:py-5 px-8 bg-green-600 text-white font-black rounded-2xl hover:bg-green-700 transition shadow-lg shadow-green-100 flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed group active:scale-95 text-sm lg:text-base whitespace-nowrap"
                                        :disabled="selectedCount === 0">
                                    <span class="hidden lg:inline">Lanjut ke Checkout</span>
                                    <span class="lg:hidden whitespace-nowrap text-xs">Checkout (<span x-text="selectedCount"></span>)</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="hidden lg:block h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hapus Bar Melayang Lama --}}
            </form>
        @endif
    </div>
    
    {{-- Modal Konfirmasi Hapus --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div @click.away="showDeleteModal = false" 
             x-show="showDeleteModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center">
            
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>

            <h3 class="text-xl font-black text-gray-900 mb-2">Hapus dari Keranjang?</h3>
            <p class="text-gray-500 text-sm mb-8 leading-relaxed font-medium">Produk ini akan dihapus dari daftar belanja Anda.</p>
            
            <div class="flex flex-col gap-3">
                <form :action="'{{ url('/keranjang/hapus') }}/' + cartIdToDelete" method="POST" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-4 bg-red-500 text-white font-black rounded-2xl hover:bg-red-600 transition shadow-lg shadow-red-100 active:scale-95">
                        Ya, Hapus Produk
                    </button>
                </form>
                <button @click="showDeleteModal = false" class="w-full py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl hover:bg-gray-200 transition active:scale-95">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@php
    $cartConfig = [
        'items' => $cartJson,
        'updateUrl' => url('/keranjang/update'),
    ];
@endphp
<div id="cart-config" class="hidden" data-config="{{ json_encode($cartConfig) }}"></div>

@push('scripts')
<!--
<script>
    function cartHandler() {
        return {
            selectedCount: 0,
            totalPrice: 0,
            showDeleteModal: false,
            cartIdToDelete: null,
            items: @json($cartJson),

            init() {
                this.updateSummary();
                // Listen for quantity updates from components
                window.addEventListener('update-cart', (e) => {
                    this.updateQty(e.detail.id, e.detail.qty);
                });
            },

            updateSummary() {
                const checkboxes = document.querySelectorAll('input[name="cart_ids[]"]:checked');
                this.selectedCount = checkboxes.length;
                
                let total = 0;
                checkboxes.forEach(cb => {
                    const item = this.items.find(i => i.id == cb.value);
                    if (item) {
                        total += item.price * item.qty;
                    }
                });
                this.totalPrice = total;
            },

            async updateQty(cartId, newQty) {
                const item = this.items.find(i => i.id == cartId);
                if (!item || newQty < 1 || newQty > item.stok) return;

                item.qty = newQty;
                this.updateSummary();

                try {
                    const response = await fetch(`{{ url('/keranjang/update') }}/${cartId}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ jumlah: newQty })
                    });

                    const data = await response.json();
                    if (!data.success) {
                        alert(data.message);
                        location.reload(); 
                    }
                } catch (error) {
                    console.error('Error updating quantity:', error);
                }
            },

            removeItem(cartId) {
                this.cartIdToDelete = cartId;
                this.showDeleteModal = true;
            },

            formatNumber(num) {
                return new Intl.NumberFormat('id-ID').format(num);
            }
        }
    }
</script>
-->
@endpush
@endsection
