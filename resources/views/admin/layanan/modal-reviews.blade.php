{{-- ============================================================================= --}}
{{-- FILE: admin/layanan/modal-reviews.blade.php --}}
{{-- HALAMAN: Modal Ulasan Layanan --}}
{{-- DESKRIPSI: Modal menampilkan ulasan pelanggan untuk layanan. --}}
{{-- ============================================================================= --}}

{{-- Modal: Daftar Ulasan Layanan --}}
<template x-teleport="body">
    <div
        x-show="showReviewModal"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm overflow-y-auto py-8"
        @click.self="showReviewModal = false"
        style="display: none;"
    >
        <div
            @click.stop
            x-show="showReviewModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
            class="relative z-10 w-full max-w-2xl bg-white shadow-2xl rounded-[2rem] p-6 my-auto"
        >
            {{-- Bagian: Header Modal --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-12 h-12 text-blue-600 bg-blue-50 rounded-2xl shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-gray-900">Ulasan Layanan</h3>
                        <p class="text-sm font-bold text-gray-400">Daftar ulasan dari pelanggan untuk layanan ini</p>
                    </div>
                </div>
                <button type="button" @click="showReviewModal = false" class="p-2 text-gray-400 transition-colors bg-gray-50 hover:bg-red-50 hover:text-red-500 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Bagian: Daftar Ulasan --}}
            <div class="overflow-y-auto max-h-[60vh] pr-2 space-y-4">
                <template x-if="!reviewData || reviewData.length === 0">
                    <div class="p-8 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                        <div class="mx-auto w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-bold">Belum ada ulasan untuk layanan ini.</p>
                    </div>
                </template>

                <template x-for="review in reviewData" :key="review.id">
                    <div class="p-5 bg-white border border-gray-100 rounded-2xl shadow-sm">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 overflow-hidden bg-gray-100 rounded-full shrink-0 flex items-center justify-center text-gray-400 font-bold text-sm">
                                    <span x-text="(review.user && review.user.nama_lengkap) ? review.user.nama_lengkap.charAt(0) : '?'"></span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900" x-text="review.user ? (review.user.nama_lengkap || review.user.username) : 'Pengguna Anonim'"></h4>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase" x-text="review.tanggal_ulasan ? new Date(review.tanggal_ulasan).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'}) : '-'"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-0.5 shrink-0">
                                <template x-for="i in 5" :key="i">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" :class="i <= review.rating ? 'text-amber-400' : 'text-gray-200'" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </template>
                            </div>
                        </div>
                        <p class="text-sm leading-relaxed text-gray-600" x-text="review.komentar || 'Tidak ada komentar.'"></p>

                        <template x-if="review.balasan">
                            <div class="p-3 mt-4 bg-gray-50 rounded-xl border border-gray-100">
                                <span class="text-[10px] font-black text-green-600 uppercase tracking-widest bg-green-100 px-2 py-0.5 rounded-md">Balasan Admin</span>
                                <p class="text-sm text-gray-600 mt-2" x-text="review.balasan"></p>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Bagian: Footer Modal --}}
            <div class="mt-6 text-right">
                <button type="button" @click="showReviewModal = false" class="px-6 py-3 text-sm font-bold text-gray-600 transition-all bg-gray-100 rounded-xl hover:bg-gray-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</template>
