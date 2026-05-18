@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen bg-gray-50/50 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.reward.index') }}" class="text-sm font-bold text-gray-400 hover:text-green-600 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Kelola Reward
                    </a>
                </li>
            </ol>
        </nav>

        <div data-aos="fade-right">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">
                        Monitoring Pelanggan
                    </span>
                    <h1 class="text-3xl font-extrabold text-gray-900 mt-2">Data Reward Pelanggan</h1>
                    <p class="text-gray-500 text-sm">Lihat poin dan status penukaran reward setiap pelanggan</p>
                </div>
            </div>
        </div>

        <div data-aos="fade-up">
            <div class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm mb-6">
                <form action="{{ route('admin.reward.customers') }}" method="GET" class="relative">
                    <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, username, atau email pelanggan..." 
                        class="w-full pl-12 pr-12 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm">
                </form>
            </div>

            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Pelanggan</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Poin Saat Ini</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Total Penukaran</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($customers as $customer)
                            <tr class="hover:bg-gray-50/30 transition group cursor-pointer" onclick="window.location='{{ route('admin.reward.customer-show', $customer->id) }}'">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 rounded-xl overflow-hidden shrink-0 flex items-center justify-center text-gray-400">
                                            @if($customer->foto_profil)
                                                <img src="{{ asset('uploads/profil/' . $customer->foto_profil) }}" class="w-full h-full object-cover">
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm leading-tight group-hover:text-green-600 transition-colors">{{ $customer->nama_lengkap }}</p>
                                            <p class="text-[10px] text-gray-400 mt-0.5">@<span>{{ $customer->username }}</span> • {{ $customer->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black rounded-lg uppercase whitespace-nowrap">
                                        {{ number_format($customer->poin_reward) }} Poin
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-gray-900">{{ $customer->penukaranRewards->count() }} Kali</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center">
                                        <a href="{{ route('admin.reward.customer-show', $customer->id) }}" class="p-2 bg-gray-50 text-gray-400 group-hover:bg-green-50 group-hover:text-green-600 rounded-xl transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-bold">Tidak ada data pelanggan ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="p-6 bg-gray-50/50 flex justify-between items-center">
                    <p class="text-xs font-bold text-gray-400">
                        Menampilkan {{ $customers->firstItem() ?? 0 }}-{{ $customers->lastItem() ?? 0 }} dari {{ $customers->total() }} pelanggan
                    </p>
                    
                    <div class="flex gap-1">
                        @if ($customers->onFirstPage())
                            <span class="px-4 py-2 bg-white border border-gray-100 text-gray-300 text-xs font-bold rounded-xl cursor-not-allowed">
                                <
                            </span>
                        @else
                            <a href="{{ $customers->previousPageUrl() }}" class="px-4 py-2 bg-white border border-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-green-600 hover:text-white hover:border-green-600 transition-all duration-300">
                                <
                            </a>
                        @endif

                        @foreach ($customers->getUrlRange(max(1, $customers->currentPage() - 1), min($customers->lastPage(), $customers->currentPage() + 1)) as $page => $url)
                            @if ($page == $customers->currentPage())
                                <span class="px-4 py-2 bg-green-600 text-white text-xs font-bold rounded-xl shadow-sm shadow-green-100">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 bg-white border border-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-gray-50 transition-all">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if ($customers->hasMorePages())
                            <a href="{{ $customers->nextPageUrl() }}" class="px-4 py-2 bg-white border border-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-green-600 hover:text-white hover:border-green-600 transition-all duration-300">
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
@endsection
