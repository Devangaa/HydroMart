{{-- ============================================================================= --}}
{{-- FILE: pelanggan/cart/modal-delete.blade.php --}}
{{-- HALAMAN: Modal Hapus Keranjang --}}
{{-- DESKRIPSI: Konfirmasi menghapus item dari keranjang belanja. --}}
{{-- ============================================================================= --}}

{{-- Modal: Konfirmasi Hapus --}}
<template x-teleport="body">
    <div x-show="showDeleteModal"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
         @click.self="showDeleteModal = false"
         style="display: none;">

        <div @click.stop
             x-show="showDeleteModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="relative z-10 bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center">

            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>

            <h3 class="text-xl font-black text-gray-900 mb-2">Hapus dari Keranjang?</h3>
            <p class="text-gray-500 text-sm mb-8 leading-relaxed font-medium">Produk ini akan dihapus dari daftar belanja Anda.</p>

            <div class="flex flex-col gap-3">
                <form :action="'{{ url('/keranjang/hapus') }}/' + cartIdToDelete" method="POST" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-4 bg-red-500 text-white font-black rounded-2xl hover:bg-red-600 transition shadow-lg shadow-red-100 active:scale-95">
                        Ya, Hapus Produk
                    </button>
                </form>
                <button @click="showDeleteModal = false" type="button" class="w-full py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl hover:bg-gray-200 transition active:scale-95">
                    Batal
                </button>
            </div>
        </div>
    </div>
</template>
