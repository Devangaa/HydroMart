<template x-teleport="body">
    <div x-show="showCreateModal" 
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
         style="display: none;" x-transition>
        
        <div @click.away="showCreateModal = false" class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto overflow-x-hidden relative text-left">
            <div class="sticky top-0 bg-white px-8 py-6 border-b border-gray-50 flex justify-between items-center z-10">
                <h1 class="text-2xl font-bold text-gray-900">Tambah Layanan Baru</h1>
                <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor font-bold"><path d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form action="{{ route('admin.layanan.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Foto Layanan</label>
                        <input type="file" name="foto_layanan" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 outline-none text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700">
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
                    <button type="submit" class="px-8 py-4 bg-green-600 text-white font-bold rounded-2xl shadow-lg shadow-green-100 hover:bg-green-700 transition-all">Simpan Layanan</button>
                </div>
            </form>
        </div>
    </div>
</template>