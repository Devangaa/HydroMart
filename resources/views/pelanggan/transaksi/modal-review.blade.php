{{-- ============================================================================= --}}
{{-- FILE: pelanggan/transaksi/modal-review.blade.php --}}
{{-- HALAMAN: Modal Form Ulasan --}}
{{-- DESKRIPSI: Form memberikan ulasan produk/layanan. --}}
{{-- ============================================================================= --}}
    <template x-teleport="body">
        <div x-show="showReviewModal"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9998] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
             @click.self="showReviewModal = false"
             style="display: none;">

            <div @click.stop
                 x-show="showReviewModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="bg-white rounded-[2.5rem] p-8 max-w-md w-full shadow-2xl relative z-10">

                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    Beri Ulasan
                </h3>

                <div class="mb-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Nama Produk/Layanan</p>
                    <p class="text-sm font-bold text-gray-900" x-text="selectedItem.name"></p>
                </div>

                <div class="mb-6">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Rating</p>
                    <div class="flex gap-2">
                        <template x-for="i in 5">
                            <button @click="rating = i" type="button" class="transition-all transform hover:scale-110 active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" :class="i <= rating ? 'text-yellow-400' : 'text-gray-200'" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="mb-8">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Komentar</p>
                    <textarea x-model="komentar" rows="3" class="w-full px-5 py-4 rounded-2xl border-gray-100 bg-gray-50 focus:bg-white focus:border-green-500 focus:ring-0 text-sm transition-all placeholder:text-gray-400" placeholder="Berikan pendapat Anda..."></textarea>
                </div>

                <div class="flex flex-col gap-3">
                    <button @click="showConfirmModal = true; showReviewModal = false" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-green-100 active:scale-95">
                        Kirim Ulasan
                    </button>
                    <button @click="showReviewModal = false" type="button" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-4 rounded-2xl transition-all active:scale-95">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </template>
