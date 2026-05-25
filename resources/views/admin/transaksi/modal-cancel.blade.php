{{-- ============================================================================= --}}
{{-- FILE: admin/transaksi/modal-cancel.blade.php --}}
{{-- HALAMAN: Modal Batalkan Pesanan --}}
{{-- DESKRIPSI: Konfirmasi pembatalan pesanan admin. --}}
{{-- ============================================================================= --}}
    <template x-teleport="body">
        <div x-show="showCancelModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
             style="display: none;">
            
            <div @click.away="showCancelModal = false" 
                 x-show="showCancelModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
                 class="bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center">
                
                <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mb-2">Batalkan Pesanan?</h3>
                <p class="text-gray-500 text-sm mb-8 leading-relaxed">Apakah anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.</p>

                <div class="flex flex-col gap-3">
                    <form action="{{ route('admin.transaksi.status', $transaksi->order_id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="Dibatalkan">
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-red-100 active:scale-95">
                            Ya, Batalkan Pesanan
                        </button>
                    </form>
                    
                    <button @click="showCancelModal = false" type="button" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-4 rounded-2xl transition-all active:scale-95">
                        Kembali
                    </button>
                </div>
            </div>
        </div>
    </template>
