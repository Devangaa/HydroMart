<template x-teleport="body">
    <div x-show="showEditModal" 
         class="fixed inset-0 z-[9999] flex items-start justify-center p-4 bg-black/40 backdrop-blur-sm overflow-y-auto py-10"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div @click.away="showEditModal = false" 
             class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-3xl relative">
            
            <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center text-gray-900">
                <h1 class="text-2xl font-bold">Edit Produk</h1>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form :action="editUrl" method="POST" enctype="multipart/form-data" class="p-8"
                x-data="{ 
                    newPhotos: [],
                    removedImages: [],
                    isSubmitting: false,
                    
                    handleFileSelect(event) {
                        const files = Array.from(event.target.files);
                        files.forEach(file => {
                            // Gunakan editData.foto_produk (asumsi variabel global Alpine Anda)
                            if ((editData.foto_produk.length - this.removedImages.length + this.newPhotos.length) < 4) {
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
                        event.target.value = '';
                    },

                    removeNewPhoto(index) {
                        this.newPhotos.splice(index, 1);
                    },

                    toggleExistingImage(img) {
                        if (this.removedImages.includes(img)) {
                            this.removedImages = this.removedImages.filter(i => i !== img);
                        } else {
                            this.removedImages.push(img);
                        }
                    }
                }"
                @submit="
                    isSubmitting = true;
                    const dataTransfer = new DataTransfer();
                    newPhotos.forEach(p => dataTransfer.items.add(p.file));
                    $refs.fileInput.files = dataTransfer.files;
                ">
                
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-8 bg-red-50 border border-red-100 rounded-2xl p-4 flex items-start gap-3 text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-bold text-sm">Gagal memperbarui data:</p>
                            <ul class="list-disc list-inside text-xs mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                    <div class="md:col-span-2 space-y-4">
                        <label class="text-sm font-bold text-gray-700 ml-1">Manajemen Foto Produk (Maks 4)</label>
                        
                        <div class="grid grid-cols-4 gap-4" x-show="editData.foto_produk && editData.foto_produk.length > 0">
                            <template x-for="(img, index) in editData.foto_produk" :key="'old-'+index">
                                <div class="relative group aspect-square rounded-2xl overflow-hidden border-2 transition-all"
                                    :class="removedImages.includes(img) ? 'border-red-500 opacity-50' : 'border-gray-200'">
                                    
                                    <img :src="'/uploads/produk/' + img" class="w-full h-full object-cover">
                                    
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/40 transition-all">
                                        <button type="button" @click="toggleExistingImage(img)" 
                                                class="p-2 rounded-xl shadow-lg transform hover:scale-110 transition"
                                                :class="removedImages.includes(img) ? 'bg-green-500 text-white' : 'bg-red-500 text-white'">
                                            
                                            <svg x-show="!removedImages.includes(img)" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <svg x-show="removedImages.includes(img)" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="checkbox" name="remove_images[]" :value="img" :checked="removedImages.includes(img)" class="hidden">
                                </div>
                            </template>
                        </div>

                        <div class="mt-4 space-y-3">
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider" x-show="newPhotos.length > 0">Foto Baru yang Akan Di-upload</span>
                            
                            <div class="grid grid-cols-4 gap-4">
                                <template x-for="(photo, index) in newPhotos" :key="'new-'+index">
                                    <div class="relative aspect-square rounded-2xl overflow-hidden border-2 border-green-500">
                                        <img :src="photo.preview" class="w-full h-full object-cover">
                                        <button type="button" @click="removeNewPhoto(index)" 
                                                class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-lg hover:bg-red-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>

                                <label x-show="(editData.foto_produk.length - removedImages.length + newPhotos.length) < 4" 
                                    class="aspect-square flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 group-hover:text-green-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span class="text-[10px] text-gray-400 mt-1 font-bold">Tambah</span>
                                    
                                    <input type="file" x-ref="fileInput" name="foto_produk[]" multiple accept="image/*" class="hidden" @change="handleFileSelect">
                                </label>
                            </div>
                        </div>
                        
                        <p class="text-[10px] text-gray-400 font-medium">
                            * Bingkai <span class="text-red-500">Merah</span>: Akan dihapus. Bingkai <span class="text-green-500">Hijau</span>: Foto baru.
                        </p>
                    </div>

                    <div class="space-y-2 text-left">
                        <label class="text-sm font-bold text-gray-700 ml-1">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_produk" x-model="editData.nama_produk" required 
                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm @error('nama_produk') ring-2 ring-red-500 @enderror">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori" x-model="editData.kategori" required class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm cursor-pointer">
                            @foreach(['Sayuran', 'Alat', 'Nutrisi', 'Bibit'] as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga" :value="Math.floor(editData.harga)" @input="editData.harga = $event.target.value" step="1" required 
                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm @error('harga') ring-2 ring-red-500 @enderror">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_stok" x-model="editData.jumlah_stok" required 
                            class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm @error('jumlah_stok') ring-2 ring-red-500 @enderror">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Unit <span class="text-red-500">*</span></label>
                        <select name="unit" x-model="editData.unit" required class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm cursor-pointer">
                            @foreach(['Ikat', 'Set', 'Pcs'] as $unit)
                                <option value="{{ $unit }}">{{ $unit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Berat (gram)</label>
                        <input type="number" name="berat" x-model="editData.berat" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm">
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" x-model="editData.deskripsi" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm resize-none"></textarea>
                    </div>
                </div>

                <div class="mt-10 flex justify-end gap-4">
                    <button type="button" @click="showEditModal = false" class="px-8 py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl hover:bg-gray-200 transition-all text-sm">
                        Batal
                    </button>
                    <button type="submit" 
                        :disabled="isSubmitting"
                        class="px-8 py-4 bg-green-600 text-white font-bold rounded-2xl shadow-lg shadow-green-100 hover:bg-green-700 transition-all text-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        
                        <svg x-show="isSubmitting" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>

                        <span x-text="isSubmitting ? 'Sedang Menyimpan...' : 'Simpan Perubahan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>