@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="w-full">
    <div class="max-w-4xl mx-auto px-6">

        <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-gray-100 w-full max-w-md mx-auto"
            x-data="{ loading: false }">
            
            <div class="flex justify-center mb-6">
                <img src="{{ asset('img/logo-hydro2.ico') }}" 
                        alt="Logo HydroMart" 
                        class="w-20 h-20 object-contain">
            </div>

            <h2 class="text-2xl font-bold text-center text-gray-900 mb-1">Masuk ke Akun</h2>
            <p class="text-center text-gray-400 text-sm mb-8">Selamat datang kembali di HydroMart</p>

            <form action="{{ route('login') }}" method="POST" @submit="loading = true">
                @csrf
                
                <div class="mb-5">
                    <label for="username" class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                    <input type="text" 
                        name="username" 
                        id="username"
                        value="{{ old('username') }}"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent focus:outline-none transition placeholder-gray-300"
                        placeholder="Masukkan username anda"
                        required>
                </div>

                <div class="mb-2" x-data="{ show: false }">
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" 
                            name="password" 
                            id="password"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent focus:outline-none transition placeholder-gray-300"
                            placeholder="Masukkan password anda"
                            required>
                        
                        <button type="button" 
                                @click="show = !show" 
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-green-600 transition">
                            
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>

                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.822 7.822L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex flex-col items-end mb-6">
                    <a href="{{ route('password.request') }}" class="text-[11px] text-green-600 font-bold hover:underline mb-2">Lupa Password?</a>
                    
                    @if($errors->any())
                        <div class="flex items-center gap-1 text-red-500 text-[11px] w-full italic">
                            <span class="not-italic">ⓘ</span> {{ $errors->first() }}
                        </div>
                    @endif
                </div>

                <button type="submit" 
                        :disabled="loading"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition duration-300 transform active:scale-[0.98] shadow-lg shadow-green-100 flex justify-center items-center gap-2">
                    
                    {{-- Teks Normal --}}
                    <span x-show="!loading">Login</span>
                    
                    {{-- Teks & Spinner saat Loading --}}
                    <span x-show="loading" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>

                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-100"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="bg-white px-3 text-gray-300 font-medium">atau</span>
                    </div>
                </div>

                <p class="text-center text-sm text-gray-400">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-green-600 font-bold hover:underline">Daftar Sekarang</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection