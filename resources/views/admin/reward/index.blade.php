@extends('layouts.app')

{{-- ============================================================================= --}}
{{-- FILE: admin/reward/index.blade.php --}}
{{-- HALAMAN: Kelola Reward --}}
{{-- DESKRIPSI: Manajemen program reward dan poin loyalitas. --}}
{{-- ============================================================================= --}}

@section('title', 'Kelola Reward')

@section('content')
<div x-data="{ 
    showDeleteModal: false, 
    deleteUrl: '',
    showCreateModal: {{ ($errors->any() && !session('editingRewardId')) ? 'true' : 'false' }}, 
    showEditModal: {{ session('editingRewardId') ? 'true' : 'false' }},
    editUrl: '{{ session('editingRewardId') ? route('admin.reward.update', session('editingRewardId')) : '' }}',
    editData: {
        nama_reward: '{{ old('nama_reward', '') }}' || '',
        poin_diperlukan: '{{ old('poin_diperlukan', '') }}' || '',
        diskon: '{{ old('diskon', '') }}' || '',
        minimal_pembelian: '{{ old('minimal_pembelian', '') }}' || '',
        durasi_reward: '{{ old('durasi_reward', '') }}' || '',
        deskripsi: '{{ addslashes(old('deskripsi', '')) }}' || '',
    }}"
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
                            Manajemen Reward
                        </span>
                        <h1 class="text-3xl font-extrabold text-gray-900 mt-2">Data Reward</h1>
                        <p class="text-gray-500 text-sm">Kelola semua reward penukaran poin untuk pelanggan</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.reward.customers') }}" class="inline-flex items-center justify-center bg-white border border-gray-100 text-gray-600 font-bold py-3 px-6 rounded-2xl transition-all shadow-sm hover:bg-gray-50 gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Cek Data Reward Pelanggan
                        </a>
                        <button @click="showCreateModal = true" type="button" class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-2xl transition-all shadow-lg shadow-green-200 gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Reward
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                    @php
                        $cardStyles = [
                            ['label' => 'Total Reward', 'value' => $stats['total'], 'color' => 'text-gray-900'],
                            ['label' => 'Reward Aktif', 'value' => $stats['aktif'], 'color' => 'text-green-600'],
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
                    <form action="{{ route('admin.reward.index') }}" method="GET" class="flex flex-col gap-4 sm:flex-row sm:flex-wrap items-start justify-between">
                        <div class="flex flex-1 items-center gap-2 min-w-0">
                            <div class="relative flex-1 min-w-0">
                                <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama reward..." 
                                    class="w-full pl-12 pr-12 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm">
                            </div>

                            @if(request('search') || request('status'))
                                <a href="{{ route('admin.reward.index') }}" 
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                            @if(request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif

                            <button type="submit" class="hidden">Cari</button>

                            <a href="{{ route('admin.reward.index') }}" 
                            class="w-full sm:w-auto px-4 py-3 font-bold rounded-xl text-sm text-center transition-all duration-300 {{ request('status') != 'terhapus' ? 'bg-green-600 text-white' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">
                                Aktif
                            </a>
                            <a href="{{ route('admin.reward.index', ['status' => 'terhapus']) }}" 
                            class="w-full sm:w-auto px-4 py-3 font-bold rounded-xl text-sm text-center transition-all duration-300 {{ request('status') == 'terhapus' ? 'bg-red-600 text-white' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}">
                                Terhapus
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Bagian: Tabel Data --}}
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                        <h2 class="font-bold text-gray-900">Daftar Reward {{ request('status') == 'terhapus' ? '(Terhapus)' : '' }}</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Reward</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Poin Diperlukan</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Diskon</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Min. Pembelian</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Durasi</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($rewards as $reward)
                                <tr class="hover:bg-gray-50/30 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-green-600 shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900 text-sm leading-tight">{{ $reward->nama_reward }}</p>
                                                <p class="text-[10px] text-gray-400 mt-1 line-clamp-1 max-w-[200px]">{{ $reward->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black rounded-lg uppercase whitespace-nowrap">{{ number_format($reward->poin_diperlukan) }} Poin</span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-green-600 text-sm whitespace-nowrap">
                                        Rp {{ number_format($reward->diskon, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-gray-900 text-sm whitespace-nowrap">
                                        Rp {{ number_format($reward->minimal_pembelian, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-500 text-sm font-bold whitespace-nowrap">
                                        {{ $reward->durasi_reward }} Hari
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($reward->is_delete)
                                                <form action="{{ route('admin.reward.update', $reward->id) }}" method="POST" class="inline w-full sm:w-auto">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="restore" value="1">
                                                    <button type="submit" class="px-4 py-2 bg-orange-50 text-orange-600 text-xs font-bold rounded-xl hover:bg-orange-600 hover:text-white transition">Pulihkan</button>
                                                </form>
                                            @else
                                                <button 
                                                    @click="
                                                        showEditModal = true; 
                                                        editUrl = '{{ route('admin.reward.update', $reward->id) }}';
                                                        editData = {
                                                            nama_reward: '{{ addslashes($reward->nama_reward) }}',
                                                            poin_diperlukan: '{{ $reward->poin_diperlukan }}',
                                                            diskon: '{{ $reward->diskon }}',
                                                            minimal_pembelian: '{{ $reward->minimal_pembelian }}',
                                                            durasi_reward: '{{ $reward->durasi_reward }}',
                                                            deskripsi: '{{ addslashes($reward->deskripsi ?? '') }}'
                                                        }
                                                    "
                                                    class="px-4 py-2 bg-green-50 text-green-600 text-xs font-bold rounded-xl hover:bg-green-600 hover:text-white transition">
                                                    Edit
                                                </button>

                                                <button @click="showDeleteModal = true; deleteUrl = '{{ route('admin.reward.destroy', $reward->id) }}'" 
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
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-bold">Tidak ada data reward ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-6 bg-gray-50/50 flex justify-between items-center">
                        <p class="text-xs font-bold text-gray-400">
                            Menampilkan {{ $rewards->firstItem() ?? 0 }}-{{ $rewards->lastItem() ?? 0 }} dari {{ $rewards->total() }} reward
                        </p>
                        
                        <div class="flex gap-1">
                            @if ($rewards->onFirstPage())
                                <span class="px-4 py-2 bg-white border border-gray-100 text-gray-300 text-xs font-bold rounded-xl cursor-not-allowed">
                                    <
                                </span>
                            @else
                                <a href="{{ $rewards->previousPageUrl() }}" class="px-4 py-2 bg-white border border-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-green-600 hover:text-white hover:border-green-600 transition-all duration-300">
                                    <
                                </a>
                            @endif

                            @foreach ($rewards->getUrlRange(max(1, $rewards->currentPage() - 1), min($rewards->lastPage(), $rewards->currentPage() + 1)) as $page => $url)
                                @if ($page == $rewards->currentPage())
                                    <span class="px-4 py-2 bg-green-600 text-white text-xs font-bold rounded-xl shadow-sm shadow-green-100">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="px-4 py-2 bg-white border border-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-gray-50 transition-all">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if ($rewards->hasMorePages())
                                <a href="{{ $rewards->nextPageUrl() }}" class="px-4 py-2 bg-white border border-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-green-600 hover:text-white hover:border-green-600 transition-all duration-300">
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
    @include('admin.reward.modal-delete')
    @include('admin.reward.modal-create')
    @include('admin.reward.modal-edit')
</div>
@endsection
