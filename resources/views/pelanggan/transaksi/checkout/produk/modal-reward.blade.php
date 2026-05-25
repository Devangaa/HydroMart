{{-- ============================================================================= --}}
{{-- FILE: pelanggan/transaksi/checkout/produk/modal-reward.blade.php --}}
{{-- HALAMAN: Modal Pilih Reward --}}
{{-- DESKRIPSI: Modal memilih reward saat checkout produk. --}}
{{-- ============================================================================= --}}

{{-- Modal: Pilih Reward --}}
<template x-teleport="body">
    <div
        x-show="showRewardModal"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm overflow-y-auto py-8"
        @click.self="showRewardModal = false"
        style="display: none;"
    >
        <div
            @click.stop
            x-show="showRewardModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
            class="relative z-10 w-full max-w-lg overflow-hidden bg-white shadow-2xl rounded-[2.5rem] my-auto"
        >
            <div class="p-8 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-xl font-black text-gray-900">Pilih Reward</h3>
                    <p class="text-xs text-gray-400 font-bold mt-1">Gunakan reward yang Anda miliki</p>
                </div>
                <button type="button" @click="showRewardModal = false" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-8 max-h-[60vh] overflow-y-auto space-y-8 custom-scrollbar">
                <div>
                    <h4 class="text-[10px] font-black text-green-600 uppercase tracking-[0.2em] mb-4">Tersedia</h4>
                    <div class="space-y-3">
                        <template x-for="reward in availableRewards.filter(r => subtotal >= r.reward.minimal_pembelian)" :key="reward.id">
                            <button type="button" @click="selectReward(reward)"
                                    class="w-full flex items-center gap-4 p-4 rounded-2xl border border-gray-100 hover:border-green-500 hover:bg-green-50/30 transition-all text-left group">
                                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-black text-gray-900" x-text="reward.reward.nama_reward"></p>
                                    <p class="text-xs font-bold text-green-600">Potongan Rp<span x-text="formatCurrency(reward.reward.diskon)"></span></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mb-0.5">Berlaku s/d</p>
                                    <p class="text-[10px] font-black text-gray-600" x-text="new Date(reward.batas_berlaku).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})"></p>
                                </div>
                            </button>
                        </template>
                        <template x-if="availableRewards.filter(r => subtotal >= r.reward.minimal_pembelian).length === 0">
                            <p class="text-center py-4 text-xs text-gray-400 font-medium italic">Tidak ada reward yang memenuhi syarat minimal belanja</p>
                        </template>
                    </div>
                </div>

                <div x-show="availableRewards.filter(r => subtotal < r.reward.minimal_pembelian).length > 0">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Belum Memenuhi Syarat</h4>
                    <div class="space-y-3">
                        <template x-for="reward in availableRewards.filter(r => subtotal < r.reward.minimal_pembelian)" :key="reward.id">
                            <div class="w-full flex items-center gap-4 p-4 rounded-2xl border border-gray-100 bg-gray-50/50 opacity-60 filter grayscale text-left">
                                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-black text-gray-900" x-text="reward.reward.nama_reward"></p>
                                    <p class="text-xs font-bold text-red-500">Min. belanja Rp<span x-text="formatCurrency(reward.reward.minimal_pembelian)"></span></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] font-black text-red-400 uppercase">Kurang Rp<span x-text="formatCurrency(reward.reward.minimal_pembelian - subtotal)"></span></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="p-8 bg-gray-50 border-t border-gray-100">
                <button type="button" @click="showRewardModal = false" class="w-full py-4 bg-white border border-gray-200 text-gray-600 font-bold rounded-2xl hover:bg-gray-100 transition shadow-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</template>
