@extends('layouts.app')

@section('title', 'Pembayaran #' . $transaksi->order_id)

@section('content')
<div class="w-full min-h-screen bg-gray-50/50 pb-20">
    <div class="max-w-5xl mx-auto px-4 pt-12">
        
        {{-- Header & Status --}}
        <div class="text-center mb-8" data-aos="fade-down">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Menunggu Pembayaran</h1>
            <p class="text-gray-500 mt-2 font-medium">Segera selesaikan pembayaran sebelum batas waktu berakhir</p>
        </div>

        {{-- Countdown Card --}}
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8 mb-6 text-center" data-aos="fade-up">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Batas Waktu Pembayaran</p>
            <div class="flex justify-center items-center gap-4">
                <div class="text-center">
                    <span class="text-4xl font-black text-green-600 tabular-nums" id="countdown">-- : -- : --</span>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4 font-semibold">
                Jatuh tempo: <span class="text-gray-900">{{ \Carbon\Carbon::parse($transaksi->batas_pembayaran)->locale('id')->translatedFormat('d F Y, H:i') }} WIB</span>
            </p>
        </div>

        {{-- Payment Info Card --}}
        <div class="bg-white rounded-[2rem] shadow-xl shadow-green-900/5 border border-gray-100 overflow-hidden mb-6" data-aos="fade-up" data-aos-delay="100">
            <div class="p-8 border-b border-gray-50 bg-gray-50/30">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Metode Pembayaran</p>
                        <h3 class="text-xl font-black text-gray-900 uppercase">
                            @if($transaksi->metode_pembayaran == 'bca') BCA Virtual Account
                            @elseif($transaksi->metode_pembayaran == 'mandiri') Mandiri Bill
                            @elseif($transaksi->metode_pembayaran == 'qris') QRIS
                            @else {{ strtoupper($transaksi->metode_pembayaran) }}
                            @endif
                        </h3>
                    </div>
                    @if($transaksi->metode_pembayaran == 'bca')
                        <img src="{{ asset('img/logos/bca.png') }}" class="h-20" alt="BCA">
                    @elseif($transaksi->metode_pembayaran == 'mandiri')
                        <img src="{{ asset('img/logos/mandiri.png') }}" class="h-20" alt="Mandiri">
                    @elseif($transaksi->metode_pembayaran == 'qris')
                        <img src="{{ asset('img/logos/qris.png') }}" class="h-9" alt="QRIS">
                    @endif
                </div>
            </div>

            <div class="p-8 text-center">
                @if($transaksi->metode_pembayaran == 'qris')
                    <div class="bg-white p-4 inline-block rounded-3xl border-4 border-gray-50 shadow-inner mb-6">
                        <img id="qris-image" src="{{ $transaksi->kode_pembayaran }}" alt="QRIS Code" class="w-full max-w-[256px] aspect-square object-contain mx-auto rounded-xl border border-gray-100 p-2 bg-white">
                    </div>
                    <div class="flex flex-col items-center gap-4 mb-6">
                        <a href="{{ $transaksi->kode_pembayaran }}" download="QRIS_HydroMart_{{ $transaksi->order_id }}.png" target="_blank" class="flex items-center gap-2 px-6 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download QR Code
                        </a>
                        <p class="text-sm text-gray-500 font-medium max-w-xs mx-auto text-center">Scan kode QR di atas menggunakan aplikasi mobile banking atau e-wallet pilihan Anda.</p>
                    </div>
                @else
                    @php
                        $isMandiri = $transaksi->metode_pembayaran == 'mandiri';
                        $paymentData = $isMandiri ? json_decode($transaksi->kode_pembayaran) : null;
                    @endphp

                    @if($isMandiri)
                        <div class="space-y-6">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-2">Kode Perusahaan (Biller Code)</p>
                                <div class="flex items-center justify-center gap-2 md:gap-3 flex-wrap">
                                    <span class="text-xl md:text-3xl font-black text-gray-900 tracking-wider md:tracking-widest break-all">{{ $paymentData->biller_code }}</span>
                                    <button onclick="copyToClipboard('{{ $paymentData->biller_code }}')" class="p-2 text-green-600 hover:bg-green-50 rounded-xl transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-6a2 2 0 00-2 2z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-2">Nomor Virtual Account (Bill Key)</p>
                                <div class="flex items-center justify-center gap-2 md:gap-3 flex-wrap">
                                    <span class="text-xl md:text-3xl font-black text-gray-900 tracking-wider md:tracking-widest break-all">{{ $paymentData->bill_key }}</span>
                                    <button onclick="copyToClipboard('{{ $paymentData->bill_key }}')" class="p-2 text-green-600 hover:bg-green-50 rounded-xl transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-6a2 2 0 00-2 2z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase mb-2">Nomor Virtual Account</p>
                            <div class="flex items-center justify-center gap-2 md:gap-3 flex-wrap">
                                <span class="text-xl sm:text-2xl md:text-4xl font-black text-gray-900 tracking-wider md:tracking-widest break-all">{{ $transaksi->kode_pembayaran }}</span>
                                <button onclick="copyToClipboard('{{ $transaksi->kode_pembayaran }}')" class="p-2 text-green-600 hover:bg-green-50 rounded-xl transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-6a2 2 0 00-2 2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <div class="p-8 bg-green-600 flex justify-between items-center">
                <span class="text-white font-bold opacity-80 uppercase tracking-widest text-xs">Total Pembayaran</span>
                <span class="text-2xl font-black text-white">Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Instructions --}}
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8" data-aos="fade-up" data-aos-delay="200">
            <h4 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Instruksi Pembayaran
            </h4>

            <div x-data="{ active: 1 }" class="space-y-4">
                @if($transaksi->metode_pembayaran == 'bca')
                    <div class="border border-gray-100 rounded-2xl overflow-hidden">
                        <button @click="active = (active === 1 ? 0 : 1)" class="w-full px-6 py-4 flex items-center justify-between font-bold text-gray-700 bg-gray-50/50">
                            <span>M-Banking BCA</span>
                            <svg :class="active === 1 ? 'rotate-180' : ''" class="h-5 w-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="active === 1" class="px-6 py-4 text-sm text-gray-500 space-y-3 leading-relaxed">
                            <p>1. Login ke aplikasi m-BCA</p>
                            <p>2. Pilih menu <strong>m-Transfer</strong></p>
                            <p>3. Pilih <strong>BCA Virtual Account</strong></p>
                            <p>4. Masukkan nomor VA <strong>{{ $transaksi->kode_pembayaran }}</strong></p>
                            <p>5. Masukkan jumlah yang harus dibayar</p>
                            <p>6. Periksa detail transaksi dan masukkan PIN Anda</p>
                        </div>
                    </div>
                    <div class="border border-gray-100 rounded-2xl overflow-hidden">
                        <button @click="active = (active === 2 ? 0 : 2)" class="w-full px-6 py-4 flex items-center justify-between font-bold text-gray-700 bg-gray-50/50">
                            <span>ATM BCA</span>
                            <svg :class="active === 2 ? 'rotate-180' : ''" class="h-5 w-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="active === 2" class="px-6 py-4 text-sm text-gray-500 space-y-3 leading-relaxed">
                            <p>1. Masukkan kartu ATM dan PIN</p>
                            <p>2. Pilih menu <strong>Transaksi Lainnya</strong></p>
                            <p>3. Pilih <strong>Transfer</strong> lalu <strong>Ke Rek BCA Virtual Account</strong></p>
                            <p>4. Masukkan nomor VA <strong>{{ $transaksi->kode_pembayaran }}</strong></p>
                            <p>5. Konfirmasi data dan tekan <strong>Ya</strong></p>
                        </div>
                    </div>
                @elseif($transaksi->metode_pembayaran == 'mandiri')
                    <div class="border border-gray-100 rounded-2xl overflow-hidden">
                        <button @click="active = (active === 1 ? 0 : 1)" class="w-full px-6 py-4 flex items-center justify-between font-bold text-gray-700 bg-gray-50/50">
                            <span>Livin' by Mandiri</span>
                            <svg :class="active === 1 ? 'rotate-180' : ''" class="h-5 w-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="active === 1" class="px-6 py-4 text-sm text-gray-500 space-y-3 leading-relaxed">
                            @php $paymentData = json_decode($transaksi->kode_pembayaran); @endphp
                            <p>1. Login ke aplikasi Livin' by Mandiri</p>
                            <p>2. Pilih menu <strong>Bayar</strong></p>
                            <p>3. Ketik <strong>Midtrans</strong> atau kode <strong>{{ $paymentData->biller_code }}</strong></p>
                            <p>4. Masukkan Bill Key <strong>{{ $paymentData->bill_key }}</strong></p>
                            <p>5. Konfirmasi pembayaran dan masukkan PIN</p>
                        </div>
                    </div>
                @elseif($transaksi->metode_pembayaran == 'qris')
                    <div class="border border-gray-100 rounded-2xl overflow-hidden">
                        <button @click="active = (active === 1 ? 0 : 1)" class="w-full px-6 py-4 flex items-center justify-between font-bold text-gray-700 bg-gray-50/50">
                            <span>Aplikasi M-Banking / E-Wallet</span>
                            <svg :class="active === 1 ? 'rotate-180' : ''" class="h-5 w-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="active === 1" class="px-6 py-4 text-sm text-gray-500 space-y-3 leading-relaxed">
                            <p>1. Buka aplikasi e-wallet (Gopay, OVO, Dana, LinkAja) atau mobile banking Anda</p>
                            <p>2. Pilih fitur <strong>Scan QR</strong> atau <strong>Bayar</strong></p>
                            <p>3. Arahkan kamera ke kode QR yang muncul di atas</p>
                            <p>4. Konfirmasi jumlah pembayaran yang tertera</p>
                            <p>5. Masukkan PIN untuk menyelesaikan pembayaran</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-10 flex flex-col md:flex-row gap-4">
                <a href="{{ route('transaksi.history') }}" class="flex-1 px-8 py-4 bg-green-600 text-white font-black rounded-2xl text-center shadow-lg shadow-green-100 hover:bg-green-700 transition">
                    Cek Status Pesanan
                </a>
                <a href="{{ route('produk.index') }}" class="flex-1 px-8 py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl text-center hover:bg-gray-200 transition">
                    Kembali Belanja
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Countdown logic
    const expiryTime = new Date("{{ $transaksi->batas_pembayaran }}").getTime();
    
    const x = setInterval(function() {
        const now = new Date().getTime();
        const distance = expiryTime - now;

        if (distance < 0) {
            clearInterval(x);
            document.getElementById("countdown").innerHTML = "EXPIRED";
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("countdown").innerHTML = 
            (hours < 10 ? "0" + hours : hours) + " : " + 
            (minutes < 10 ? "0" + minutes : minutes) + " : " + 
            (seconds < 10 ? "0" + seconds : seconds);
    }, 1000);

    // Polling status pembayaran
    const statusCheckInterval = setInterval(function() {
        fetch("{{ route('transaksi.status-check', $transaksi->order_id) }}")
            .then(response => response.json())
            .then(data => {
                if (data.is_processed) {
                    clearInterval(statusCheckInterval);
                    window.location.href = data.redirect_url;
                }
            })
            .catch(error => console.error('Error checking status:', error));
    }, 5000); // Cek setiap 5 detik

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Kode berhasil disalin!');
        });
    }
</script>
@endsection
