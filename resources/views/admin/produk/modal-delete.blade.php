<template x-teleport="body">
        <div x-show="showDeleteModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
             style="display: none;">
            
            <div @click.away="showDeleteModal = false" 
                 x-show="showDeleteModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center">
                
                <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Produk?</h3>
                <p class="text-gray-500 text-sm mb-8 leading-relaxed">Produk akan dipindahkan ke tab <b>Terhapus</b>.</p>

                <div class="flex flex-col gap-3">
                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')
                        {{-- Tombol Konfirmasi Hapus: Hapus class active:scale-95 --}}
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-red-100">
                            Ya, Hapus Produk
                        </button>
                    </form>
                    
                    {{-- Tombol Batal: Hapus class active:scale-95 --}}
                    <button @click="showDeleteModal = false" type="button" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-4 rounded-2xl transition-all">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </template>