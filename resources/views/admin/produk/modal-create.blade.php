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
                <h1 class="text-2xl font-bold text-gray-900">Tambah Produk Baru</h1>
                <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                
                @if ($errors->any())
                <div class="mb-8 bg-red-50 border border-red-100 rounded-2xl p-4 flex flex-col gap-2 text-red-700">
                    <div class="flex items-center gap-3 font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Terdapat kesalahan input:
                    </div>
                    <ul class="list-disc list-inside text-xs ml-8">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="mb-8 bg-green-50 border border-green-100 rounded-2xl p-4 flex items-center gap-3 text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm">Pastikan data yang Anda masukkan sudah benar sebelum menyimpan.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Foto Produk</label>
                        <input type="file" name="foto_produk" value="{{ old('foto_produk') }}" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_produk" value="{{ old('nama_produk') }}"placeholder="cth. Selada Romaine" required class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori" required class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm cursor-pointer">
                            @foreach(['Sayuran', 'Alat', 'Nutrisi', 'Bibit'] as $cat)
                                <option value="{{ $cat }}" {{ old('kategori') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga" value="{{ old('harga') }}" placeholder="cth. 15000" required class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_stok" value="{{ old('jumlah_stok') }}" placeholder="cth. 50" required class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Unit <span class="text-red-500">*</span></label>
                        <select name="unit" required class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm cursor-pointer">
                            @foreach(['Ikat', 'Set', 'Pcs'] as $unit)
                                <option value="{{ $unit }}" {{ old('unit') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Berat (gram)</label>
                        <input type="number" name="berat" value="{{ old('berat') }}" placeholder="cth. 250" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm">
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat..." class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm resize-none"></textarea>
                    </div>
                </div>

                <div class="mt-10 flex justify-end gap-4">
                    <button type="button" @click="showCreateModal = false" class="px-8 py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl hover:bg-gray-200 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-8 py-4 bg-green-600 text-white font-bold rounded-2xl shadow-lg shadow-green-100 hover:bg-green-700 transition-all">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>