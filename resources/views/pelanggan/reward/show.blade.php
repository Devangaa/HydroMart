@extends('layouts.app')

{{-- ============================================================================= --}}
{{-- FILE: pelanggan/reward/show.blade.php --}}
{{-- HALAMAN: Detail Reward --}}
{{-- DESKRIPSI: Detail hadiah reward dan form penukaran poin. --}}
{{-- ============================================================================= --}}

@section('content')
{{-- Bagian: Kontainer Halaman --}}
<div class="w-full min-h-screen bg-gray-50/50 pb-12">
    <div class="max-w-2xl mx-auto px-4 pt-8">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('reward.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-50 transition text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="text-lg font-black text-gray-900">Detail Reward</h2>
            <div class="w-10"></div>
        </div>

        @php
            $canClaim = Auth::user()->poin_reward >= $reward->poin_diperlukan;
        @endphp

        <div class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-sm shadow-gray-200/50" data-aos="fade-up">
            <div class="p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 leading-tight">{{ $reward->nama_reward }}</h1>
                        <p class="text-green-600 font-bold mt-1">Diskon Rp {{ number_format($reward->diskon, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Poin Dibutuhkan</p>
                        <p class="text-xl font-black text-amber-600">{{ number_format($reward->poin_diperlukan) }} Poin</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Masa Berlaku</p>
                        <p class="text-xl font-black text-gray-900">{{ $reward->durasi_reward }} Hari</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Deskripsi</h4>
                        <div class="text-sm text-gray-600 font-medium leading-relaxed">
                            {{ $reward->deskripsi ?: 'Tidak ada deskripsi tambahan.' }}
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Syarat & Ketentuan</h4>
                        <ul class="space-y-3">
                            <li class="flex gap-3 text-sm text-gray-600 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Minimal pembelian Rp {{ number_format($reward->minimal_pembelian, 0, ',', '.') }}.
                            </li>
                            <li class="flex gap-3 text-sm text-gray-600 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Voucher berlaku selama {{ $reward->durasi_reward }} hari setelah diklaim.
                            </li>
                            <li class="flex gap-3 text-sm text-gray-600 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Poin yang sudah ditukarkan tidak dapat dikembalikan.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="p-8 bg-gray-50 border-t border-gray-100">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Poin Anda</span>
                        <span class="text-lg font-black text-gray-900">{{ number_format(Auth::user()->poin_reward) }} Poin</span>
                    </div>

                    @if($canClaim)
                        <button onclick="confirmClaim('{{ route('reward.claim', $reward->id) }}', '{{ $reward->nama_reward }}', '{{ $reward->poin_diperlukan }}')" 
                            class="px-10 py-4 bg-green-600 text-white font-black text-sm uppercase tracking-widest rounded-2xl shadow-lg shadow-green-100 hover:bg-green-700 transition-all">
                            KLAIM SEKARANG
                        </button>
                    @else
                        <button disabled class="px-10 py-4 bg-gray-200 text-gray-400 font-black text-sm uppercase tracking-widest rounded-2xl cursor-not-allowed">
                            POIN KURANG
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bagian: Modal Terkait --}}
@include('pelanggan.reward.modal-confirm')

{{-- Bagian: Skrip Arsip --}}
<!--
<script>
    function confirmClaim(url, name, poin) {
        const modal = document.getElementById('confirmModal');
        const content = modal.querySelector('.modal-content');
        const form = document.getElementById('claimForm');
        
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
-->
@endsection
