<a href="{{ route('layanan.show', $layanan->slug) }}" 
   class="group bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl transition duration-300 overflow-hidden flex flex-col h-full"
   data-aos="fade-up">
    
    <div class="relative aspect-square overflow-hidden bg-gray-100">
        <img src="{{ (is_array($layanan->foto_layanan) && count($layanan->foto_layanan) > 0) 
            ? asset('uploads/layanan/' . $layanan->foto_layanan[0]) 
            : 'https://ui-avatars.com/api/?name=' . urlencode($layanan->nama_layanan) }}" 
            alt="{{ $layanan->nama_layanan }}"
            class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
    </div>

    <div class="p-5 flex flex-col flex-grow">
        <div class="flex items-center gap-1.5 mb-2">
            <div class="flex items-center">
                <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            </div>
            <span class="text-[11px] font-black text-gray-700">4.8</span>
            <span class="text-[11px] font-medium text-gray-400">(120 ulasan)</span>
        </div>

        <h3 class="font-bold text-gray-900 text-sm md:text-base leading-tight group-hover:text-green-600 transition-colors line-clamp-2 mb-4">
            {{ $layanan->nama_layanan }}
        </h3>
        
        <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Harga</p>
                <p class="text-green-600 font-black text-sm md:text-base">
                    Rp{{ number_format($layanan->harga, 0, ',', '.') }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Stok</p>
                <p class="text-gray-900 font-black text-sm">
                    {{ $layanan->jumlah_stok }} <span class="text-[10px] text-gray-500 font-medium lowercase">{{ $layanan->unit }}</span>
                </p>
            </div>
        </div>
    </div>
</a>