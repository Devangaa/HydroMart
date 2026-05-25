{{-- ============================================================================= --}}
{{-- FILE: admin/transaksi/modal-reply.blade.php --}}
{{-- HALAMAN: Modal Balas Ulasan --}}
{{-- DESKRIPSI: Form balasan ulasan pelanggan. --}}
{{-- ============================================================================= --}}
    <template x-teleport="body">
        <div x-show="showReplyModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
             style="display: none;">
            
            <div @click.away="showReplyModal = false" 
                 x-show="showReplyModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
                 class="bg-white rounded-[2.5rem] p-8 max-w-md w-full shadow-2xl">
                
                <h3 class="text-xl font-bold text-gray-900 mb-2">Balas Ulasan</h3>
                <p class="text-gray-500 text-xs mb-6">Memberikan tanggapan untuk ulasan <span class="font-bold text-green-600" x-text="replyTarget.name"></span></p>

                <div class="bg-gray-50 rounded-2xl p-4 mb-6 border border-gray-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Komentar Pelanggan</p>
                    <p class="text-xs text-gray-600" x-text="replyTarget.comment"></p>
                </div>

                <form :action="'{{ url('/kelola-transaksi/ulasan') }}/' + replyTarget.id + '/reply'" method="POST" id="replyForm" @submit.prevent="replyConfirmModal = true">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2 ml-1">Tulis Balasan Anda</label>
                            <textarea name="balasan" required rows="4" placeholder="Terima kasih telah berbelanja..." 
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm focus:border-green-500 focus:ring-0 transition-colors"></textarea>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 mt-8">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-green-100 active:scale-95">
                            Kirim Balasan
                        </button>
                        <button @click="showReplyModal = false" type="button" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-4 rounded-2xl transition-all active:scale-95">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
