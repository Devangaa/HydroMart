@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
{{-- Container utama dibuat sama dengan login untuk centering --}}
<div class="w-full">
    <div class="max-w-4xl mx-auto px-6">
<div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-gray-100 w-full max-w-md mx-auto" x-data="forgotPasswordHandler()">
    
    {{-- Logo HydroMart Asli --}}
    <div class="flex justify-center mb-6">
        <img src="{{ asset('img/logo-hydro2.ico') }}" 
                alt="Logo HydroMart" 
                class="w-20 h-20 object-contain">
    </div>

    <h2 class="text-2xl font-bold text-center text-gray-900 mb-1">Lupa Password</h2>
    <p class="text-center text-gray-400 text-sm mb-8">Harap isi data dibawah ini</p>

    {{-- Form Step 1: Input Email --}}
    <div x-show="step === 1" x-transition>
        <div class="mb-5">
            <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
            <input type="email" 
                   x-model="email"
                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent focus:outline-none transition placeholder-gray-300"
                   placeholder="Masukkan email anda">
        </div>

        <div class="flex flex-col items-end mb-6">
            <a href="{{ route('login') }}" class="text-[11px] text-green-600 font-bold hover:underline mb-2">Kembali ke login</a>
            
            {{-- Error Message gaya Login --}}
            <template x-if="error">
                <div class="flex items-center gap-1 text-red-500 text-[11px] w-full italic">
                    <span class="not-italic">ⓘ</span> <span x-text="error"></span>
                </div>
            </template>
        </div>

        <button @click="sendOtp" 
                :disabled="loading"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition duration-300 transform active:scale-[0.98] shadow-lg shadow-green-100 flex justify-center items-center gap-2">
            <span x-show="!loading">Kirim OTP</span>
            <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </button>
    </div>

    {{-- Form Step 2: Input OTP --}}
    <div x-show="step === 2" x-cloak x-transition.opacity>
        <p class="text-[11px] text-gray-400 text-center mb-5 italic">ⓘ Link verifikasi sudah dikirim ke email anda</p>
        
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase text-center tracking-widest">Kode OTP</label>
            <input type="text" 
                   x-model="otp"
                   maxlength="6"
                   class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent focus:outline-none transition text-center tracking-[0.5em] font-bold text-2xl"
                   placeholder="000000">
        </div>

        <button @click="verifyOtp" 
                :disabled="loading"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-green-100">
            Verifikasi Kode
        </button>
        
        <button @click="step = 1; error = ''" class="w-full mt-4 text-[11px] text-gray-400 font-bold hover:text-green-600 transition">Ganti Email</button>
    </div>
</div>

<script>
    function forgotPasswordHandler() {
        return {
            step: 1, 
            email: '', 
            otp: '', 
            loading: false, 
            error: '',
            
            async sendOtp() {
                if(!this.email) { 
                    this.error = 'Email wajib diisi!'; 
                    return; 
                }
                
                this.loading = true; 
                this.error = '';
                
                try {
                    let response = await fetch('/forgot-password/send-otp', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'Accept': 'application/json', // TAMBAHKAN INI agar Laravel kirim JSON
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                        },
                        body: JSON.stringify({ email: this.email })
                    });
                    
                    let data = await response.json();
                    
                    if(response.ok) { 
                        this.step = 2; 
                    } else { 
                        this.error = data.message || 'Gagal mengirim OTP'; 
                    }
                } catch (e) { 
                    this.error = 'Koneksi bermasalah, silakan coba lagi.';
                    console.error(e);
                }
                this.loading = false;
            },

            async verifyOtp() {
                if(this.otp.length < 6) { 
                    this.error = 'Masukkan 6 digit kode!'; 
                    return; 
                }
                
                this.loading = true; 
                this.error = '';
                
                try {
                    let response = await fetch('/forgot-password/verify-otp', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'Accept': 'application/json', // TAMBAHKAN INI
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                        },
                        body: JSON.stringify({ email: this.email, otp: this.otp })
                    });
                    
                    let data = await response.json();
                    
                    if(response.ok) { 
                        window.location.href = '{{ route("password.reset") }}'; 
                    } else { 
                        this.error = data.message || 'Kode OTP salah.'; 
                    }
                } catch (e) { 
                    this.error = 'Terjadi kesalahan saat verifikasi.'; 
                }
                this.loading = false;
            }
        }
    }
</script>
    </div>
</div>
@endsection