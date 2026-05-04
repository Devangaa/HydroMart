@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="w-full">
    <div class="max-w-4xl mx-auto px-6">
<div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-gray-100 w-full max-w-md mx-auto" x-data="resetPasswordHandler()">
    
    <div class="flex justify-center mb-6">
        <img src="{{ asset('img/logo-hydro2.ico') }}" alt="Logo HydroMart" class="w-20 h-20 object-contain">
    </div>

    <h2 class="text-2xl font-bold text-center text-gray-900 mb-1">Password Baru</h2>
    <p class="text-center text-gray-400 text-sm mb-8">Silahkan masukkan password baru anda</p>

    <div class="space-y-5">
        {{-- Input Password Baru --}}
        <div x-data="{ show: false }">
            <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" 
                    x-model="password" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300"
                    placeholder="Masukkan password baru">
                
                <button type="button" 
                        @click="show = !show" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-green-600 transition">
                    {{-- Ikon Mata Terbuka (Show) --}}
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    {{-- Ikon Mata Tertutup (Hide) --}}
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.822 7.822L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Konfirmasi Password --}}
        <div x-data="{ showConfirm: false }">
            <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password</label>
            <div class="relative">
                <input :type="showConfirm ? 'text' : 'password'" 
                    x-model="password_confirmation" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300"
                    placeholder="Ulangi password">
                
                <button type="button" 
                        @click="showConfirm = !showConfirm" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-green-600 transition">
                    {{-- Ikon Mata Terbuka (Show) --}}
                    <svg x-show="showConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    {{-- Ikon Mata Tertutup (Hide) --}}
                    <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.822 7.822L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Error Message --}}
        <template x-if="error">
            <div class="flex items-center gap-1 text-red-500 text-[11px] italic">
                <span>ⓘ</span> <span x-text="error"></span>
            </div>
        </template>

        <button @click="submitReset" :disabled="loading"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-green-100 disabled:opacity-50">
            <span x-show="!loading">Update Password</span>
            <span x-show="loading">Memproses...</span>
        </button>
    </div>

    <template x-teleport="body">
        <div x-show="showSuccessModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
             style="display: none;">
            
            <div x-show="showSuccessModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center border border-gray-100">
                
                <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mb-2">Berhasil!</h3>
                <p class="text-gray-500 text-sm mb-8 leading-relaxed">Password anda telah berhasil diperbarui. Silahkan login kembali dengan password baru anda.</p>

                <div class="flex flex-col gap-3">
                    <button @click="goToLogin" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-green-100 active:scale-95">
                        Ke Halaman Login
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    function resetPasswordHandler() {
        return {
            email: '{{ $email }}', // Email ini dikirim aman dari session di Controller
            password: '',
            password_confirmation: '',
            loading: false,
            error: '',

            async submitReset() {
                // Validasi sisi Client (opsional tapi bagus)
                if(this.password.length < 8) {
                    this.error = 'Password minimal 8 karakter!';
                    return;
                }
                if(this.password !== this.password_confirmation) {
                    this.error = 'Konfirmasi password tidak cocok!';
                    return;
                }

                this.loading = true;
                this.error = '';

                try {
                    let response = await fetch('/reset-password', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'Accept': 'application/json', 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'ngrok-skip-browser-warning': 'true'
                        },
                        body: JSON.stringify({
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.password_confirmation
                        })
                    });

                    let data = await response.json();

                    if(response.ok) {
                        this.showSuccessModal = true;
                    } else {
                        // Ambil pesan error dari Validator Laravel
                        this.error = data.message || 'Gagal memperbarui password.';
                    }
                } catch (e) {
                    this.error = 'Terjadi kesalahan sistem. Coba lagi nanti.';
                    console.error(e);
                }
                this.loading = false;
            },

            goToLogin() {
                window.location.href = '{{ route("login") }}';
            }
        }
    }
</script>
    </div>
</div>
@endsection