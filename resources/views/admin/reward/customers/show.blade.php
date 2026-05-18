@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen bg-gray-50/50 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.reward.customers') }}" class="text-sm font-bold text-gray-400 hover:text-green-600 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar Pelanggan
                    </a>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Profile Card --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-[2.5rem] border border-gray-100 p-8 shadow-sm text-center" data-aos="fade-right">
                    <div class="w-24 h-24 bg-gray-100 rounded-[2rem] mx-auto mb-4 overflow-hidden border-4 border-gray-50 shadow-sm flex items-center justify-center text-gray-400">
                        @if($customer->foto_profil)
                            <img src="{{ asset('uploads/profil/' . $customer->foto_profil) }}" class="w-full h-full object-cover">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        @endif
                    </div>
                    <h2 class="text-xl font-black text-gray-900">{{ $customer->nama_lengkap }}</h2>
                    <p class="text-gray-400 text-sm font-bold mt-1">@<span>{{ $customer->username }}</span></p>
                    
                    <div class="mt-8 pt-8 border-t border-gray-50">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Poin Reward</p>
                        <p class="text-4xl font-black text-amber-500">{{ number_format($customer->poin_reward) }}</p>
                        <p class="text-xs font-bold text-amber-600/60 mt-1 uppercase tracking-wider">Poin Tersedia</p>
                    </div>

                    <div class="mt-8 space-y-4 text-left">
                        <div class="flex justify-between items-center py-3 border-b border-gray-50 last:border-0">
                            <span class="text-xs font-bold text-gray-400 uppercase">Status</span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black rounded-lg uppercase">Member Aktif</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-50 last:border-0">
                            <span class="text-xs font-bold text-gray-400 uppercase">Bergabung Sejak</span>
                            <span class="text-xs font-black text-gray-900">{{ $customer->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reward Tabs --}}
            <div class="lg:col-span-2 space-y-6" x-data="{ tab: 'active' }">
                <div class="bg-white p-2 rounded-[1.5rem] border border-gray-100 shadow-sm flex flex-wrap sm:flex-nowrap gap-2" data-aos="fade-up">
                    <button @click="tab = 'active'" 
                            :class="tab === 'active' ? 'bg-green-600 text-white shadow-lg shadow-green-100' : 'text-gray-500 hover:bg-gray-50'"
                            class="flex-1 min-w-[120px] py-3 px-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">
                        Berlaku ({{ $activeRewards->count() }})
                    </button>
                    <button @click="tab = 'used'" 
                            :class="tab === 'used' ? 'bg-green-600 text-white shadow-lg shadow-green-100' : 'text-gray-500 hover:bg-gray-50'"
                            class="flex-1 min-w-[120px] py-3 px-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">
                        Ditukarkan ({{ $usedRewards->count() }})
                    </button>
                    <button @click="tab = 'expired'" 
                            :class="tab === 'expired' ? 'bg-green-600 text-white shadow-lg shadow-green-100' : 'text-gray-500 hover:bg-gray-50'"
                            class="flex-1 min-w-[120px] py-3 px-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">
                        Expired ({{ $expiredRewards->count() }})
                    </button>
                    <button @click="tab = 'history'" 
                            :class="tab === 'history' ? 'bg-green-600 text-white shadow-lg shadow-green-100' : 'text-gray-500 hover:bg-gray-50'"
                            class="flex-1 min-w-[120px] py-3 px-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">
                        Riwayat Poin
                    </button>
                </div>

                {{-- Riwayat Poin --}}
                <div x-show="tab === 'history'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                    <div class="bg-white rounded-[2rem] border border-gray-100 overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-[500px]">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Keterangan</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Jumlah Poin</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($customer->riwayatPoins->sortByDesc('created_at') as $history)
                                    <tr class="hover:bg-gray-50/30 transition">
                                        <td class="px-6 py-4 text-xs font-bold text-gray-500">
                                            {{ $history->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-xs font-bold text-gray-900">
                                            {{ $history->keterangan }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="font-black {{ $history->jumlah_poin >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                                {{ $history->jumlah_poin >= 0 ? '+' : '' }}{{ number_format($history->jumlah_poin) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-gray-400 font-bold">Belum ada riwayat poin.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Active Rewards --}}
                <div x-show="tab === 'active'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                    @forelse($activeRewards as $item)
                    <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-black text-gray-900 leading-tight">{{ $item->reward->nama_reward }}</h4>
                                <p class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-wider">Potongan Rp {{ number_format($item->reward->diskon, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Berlaku Hingga</p>
                            <p class="text-sm font-black text-green-600">{{ $item->batas_berlaku->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-3xl border-2 border-dashed border-gray-100 p-12 text-center">
                        <p class="text-gray-400 font-bold text-sm">Tidak ada reward yang sedang berlaku.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Used Rewards --}}
                <div x-show="tab === 'used'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                    @forelse($usedRewards as $item)
                    <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 opacity-75">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-black text-gray-900 leading-tight">{{ $item->reward->nama_reward }}</h4>
                                <p class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-wider">Potongan Rp {{ number_format($item->reward->diskon, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Digunakan Pada</p>
                            <p class="text-sm font-black text-gray-900">{{ $item->tanggal_penukaran ? $item->tanggal_penukaran->format('d M Y, H:i') : '-' }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-3xl border-2 border-dashed border-gray-100 p-12 text-center">
                        <p class="text-gray-400 font-bold text-sm">Belum ada reward yang digunakan.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Expired Rewards --}}
                <div x-show="tab === 'expired'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                    @forelse($expiredRewards as $item)
                    <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 opacity-50 grayscale">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-red-400 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-black text-gray-900 leading-tight">{{ $item->reward->nama_reward }}</h4>
                                <p class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-wider">Kedaluwarsa</p>
                            </div>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Batas Waktu</p>
                            <p class="text-sm font-black text-red-500">{{ $item->batas_berlaku->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-3xl border-2 border-dashed border-gray-100 p-12 text-center">
                        <p class="text-gray-400 font-bold text-sm">Tidak ada reward yang kedaluwarsa.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
