@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen bg-gray-50/50 pb-12">
    <div class="max-w-4xl mx-auto px-6">
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

        {{-- Header --}}
        <div class="mb-8">
            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">
                Koleksi Reward Anda
            </span>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mt-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Reward Saya</h1>
                    <p class="text-gray-500 text-sm mt-2 font-medium">Daftar voucher yang telah Anda klaim sebelumnya</p>
                </div>
                <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-2xl border border-gray-100 shadow-sm self-start">
                    <span class="text-gray-400 font-bold text-xs">Poin Anda:</span>
                    <span class="text-amber-600 text-sm font-black whitespace-nowrap">
                        {{ number_format(Auth::user()->poin_reward) }} Poin
                    </span>
                </div>
            </div>
        </div>

        {{-- Menu Tab --}}
        <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-gray-100 flex w-full mb-10">
            <a href="{{ route('reward.index') }}" 
               class="flex-1 py-3 rounded-xl text-sm font-bold transition-all duration-300 text-center text-gray-500 hover:text-gray-700">
                Tukar Poin
            </a>
            <a href="{{ route('reward.my-rewards') }}" 
               class="flex-1 py-3 rounded-xl text-sm font-bold transition-all duration-300 text-center bg-green-600 text-white shadow-md">
                Reward Saya
            </a>
        </div>

        {{-- Tabs --}}
        <div x-data="{ tab: 'active' }" class="space-y-8">
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
                    Kedaluwarsa ({{ $expiredRewards->count() }})
                </button>
            </div>

            {{-- Tab Contents --}}
            <div class="space-y-4">
                {{-- Active --}}
                <div x-show="tab === 'active'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                    @forelse($activeRewards as $item)
                        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm shadow-gray-200/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
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
                        <div class="bg-white rounded-[2.5rem] border-2 border-dashed border-gray-100 p-12 text-center">
                            <p class="text-gray-400 font-bold text-sm">Belum ada reward yang bisa digunakan.</p>
                            <a href="{{ route('reward.index') }}" class="text-green-600 font-black text-xs uppercase tracking-widest mt-4 inline-block hover:underline">Tukar Poin Sekarang</a>
                        </div>
                    @endforelse
                </div>

                {{-- Used --}}
                <div x-show="tab === 'used'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                    @forelse($usedRewards as $item)
                        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 opacity-75 grayscale">
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
                        <div class="bg-white rounded-[2.5rem] border-2 border-dashed border-gray-100 p-12 text-center">
                            <p class="text-gray-400 font-bold text-sm">Belum ada riwayat penggunaan reward.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Expired --}}
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
                        <div class="bg-white rounded-[2.5rem] border-2 border-dashed border-gray-100 p-12 text-center">
                            <p class="text-gray-400 font-bold text-sm">Tidak ada reward yang kedaluwarsa.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
