<template x-teleport="body">
    <div x-show="showCreateModal" 
         class="fixed inset-0 z-[9999] flex items-start justify-center p-4 bg-black/40 backdrop-blur-sm overflow-y-auto py-10"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div @click.away="showCreateModal = false" 
            class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-3xl relative">
            
            <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Tambah Reward Baru</h1>
                <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.reward.store') }}" method="POST" class="p-8"
                x-data="{ isSubmitting: false }"
                @submit="isSubmitting = true">
                
                @csrf

                @if ($errors->any())
                    <div class="mb-8 bg-red-50 border border-red-100 rounded-2xl p-4 flex items-start gap-3 text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-bold text-sm">Gagal menambah reward:</p>
                            <ul class="list-disc list-inside text-xs mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="mb-8 bg-green-50 border border-green-100 rounded-2xl p-4 flex items-center gap-3 text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium">Pastikan data reward yang Anda masukkan sudah benar sebelum menyimpan.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Nama Reward <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_reward" value="{{ old('nama_reward') }}" required placeholder="cth. Diskon Belanja 10rb" 
                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm font-medium">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Potongan Diskon (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="diskon" value="{{ old('diskon') }}" required min="0" placeholder="0" 
                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm font-medium">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Min. Pembelian (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="minimal_pembelian" value="{{ old('minimal_pembelian', 0) }}" required min="0" placeholder="0" 
                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm font-medium">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Poin Diperlukan <span class="text-red-500">*</span></label>
                        <input type="number" name="poin_diperlukan" value="{{ old('poin_diperlukan') }}" required min="0" placeholder="0" 
                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm font-medium">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Durasi Berlaku (Hari) <span class="text-red-500">*</span></label>
                        <input type="number" name="durasi_reward" value="{{ old('durasi_reward') }}" required min="1" placeholder="cth. 30" 
                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm font-medium">
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat mengenai reward..." 
                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm font-medium resize-none">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>

                <div class="mt-10 flex justify-end gap-4">
                    <button type="button" @click="showCreateModal = false" class="px-8 py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl hover:bg-gray-200 transition-all text-sm">
                        Batal
                    </button>
                    <button type="submit" 
                        :disabled="isSubmitting"
                        class="px-8 py-4 bg-green-600 text-white font-bold rounded-2xl shadow-lg shadow-green-100 hover:bg-green-700 transition-all text-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        
                        <svg x-show="isSubmitting" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>

                        <span x-text="isSubmitting ? 'Sedang Menyimpan...' : 'Simpan Reward'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
