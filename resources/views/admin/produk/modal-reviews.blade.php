<div x-show="showReviewModal" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        
        <div x-show="showReviewModal" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0" 
            class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" 
            @click="showReviewModal = false"
            aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="showReviewModal" 
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave="ease-in duration-200" 
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
            class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[2rem]">
            
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-12 h-12 text-blue-600 bg-blue-50 rounded-2xl shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-gray-900">Ulasan Produk</h3>
                        <p class="text-sm font-bold text-gray-400">Daftar ulasan dari pelanggan untuk produk ini</p>
                    </div>
                </div>
                <button @click="showReviewModal = false" class="p-2 text-gray-400 transition-colors bg-gray-50 hover:bg-red-50 hover:text-red-500 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="overflow-y-auto max-h-[60vh] pr-2 space-y-4">
                <template x-if="reviewData.length === 0">
                    <div class="p-8 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                        <div class="mx-auto w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-bold">Belum ada ulasan untuk produk ini.</p>
                    </div>
                </template>

                <template x-for="review in reviewData" :key="review.id">
                    <div class="p-5 bg-white border border-gray-100 rounded-2xl shadow-sm">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 overflow-hidden bg-gray-100 rounded-full shrink-0 flex items-center justify-center text-gray-400">
                                    <template x-if="review.user && review.user.foto_profil">
                                        <img :src="'/uploads/profil/' + review.user.foto_profil" class="object-cover w-full h-full">
                                    </template>
                                    <template x-if="!review.user || !review.user.foto_profil">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                        </svg>
                                    </template>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900" x-text="review.user ? review.user.username : 'Pengguna Anonim'"></h4>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase" x-text="new Date(review.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <template x-for="i in 5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" :class="i <= review.rating ? 'text-amber-400' : 'text-gray-200'" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </template>
                            </div>
                        </div>
                        <p class="text-sm leading-relaxed text-gray-600" x-text="review.komentar || 'Tidak ada komentar.'"></p>
                        
                        <template x-if="review.balasan">
                            <div class="p-3 mt-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-green-600 uppercase tracking-widest bg-green-100 px-2 py-0.5 rounded-md">Balasan Admin</span>
                                </div>
                                <p class="text-sm text-gray-600" x-text="review.balasan"></p>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
            
            <div class="mt-6 text-right">
                <button @click="showReviewModal = false" type="button" class="px-6 py-3 text-sm font-bold text-gray-600 transition-all bg-gray-100 rounded-xl hover:bg-gray-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
