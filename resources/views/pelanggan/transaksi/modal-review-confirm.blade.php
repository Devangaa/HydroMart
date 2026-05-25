{{-- ============================================================================= --}}
{{-- FILE: pelanggan/transaksi/modal-review-confirm.blade.php --}}
{{-- HALAMAN: Modal Konfirmasi Ulasan --}}
{{-- DESKRIPSI: Konfirmasi kirim ulasan. --}}
{{-- ============================================================================= --}}
    <template x-teleport="body">
        <div x-show="showConfirmModal"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
             @click.self="showConfirmModal = false"
             style="display: none;">

            <div @click.stop
                 x-show="showConfirmModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center relative z-10">

                <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mb-2">Tambah Review?</h3>
                <p class="text-gray-500 text-sm mb-8 leading-relaxed">Yakin ingin menambah review? Ulasan yang sudah dikirim tidak dapat diubah kembali.</p>

                <div class="flex flex-col gap-3">
                    <form action="{{ route('ulasan.store') }}" method="POST" @submit="isSubmittingReview = true">
                        @csrf
                        <input type="hidden" name="id_detailtransaksi" :value="selectedItem.id">
                        <input type="hidden" name="type" :value="selectedItem.type">
                        <input type="hidden" name="rating" :value="rating">
                        <input type="hidden" name="komentar" :value="komentar">
                        <button type="submit"
                                :disabled="isSubmittingReview"
                                :class="isSubmittingReview ? 'opacity-70 cursor-not-allowed' : 'hover:bg-green-700 active:scale-95 shadow-lg shadow-green-100'"
                                class="w-full bg-green-600 text-white font-bold py-4 rounded-2xl transition-all flex items-center justify-center gap-2">
                            <svg x-show="isSubmittingReview" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" x-cloak>
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="isSubmittingReview ? 'Mengirim...' : 'Ya, Kirim'"></span>
                        </button>
                    </form>

                    <button @click="showConfirmModal = false; showReviewModal = false" type="button" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-4 rounded-2xl transition-all active:scale-95">
                        Tidak
                    </button>
                </div>
            </div>
        </div>
    </template>
