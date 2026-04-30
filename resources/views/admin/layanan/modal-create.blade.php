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
                <h1 class="text-2xl font-bold text-gray-900">Tambah Layanan Baru</h1>
                <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.layanan.store') }}" method="POST" enctype="multipart/form-data" class="p-8"
                x-data="{ 
                    newPhotos: [],
                    isSubmitting: false,
                    handleFileSelect(event) {
                        const files = Array.from(event.target.files);
                        files.forEach(file => {
                            // Batasi maksimal 4 foto
                            if (this.newPhotos.length < 4) {
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    this.newPhotos.push({
                                        file: file,
                                        preview: e.target.result
                                    });
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                        // Reset input agar bisa memilih file yang sama jika diperlukan
                        event.target.value = '';
                    },
                    removePhoto(index) {
                        this.newPhotos.splice(index, 1);
                    }
                }"
                @submit="
                    isSubmitting = true;
                    const dataTransfer = new DataTransfer();
                    newPhotos.forEach(p => dataTransfer.items.add(p.file));
                    $refs.createFileInput.files = dataTransfer.files;
                ">

                @csrf

                @if ($errors->any())
                    <div class="mb-8 bg-red-50 border border-red-100 rounded-2xl p-4 flex items-start gap-3 text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-bold text-sm">Gagal menambah layanan:</p>
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
                    <p class="text-sm">Pastikan data yang Anda masukkan sudah benar sebelum menyimpan.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="md:col-span-2 space-y-4">
                        <label class="text-sm font-bold text-gray-700 ml-1">Foto Layanan (Maksimal 4)</label>
                        
                        <div class="grid grid-cols-4 gap-4">
                            <template x-for="(photo, index) in newPhotos" :key="index">
                                <div class="relative aspect-square rounded-2xl overflow-hidden border-2 border-green-500 shadow-sm animate-pulse-once">
                                    <img :src="photo.preview" class="w-full h-full object-cover">
                                    
                                    <button type="button" @click="removePhoto(index)" 
                                            class="absolute top-1 right-1 bg-red-500 text-white p-1.5 rounded-lg hover:bg-red-600 transition shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            <label x-show="newPhotos.length < 4" 
                                class="aspect-square flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 group-hover:text-green-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="text-[10px] text-gray-400 mt-1 font-bold">Tambah</span>
                                
                                <input type="file" 
                                    x-ref="createFileInput" 
                                    name="foto_layanan[]" 
                                    multiple 
                                    accept="image/*" 
                                    class="hidden" 
                                    @change="handleFileSelect">
                            </label>
                        </div>
                        
                        <p class="text-[10px] text-gray-400 font-medium ml-1">
                            * Foto berbingkai <span class="text-green-500 font-bold">Hijau</span> adalah foto yang akan di-upload.
                        </p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Nama Layanan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_layanan" value="{{ old('nama_layanan') }}" required class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 outline-none text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga" value="{{ old('harga') }}" required class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 outline-none text-sm">
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 outline-none text-sm resize-none">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>

                <div class="mt-10 flex justify-end gap-4">
                    <button type="button" @click="showCreateModal = false" class="px-8 py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl hover:bg-gray-200 transition-all">Batal</button>
                    <button type="submit" 
                        :disabled="isSubmitting"
                        class="px-8 py-4 bg-green-600 text-white font-bold rounded-2xl shadow-lg shadow-green-100 hover:bg-green-700 transition-all text-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        
                        <svg x-show="isSubmitting" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>

                        <span x-text="isSubmitting ? 'Sedang Menyimpan...' : 'Simpan Layanan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>