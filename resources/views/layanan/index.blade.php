@extends('layouts.app')

@section('title', 'Katalog Layanan Hidroponik')

@section('content')
<div class="w-full bg-gray-50/50 min-h-screen pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div data-aos="fade-right">
                <span class="inline-block px-4 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-full mb-4 uppercase">
                    Koleksi Layanan
                </span>
                <h1 class="text-4xl font-extrabold text-gray-900 leading-tight">
                    Pilih Layanan <span class="text-green-600">Favorit</span> Anda
                </h1>
                <p class="text-gray-500 mt-2 max-w-lg">Jelajahi berbagai pilihan layanan hidroponik terbaik untuk kebutuhan Anda.</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-[2rem] border border-gray-100 shadow-sm mb-10" data-aos="fade-up">
            <form action="{{ route('layanan.index') }}" method="GET" class="flex flex-col md:flex-row flex-wrap gap-4 items-start md:items-center">
                <div class="relative w-full md:flex-1 md:min-w-[300px]">
                    <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan" 
                        class="w-full pl-12 pr-12 py-3.5 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 transition outline-none text-sm">
                    
                    @if(request('search') || request('category'))
                        <a href="{{ route('layanan.index') }}" class="absolute inset-y-0 right-4 flex items-center text-red-500 hover:text-red-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div id="layanan-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($layanan as $item)
                @include('layanan.item-card', ['layanan' => $item])
            @empty
                <div data-aos="fade-up" class="col-span-full py-20 text-center">
                    <div class="bg-white rounded-3xl p-10 border border-dashed border-gray-200">
                        <p class="text-gray-400 font-bold">Maaf, layanan yang Anda cari tidak ditemukan.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div id="loading-trigger" class="py-8 text-center opacity-0 transition-opacity duration-300">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-green-600"></div>
            <p class="text-gray-400 text-xs font-bold mt-4 uppercase tracking-tighter">Memuat layanan lainnya...</p>
        </div>
    </div>
</div>

<script>
    let nextPageUrl = '{{ $layanan->nextPageUrl() }}';
    const container = document.querySelector('#layanan-container');
    const loading = document.querySelector('#loading-trigger');

    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && nextPageUrl) {
            loadMoreLayanan();
        }
    }, { threshold: 0.1 });

    observer.observe(loading);

    function loadMoreLayanan() {
        loading.style.opacity = '1';

        fetch(nextPageUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(data => {
            const parser = new DOMParser();
            const htmlDoc = parser.parseFromString(data, 'text/html');
            const newLayanan = htmlDoc.querySelector('#layanan-container').innerHTML;
            
            // Cari URL halaman berikutnya dari script yang di-render ulang
            const nextScript = htmlDoc.querySelector('#next-page-script');
            const newNextPageUrl = nextScript ? nextScript.getAttribute('data-url') : null;

            container.insertAdjacentHTML('beforeend', newLayanan);
            nextPageUrl = newNextPageUrl && newNextPageUrl !== '' ? newNextPageUrl : null;
            
            if (!nextPageUrl) loading.remove();
            loading.style.opacity = '0';
            
            AOS.refresh();
        })
        .catch(err => console.error(err));
    }
</script>

<div id="next-page-script" data-url="{{ $layanan->nextPageUrl() }}" class="hidden"></div>
@endsection