@extends('layouts.app')

{{-- ============================================================================= --}}
{{-- FILE: admin/layanan/index.blade.php --}}
{{-- HALAMAN: Kelola Layanan --}}
{{-- DESKRIPSI: Tabel manajemen layanan dengan CRUD dan modal terkait. --}}
{{-- ============================================================================= --}}

@section('title', 'Kelola Layanan')

@section('content')
<div x-data="{ 
    showDeleteModal: false, 
    showReviewModal: false,
    reviewData: [],
    deleteUrl: '',
    showCreateModal: {{ ($errors->any() && !session('editingLayananId')) ? 'true' : 'false' }}, 
    showEditModal: {{ session('editingLayananId') ? 'true' : 'false' }},
    editUrl: '{{ session('editingLayananId') ? route('admin.layanan.update', session('editingLayananId')) : '' }}',
    editData: {
        nama_layanan: '{{ old('nama_layanan', '') }}' || '',
        harga: '{{ old('harga', '') }}' || '',
        deskripsi: '{{ addslashes(old('deskripsi', '')) }}' || '',
        foto_layanan: [],
    }}"
    x-init="
    @if($errors->any() && session('editingLayananId'))
        @php
            $oldLayanan = \App\Models\Layanan::find(session('editingLayananId'));
        @endphp
        @if($oldLayanan)
            editUrl = '{{ route('admin.layanan.update', $oldLayanan->id) }}';
            editData = {
                nama_layanan: '{{ old('nama_layanan', $oldLayanan->nama_layanan) }}',
                harga: '{{ old('harga', $oldLayanan->harga) }}',
                deskripsi: '{{ addslashes(old('deskripsi', $oldLayanan->deskripsi)) }}',
                foto_layanan: {{ json_encode($oldLayanan->foto_layanan ?? []) }}
            };
        @endif
    @endif"
    class="w-full">

    <div class="min-h-screen bg-gray-50/50 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Bagian: Breadcrumb --}}
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
                {{-- Bagian: Header & Tombol Tambah --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">
                            Manajemen Layanan
                        </span>
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
            </div>

            <div data-aos="fade-up">
                
                {{-- Bagian: Filter & Pencarian --}}
                <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm mb-6 relative overflow-visible">
                    <form action="{{ route('admin.layanan.index') }}" method="GET" class="flex flex-col gap-4 sm:flex-row sm:flex-wrap items-start justify-between">
                        <div class="flex flex-1 items-center gap-2 min-w-0">
                            <div class="relative flex-1 min-w-0">
                                <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama layanan..." 
                                    class="w-full pl-12 pr-12 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm">
                            </div>

                            @if(request('search') || request('status'))
                                <a href="{{ route('admin.layanan.index') }}" 
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                            <button type="submit" class="hidden">Cari</button>

                            <a href="{{ route('admin.layanan.index') }}" 
                            class="w-full sm:w-auto px-4 py-3 font-bold rounded-xl text-sm text-center transition-all duration-300 {{ request('status') != 'terhapus' ? 'bg-green-600 text-white' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">
                                Aktif
                            </a>
                            <a href="{{ route('admin.layanan.index', ['status' => 'terhapus']) }}" 
                            class="w-full sm:w-auto px-4 py-3 font-bold rounded-xl text-sm text-center transition-all duration-300 {{ request('status') == 'terhapus' ? 'bg-red-600 text-white' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">
                                Terhapus
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Bagian: Tabel Data --}}
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                        <h2 class="font-bold text-gray-900">Daftar Layanan {{ request('status') == 'terhapus' ? '(Terhapus)' : '' }}</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nama_layanan', 'order' => request('sort_by') == 'nama_layanan' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-green-600 transition-colors">
                                            Layanan
                                            @if(request('sort_by') == 'nama_layanan')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ request('order') == 'asc' ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'harga', 'order' => request('sort_by') == 'harga' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-green-600 transition-colors">
                                            Harga
                                            @if(request('sort_by') == 'harga')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ request('order') == 'asc' ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'ulasans_avg_rating', 'order' => request('sort_by') == 'ulasans_avg_rating' && request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-green-600 transition-colors">
                                            Rating
                                            @if(request('sort_by') == 'ulasans_avg_rating')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ request('order') == 'asc' ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($layanan as $item)
                                <tr class="hover:bg-gray-50/30 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 aspect-square shrink-0 bg-gray-100 rounded-xl overflow-hidden border border-gray-100">
                                                <img src="{{ !empty($item->foto_layanan) && is_array($item->foto_layanan) 
                                                            ? asset('uploads/layanan/' . $item->foto_layanan[0]) 
                                                            : 'https://ui-avatars.com/api/?name=' . $item->nama_layanan }}" 
                                                    class="w-full h-full rounded-lg object-cover">
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900 text-sm leading-tight">{{ $item->nama_layanan }}</p>
                                                <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase">ID: DP0{{ $item->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900 text-sm whitespace-nowrap">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900 text-sm">
                                        <div class="flex items-center gap-1 text-orange-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span>{{ number_format($item->ulasans_avg_rating ?? 0, 1) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->is_delete)
                                            <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-lg uppercase">Terhapus</span>
                                        @else
                                            <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-bold rounded-lg uppercase">Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col sm:flex-row items-center justify-center gap-2">
                                            @if($item->is_delete)
                                                <form action="{{ route('admin.layanan.update', $item->id) }}" method="POST" class="inline w-full sm:w-auto">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="restore" value="1">
                                                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-orange-50 text-orange-600 text-xs font-bold rounded-xl hover:bg-orange-600 hover:text-white transition">Pulihkan</button>
                                                </form>
                                            @else
                                                <button 
                                                    @click="
                                                        showReviewModal = true;
                                                        reviewData = {{ json_encode($item->ulasans) }};
                                                    "
                                                    class="w-full sm:w-auto px-4 py-2 bg-blue-50 text-blue-600 text-xs font-bold rounded-xl hover:bg-blue-600 hover:text-white transition">
                                                    Lihat Ulasan
                                                </button>
                                                
                                                <button 
                                                    @click="
                                                        showEditModal = true; 
                                                        editUrl = '{{ route('admin.layanan.update', $item->id) }}';
                                                        editData = {{ json_encode([
                                                            'nama_layanan' => $item->nama_layanan,
                                                            'harga'        => $item->harga,
                                                            'deskripsi'    => $item->deskripsi ?? '',
                                                            'foto_layanan' => $item->foto_layanan ?? [],
                                                        ]) }}
                                                    " 
                                                    class="w-full sm:w-auto px-4 py-2 bg-green-50 text-green-600 text-xs font-bold rounded-xl hover:bg-green-600 hover:text-white transition">
                                                    Edit
                                                </button>

                                                <button @click="showDeleteModal = true; deleteUrl = '{{ route('admin.layanan.destroy', $item->id) }}'" 
                                                    type="button" 
                                                    class="w-full sm:w-auto px-4 py-2 bg-red-50 text-red-500 text-xs font-bold rounded-xl hover:bg-red-500 hover:text-white hover:shadow-md active:scale-90 transition-all duration-200">
                                                    Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-bold">Tidak ada data layanan ditemukan.</td>
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
                                    <
                                </span>
                            @else
                                <a href="{{ $layanan->previousPageUrl() }}" class="px-4 py-2 bg-white border border-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-green-600 hover:text-white hover:border-green-600 transition-all duration-300">
                                    <
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
                                    >
                                </a>
                            @else
                                <span class="px-4 py-2 bg-white border border-gray-100 text-gray-300 text-xs font-bold rounded-xl cursor-not-allowed">
                                    >
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bagian: Modal Terkait --}}
    @include('admin.layanan.modal-delete')
    @include('admin.layanan.modal-create')
    @include('admin.layanan.modal-edit')
    @include('admin.layanan.modal-reviews')
</div>
@endsection
