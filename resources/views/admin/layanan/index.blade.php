@extends('layouts.app')

@section('content')
<div x-data="{ 
    showDeleteModal: false, 
    deleteUrl: '',
    showCreateModal: {{ ($errors->any() && !session('editingLayananId')) ? 'true' : 'false' }}, 
    showEditModal: {{ session('editingLayananId') ? 'true' : 'false' }},
    editUrl: '{{ session('editingLayananId') ? route('admin.layanan.update', session('editingLayananId')) : '' }}',
    editData: {
        nama_layanan: '{{ old('nama_layanan', '') }}' || '',
        harga: '{{ old('harga', '') }}' || '',
        deskripsi: '{{ addslashes(old('deskripsi', '')) }}' || ''
    }}" class="w-full">
    <div class="min-h-screen bg-gray-50/50 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <span class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full uppercase tracking-wider">Manajemen Jasa</span>
                    <h1 class="text-3xl font-extrabold text-gray-900 mt-2">Data Layanan</h1>
                    <p class="text-gray-500 text-sm">Kelola semua layanan jasa hidroponik yang tersedia di toko</p>
                </div>
                <button @click="showCreateModal = true" type="button" class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-2xl transition-all shadow-lg shadow-green-200 gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Layanan
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-6 mb-8">
                @php
                    $cardStyles = [
                        ['label' => 'Total Layanan', 'value' => $stats['total'], 'color' => 'text-gray-900'],
                        ['label' => 'Layanan Aktif', 'value' => $stats['aktif'], 'color' => 'text-green-600'],
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

            <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm mb-6">
                <form action="{{ route('admin.layanan.index') }}" method="GET" class="flex flex-wrap gap-4 items-center justify-between">
                    <div class="relative flex-1 min-w-[300px]">
                        <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama layanan..." 
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="hidden">Cari</button>

                        <a href="{{ route('admin.layanan.index') }}" 
                        class="px-6 py-3 font-bold rounded-xl text-sm transition-all duration-300 {{ request('status') != 'terhapus' ? 'bg-green-600 text-white' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">
                            Aktif
                        </a>
                        <a href="{{ route('admin.layanan.index', ['status' => 'terhapus']) }}" 
                        class="px-6 py-3 font-bold rounded-xl text-sm transition-all duration-300 {{ request('status') == 'terhapus' ? 'bg-red-600 text-white' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">
                            Terhapus
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h2 class="font-bold text-gray-900">Daftar Layanan {{ request('status') == 'terhapus' ? '(Terhapus)' : '' }}</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Layanan</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Harga</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($layanan as $item)
                            <tr class="hover:bg-gray-50/30 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gray-100 rounded-xl overflow-hidden border border-gray-100">
                                            <img src="{{ $item->foto_layanan ? asset('uploads/layanan/'.$item->foto_layanan) : 'https://ui-avatars.com/api/?name='.$item->nama_layanan }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm leading-tight">{{ $item->nama_layanan }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase">ID: DL0{{ $item->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900 text-sm">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->is_delete)
                                        <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-lg uppercase">Terhapus</span>
                                    @else
                                        <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-bold rounded-lg uppercase">Aktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        @if($item->is_delete)
                                            <form action="{{ route('admin.layanan.update', $item->id) }}" method="POST" class="inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="restore" value="1">
                                                <button type="submit" class="px-4 py-2 bg-orange-50 text-orange-600 text-xs font-bold rounded-xl hover:bg-orange-600 hover:text-white transition">Pulihkan</button>
                                            </form>
                                        @else
                                            
                                            <button 
                                                @click="
                                                    showEditModal = true; 
                                                    editUrl = '{{ route('admin.layanan.update', $item->id) }}';
                                                    editData = {
                                                        nama_layanan: '{{ addslashes($item->nama_layanan) }}',
                                                        harga: '{{ $item->harga }}',
                                                        deskripsi: '{{ addslashes($item->deskripsi ?? '') }}'
                                                    }
                                                " 
                                                class="px-4 py-2 bg-green-50 text-green-600 text-xs font-bold rounded-xl hover:bg-green-600 hover:text-white transition">
                                                Edit
                                            </button>

                                            <button @click="showDeleteModal = true; deleteUrl = '{{ route('admin.layanan.destroy', $item->id) }}'" 
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
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-bold">Tidak ada data layanan ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="p-6 bg-gray-50/50 flex justify-between items-center">
                    <p class="text-xs font-bold text-gray-400">
                        Menampilkan {{ $layanan->firstItem() ?? 0 }}-{{ $layanan->lastItem() ?? 0 }} dari {{ $layanan->total() }} layanan
                    </p>
                    
                    <div class="flex gap-1">
                        @if ($layanan->onFirstPage())
                            <span class="px-4 py-2 bg-white border border-gray-100 text-gray-300 text-xs font-bold rounded-xl cursor-not-allowed">
                                Sebelumnya
                            </span>
                        @else
                            <a href="{{ $layanan->previousPageUrl() }}" class="px-4 py-2 bg-white border border-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-green-600 hover:text-white hover:border-green-600 transition-all duration-300">
                                Sebelumnya
                            </a>
                        @endif

                        @foreach ($layanan->getUrlRange(max(1, $layanan->currentPage() - 1), min($layanan->lastPage(), $layanan->currentPage() + 1)) as $page => $url)
                            @if ($page == $layanan->currentPage())
                                <span class="px-4 py-2 bg-green-600 text-white text-xs font-bold rounded-xl shadow-sm shadow-green-100">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 bg-white border border-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-gray-50 transition-all">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if ($layanan->hasMorePages())
                            <a href="{{ $layanan->nextPageUrl() }}" class="px-4 py-2 bg-white border border-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-green-600 hover:text-white hover:border-green-600 transition-all duration-300">
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

    @include('admin.layanan.modal-delete')
    @include('admin.layanan.modal-create')
    @include('admin.layanan.modal-edit')
</div>
@endsection
