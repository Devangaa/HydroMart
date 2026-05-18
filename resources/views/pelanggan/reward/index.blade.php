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
                Reward Penukaran Poin
            </span>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mt-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tukar Reward</h1>
                    <p class="text-gray-500 text-sm mt-2 font-medium">Gunakan poin yang Anda kumpulkan untuk mendapatkan voucher menarik</p>
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
               class="flex-1 py-3 rounded-xl text-sm font-bold transition-all duration-300 text-center bg-green-600 text-white shadow-md">
                Tukar Poin
            </a>
            <a href="{{ route('reward.my-rewards') }}" 
               class="flex-1 py-3 rounded-xl text-sm font-bold transition-all duration-300 text-center text-gray-500 hover:text-gray-700">
                Reward Saya
            </a>
        </div>

        {{-- Rewards List --}}
        <div class="space-y-6">
            @php
                $gradients = [
                    'from-purple-300 via-blue-200 to-blue-300',
                    'from-amber-200 via-orange-200 to-red-300',
                    'from-cyan-200 via-blue-300 to-emerald-300',
                    'from-green-200 via-teal-200 to-blue-300',
                    'from-pink-200 via-rose-200 to-orange-300'
                ];
                $sidebarGradients = [
                    'bg-purple-400',
                    'bg-amber-400',
                    'bg-blue-400',
                    'bg-emerald-400',
                    'bg-pink-400'
                ];
            @endphp

            @forelse($rewards as $index => $reward)
                @php
                    $gradIndex = $index % count($gradients);
                    $canClaim = Auth::user()->poin_reward >= $reward->poin_diperlukan;
                @endphp
                <div class="relative group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="flex min-h-[130px] md:min-h-[160px] bg-gradient-to-r {{ $gradients[$gradIndex] }} rounded-3xl shadow-xl shadow-gray-200/50 overflow-hidden relative">
                        
                        {{-- Side Circle Cutouts --}}
                        <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-gray-50 rounded-full z-10 border-r border-gray-100/20"></div>
                        <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-gray-50 rounded-full z-10 border-l border-gray-100/20"></div>

                        {{-- Left Side Label --}}
                        <div class="{{ $sidebarGradients[$gradIndex] }} w-10 md:w-20 flex items-center justify-center relative overflow-hidden shrink-0">
                            <span class="rotate-180 [writing-mode:vertical-lr] text-white font-black uppercase tracking-[0.3em] text-[8px] md:text-xs opacity-80">PROMO</span>
                        </div>

                        {{-- Required Points Badge (Absolute top right on mobile) --}}
                        <div class="absolute top-3 right-3 px-3 py-1.5 bg-white/95 backdrop-blur-md rounded-2xl shadow-sm text-center z-10 md:hidden border border-white/40">
                            <p class="text-[7px] font-black text-gray-500 uppercase tracking-widest">Dibutuhkan</p>
                            <p class="text-sm font-black text-gray-900">{{ number_format($reward->poin_diperlukan) }} Poin</p>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 p-4 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 md:gap-6 relative z-0">
                            <div class="w-full md:flex-1 pr-24 md:pr-0">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-4xl md:text-5xl font-black text-white drop-shadow-sm whitespace-nowrap leading-none">
                                        @if($reward->diskon >= 1000)
                                            {{ number_format($reward->diskon / 1000, 0) }}k
                                        @else
                                            {{ number_format($reward->diskon) }}
                                        @endif
                                    </span>
                                    <span class="text-xl font-black text-white/90">OFF</span>
                                </div>
                                <p class="text-[10px] md:text-xs font-bold text-white/90 mt-2 line-clamp-2 leading-relaxed">
                                    {{ $reward->nama_reward }}.<br>
                                    Min. belanja Rp {{ number_format($reward->minimal_pembelian, 0, ',', '.') }}.
                                </p>
                                
                                {{-- Fake Barcode --}}
                                <div class="mt-3 md:mt-4 flex flex-col gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <div class="flex gap-[2px] h-4 md:h-6 items-end">
                                        @for($i=0; $i<25; $i++)
                                            <div class="bg-gray-800 rounded-full" style="width: {{ rand(1, 3) }}px; height: {{ rand(60, 100) }}%"></div>
                                        @endfor
                                    </div>
                                    <span class="text-[6px] md:text-[7px] font-mono tracking-[0.5em] text-gray-800 ml-1">{{ str_pad($reward->id, 10, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>

                            <div class="flex flex-col md:items-end justify-end gap-3 md:gap-4 w-full md:w-auto mt-2 md:mt-0">
                                {{-- Required Points Badge (Desktop) --}}
                                <div class="hidden md:block px-4 py-2 bg-white/95 backdrop-blur-md rounded-2xl shadow-sm border border-white/40 text-center">
                                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Dibutuhkan</p>
                                    <p class="text-lg font-black text-gray-900">{{ number_format($reward->poin_diperlukan) }} Poin</p>
                                </div>

                                <div class="flex gap-2 w-full md:w-auto">
                                    <a href="{{ route('reward.show', $reward->id) }}" class="flex-1 md:flex-none px-4 md:px-6 py-2 md:py-2.5 bg-white/20 hover:bg-white/30 text-white font-black text-[10px] uppercase tracking-widest rounded-xl transition-all border border-white/30 text-center shadow-sm">
                                        DETAIL
                                    </a>
                                    
                                    @if($canClaim)
                                        <button onclick="confirmClaim('{{ route('reward.claim', $reward->id) }}', '{{ $reward->nama_reward }}', '{{ $reward->poin_diperlukan }}')" 
                                            class="flex-1 md:flex-none px-4 md:px-6 py-2 md:py-2.5 bg-white text-gray-800 font-black text-[10px] uppercase tracking-widest rounded-xl shadow-lg hover:bg-gray-100 transition-all text-center">
                                            KLAIM
                                        </button>
                                    @else
                                        <button disabled class="flex-1 md:flex-none px-4 md:px-6 py-2 md:py-2.5 bg-gray-400/50 text-white/80 font-black text-[10px] uppercase tracking-widest rounded-xl cursor-not-allowed border border-white/20 text-center shadow-sm">
                                            KURANG
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[2.5rem] border-2 border-dashed border-gray-100 p-20 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Belum Ada Reward</h3>
                    <p class="text-gray-400 text-sm mt-1">Nantikan reward menarik lainnya segera!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Confirmation Modal --}}
<div id="confirmModal" class="fixed inset-0 z-[9999] hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] p-8 max-w-sm w-full shadow-2xl scale-95 opacity-0 transition-all duration-300 modal-content">
            <div class="w-20 h-20 bg-amber-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-amber-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-black text-gray-900 text-center mb-2">Konfirmasi Klaim</h3>
            <p class="text-gray-500 text-sm text-center mb-8">
                Yakin ingin menukarkan <span id="modalPoin" class="font-black text-amber-600"></span> poin untuk reward <span id="modalReward" class="font-black text-gray-900"></span>?
            </p>
            
            <div class="flex gap-4">
                <button onclick="closeModal()" class="flex-1 py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl hover:bg-gray-200 transition">
                    Batal
                </button>
                <form id="claimForm" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-4 bg-green-600 text-white font-bold rounded-2xl shadow-lg shadow-green-100 hover:bg-green-700 transition">
                        Ya, Klaim
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmClaim(url, name, poin) {
        const modal = document.getElementById('confirmModal');
        const content = modal.querySelector('.modal-content');
        const form = document.getElementById('claimForm');
        
        document.getElementById('modalReward').innerText = name;
        document.getElementById('modalPoin').innerText = numberFormat(poin);
        form.action = url;

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('confirmModal');
        const content = modal.querySelector('.modal-content');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function numberFormat(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
</script>
@endsection
