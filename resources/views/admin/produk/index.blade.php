@extends('layouts.app')

@section('content')
<div x-data="{ 
    showDeleteModal: false, 
    deleteUrl: '',
    showCreateModal: {{ ($errors->any() && !session('editingProductId')) ? 'true' : 'false' }}, 
    showEditModal: {{ session('editingProductId') ? 'true' : 'false' }},
    editUrl: '{{ session('editingProductId') ? route('admin.produk.update', session('editingProductId')) : '' }}',
    editData: {
        nama_produk: '{{ old('nama_produk', '') }}' || '',
        kategori: '{{ old('kategori', '') }}' || '',
        harga: '{{ old('harga', '') }}' || '',
        jumlah_stok: '{{ old('jumlah_stok', '') }}' || '',
        unit: '{{ old('unit', '') }}' || '',
        berat: '{{ old('berat', '') }}' || '',
        deskripsi: '{{ addslashes(old('deskripsi', '')) }}' || '',
        foto_produk: [],
    }}"
    x-init="
    @if($errors->any() && session('editingProductId'))
        @php
            $oldProduct = \App\Models\Product::find(session('editingProductId'));
        @endphp
        @if($oldProduct)
            editUrl = '{{ route('admin.produk.update', $oldProduct->id) }}';
            editData = {
                nama_produk: '{{ old('nama_produk', $oldProduct->nama_produk) }}',
                kategori: '{{ old('kategori', $oldProduct->kategori) }}',
                harga: '{{ old('harga', $oldProduct->harga) }}',
                jumlah_stok: '{{ old('jumlah_stok', $oldProduct->jumlah_stok) }}',
                unit: '{{ old('unit', $oldProduct->unit) }}',
                berat: '{{ old('berat', $oldProduct->berat) }}',
                deskripsi: '{{ addslashes(old('deskripsi', $oldProduct->deskripsi)) }}',
                foto_produk: {{ json_encode($oldProduct->foto_produk ?? []) }}
            };
        @endif
    @endif"
    class="w-full">

    <div class="min-h-screen bg-gray-50/50 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-gray-400 hover:text-green-600 transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Dashboard
                        </a>
                    </li>
                </ol>
            </nav>

            <div data-aos="fade-right">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">
                            Manajemen Produk
                        </span>
                        <h1 class="text-3xl font-extrabold text-gray-900 mt-2">Data Produk</h1>
                        <p class="text-gray-500 text-sm">Kelola semua produk hidroponik yang tersedia di toko</p>
                    </div>
                    <button @click="showCreateModal = true" type="button" class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-2xl transition-all shadow-lg shadow-green-200 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Produk
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    @php
                        $cardStyles = [
                            ['label' => 'Total Produk', 'value' => $stats['total'], 'color' => 'text-gray-900'],
                            ['label' => 'Produk Aktif', 'value' => $stats['aktif'], 'color' => 'text-green-600'],
                            ['label' => 'Stok Menipis', 'value' => $stats['menipis'], 'color' => 'text-orange-500'],
                            ['label' => 'Dihapus', 'value' => $stats['dihapus'], 'color' => 'text-red-500'],
                        ];
                    @endphp
                    @foreach($cardStyles as $card)
                    <div class="bg-white p-6 rounded-[1.5rem] border border-gray-100 shadow-sm">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $card['label'] }}</p>
                        <p class="text-3xl font-black {{ $card['color'] }}">{{ $card['value'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div data-aos="fade-up">
                <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm mb-6">
                    <form action="{{ route('admin.produk.index') }}" method="GET" class="flex flex-wrap gap-4 items-center justify-between">
                        <div class="relative flex-1 min-w-[300px]">
                            <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." 
                                class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm">
                        </div>

                        <div class="flex gap-2">
                            <select name="category" onchange="this.form.submit()" 
                                    class="bg-gray-50 border-none rounded-xl px-4 py-3 text-sm font-bold text-gray-600 outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                            
                            @if(request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif

                            <button type="submit" class="hidden">Cari</button>

                            <a href="{{ route('admin.produk.index') }}" 
                            class="px-6 py-3 font-bold rounded-xl text-sm transition-all duration-300 {{ request('status') != 'terhapus' ? 'bg-green-600 text-white' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">
                                Aktif
                            </a>
                            <a href="{{ route('admin.produk.index', ['status' => 'terhapus']) }}" 
                            class="px-6 py-3 font-bold rounded-xl text-sm transition-all duration-300 {{ request('status') == 'terhapus' ? 'bg-red-600 text-white' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">
                                Terhapus
                            </a>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                        <h2 class="font-bold text-gray-900">Daftar Produk {{ request('status') == 'terhapus' ? '(Terhapus)' : '' }}</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Produk</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kategori</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Harga</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Stok</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Unit</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($products as $product)
                                <tr class="hover:bg-gray-50/30 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-gray-100 rounded-xl overflow-hidden border border-gray-100">
                                                <img src="{{ !empty($product->foto_produk) && is_array($product->foto_produk) 
                                                            ? asset('uploads/produk/' . $product->foto_produk[0]) 
                                                            : 'https://ui-avatars.com/api/?name=' . $product->nama_produk }}" 
                                                    class="w-12 h-12 rounded-lg object-cover">
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900 text-sm leading-tight">{{ $product->nama_produk }}</p>
                                                <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase">ID: DP0{{ $product->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-bold rounded-lg uppercase">{{ $product->kategori }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900 text-sm">
                                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900 text-sm">{{ $product->jumlah_stok }}</td>
                                    <td class="px-6 py-4 text-gray-500 text-sm">{{ $product->unit }}</td>
                                    <td class="px-6 py-4">
                                        @if($product->is_delete)
                                            <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-lg uppercase">Terhapus</span>
                                        @elseif($product->jumlah_stok <= 10)
                                            <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-bold rounded-lg uppercase">Stok Menipis</span>
                                        @else
                                            <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-bold rounded-lg uppercase">Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-2">
                                            @if($product->is_delete)
                                                <form action="{{ route('admin.produk.update', $product->id) }}" method="POST" class="inline">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="restore" value="1">
                                                    <button type="submit" class="px-4 py-2 bg-orange-50 text-orange-600 text-xs font-bold rounded-xl hover:bg-orange-600 hover:text-white transition">Pulihkan</button>
                                                </form>
                                            @else
                                                
                                                <button 
                                                    @click="
                                                        showEditModal = true; 
                                                        editUrl = '{{ route('admin.produk.update', $product->id) }}';
                                                        editData = {
                                                            nama_produk: '{{ addslashes($product->nama_produk) }}',
                                                            kategori: '{{ $product->kategori }}',
                                                            harga: '{{ $product->harga }}',
                                                            jumlah_stok: '{{ $product->jumlah_stok }}',
                                                            unit: '{{ $product->unit }}',
                                                            berat: '{{ $product->berat ?? '' }}',
                                                            deskripsi: '{{ addslashes($product->deskripsi ?? '') }}',
                                                            foto_produk: {{ json_encode($product->foto_produk) }} // WAJIB ADA INI
                                                        }
                                                    "
                                                    class="px-4 py-2 bg-green-50 text-green-600 text-xs font-bold rounded-xl hover:bg-green-600 hover:text-white transition">
                                                    Edit
                                                </button>

                                                <button @click="showDeleteModal = true; deleteUrl = '{{ route('admin.produk.destroy', $product->id) }}'" 
                                                    type="button" 
                                                    class="px-4 py-2 bg-red-50 text-red-500 text-xs font-bold rounded-xl hover:bg-red-500 hover:text-white hover:shadow-md active:scale-90 transition-all duration-200">
                                                    Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-400 font-bold">Tidak ada data produk ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-6 bg-gray-50/50 flex justify-between items-center">
                        <p class="text-xs font-bold text-gray-400">
                            Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
                        </p>
                        
                        <div class="flex gap-1">
                            @if ($products->onFirstPage())
                                <span class="px-4 py-2 bg-white border border-gray-100 text-gray-300 text-xs font-bold rounded-xl cursor-not-allowed">
                                    Sebelumnya
                                </span>
                            @else
                                <a href="{{ $products->previousPageUrl() }}" class="px-4 py-2 bg-white border border-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-green-600 hover:text-white hover:border-green-600 transition-all duration-300">
                                    Sebelumnya
                                </a>
                            @endif

                            @foreach ($products->getUrlRange(max(1, $products->currentPage() - 1), min($products->lastPage(), $products->currentPage() + 1)) as $page => $url)
                                @if ($page == $products->currentPage())
                                    <span class="px-4 py-2 bg-green-600 text-white text-xs font-bold rounded-xl shadow-sm shadow-green-100">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="px-4 py-2 bg-white border border-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-gray-50 transition-all">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if ($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" class="px-4 py-2 bg-white border border-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-green-600 hover:text-white hover:border-green-600 transition-all duration-300">
                                    Selanjutnya
                                </a>
                            @else
                                <span class="px-4 py-2 bg-white border border-gray-100 text-gray-300 text-xs font-bold rounded-xl cursor-not-allowed">
                                    Selanjutnya
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('admin.produk.modal-delete')
    @include('admin.produk.modal-create')
    @include('admin.produk.modal-edit')
</div>
@endsection