@extends('layouts.app')

@section('title', 'Ubah Data Akun')

@section('content')
<div class="w-full max-w-4xl mx-auto">
    
    <div class="mb-8">
        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">
            Data Akun Anda
        </span>
        <h1 class="text-3xl font-extrabold text-gray-900 mt-4 tracking-tight">Data Akun</h1>
        <p class="text-gray-500 text-sm mt-2 font-medium">Kelola informasi akun dan keamanan akun Anda</p>
    </div>

    <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-gray-100 flex w-full mb-8">
        <a href="{{ route('profile') }}" 
           class="flex-1 py-3 rounded-xl text-sm font-bold transition-all duration-300 text-center text-gray-500 hover:text-gray-700">
            Lihat Data Akun
        </a>
        <a href="{{ route('profile.edit') }}" 
           class="flex-1 py-3 rounded-xl text-sm font-bold transition-all duration-300 text-center bg-green-600 text-white shadow-md">
            Ubah Data Akun
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-600 text-white rounded-2xl shadow-lg shadow-green-100 text-sm font-bold flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-8 border-b border-gray-200 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 border border-green-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Ubah Data Akun</h3>
                <p class="text-gray-400 text-xs font-medium">Pastikan informasi profil Anda selalu diperbarui.</p>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" 
                        class="w-full bg-gray-50 border {{ $errors->has('username') ? 'border-red-500' : 'border-gray-100' }} p-4 rounded-2xl focus:ring-2 focus:ring-green-500 outline-none font-bold text-gray-700 transition-all">
                    @error('username') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" 
                        class="w-full bg-gray-50 border {{ $errors->has('nama_lengkap') ? 'border-red-500' : 'border-gray-100' }} p-4 rounded-2xl focus:ring-2 focus:ring-green-500 outline-none font-bold text-gray-700 transition-all">
                    @error('nama_lengkap') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                        class="w-full bg-gray-50 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-100' }} p-4 rounded-2xl focus:ring-2 focus:ring-green-500 outline-none font-bold text-gray-700 transition-all">
                    @error('email') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Nomor Telepon</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" 
                        class="w-full bg-gray-50 border {{ $errors->has('no_hp') ? 'border-red-500' : 'border-gray-100' }} p-4 rounded-2xl focus:ring-2 focus:ring-green-500 outline-none font-bold text-gray-700 transition-all">
                    @error('no_hp') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Alamat</label>
                    <textarea name="alamat" rows="3" 
                            class="w-full bg-gray-50 border {{ $errors->has('alamat') ? 'border-red-500' : 'border-gray-100' }} p-4 rounded-2xl focus:ring-2 focus:ring-green-500 outline-none font-bold text-gray-700 leading-relaxed transition-all">{{ old('alamat', $user->alamat) }}</textarea>
                    @error('alamat') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <button type="submit" class="bg-green-600 text-white px-10 py-3.5 rounded-2xl font-bold shadow-lg shadow-green-100 hover:bg-green-700 transition-all">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-200 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600 border border-red-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Keamanan Akun</h3>
                <p class="text-gray-400 text-xs font-medium">Gunakan kombinasi password yang kuat untuk keamanan ekstra.</p>
            </div>
        </div>

        <form action="{{ route('profile.password.update') }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6 max-w-md">
                <div x-data="{ show: false }">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Password Saat Ini</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="current_password" 
                            class="w-full bg-gray-50 border {{ $errors->has('current_password') ? 'border-red-500' : 'border-gray-100' }} p-4 pr-12 rounded-2xl focus:ring-2 focus:ring-red-500 outline-none font-bold transition-all">
                        
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-600 transition">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.822 7.822L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('current_password') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                </div>

                <div x-data="{ show: false }">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Password Baru</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" 
                            class="w-full bg-gray-50 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-100' }} p-4 pr-12 rounded-2xl focus:ring-2 focus:ring-red-500 outline-none font-bold transition-all">
                        
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-600 transition">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.822 7.822L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p> @enderror
                </div>

                <div x-data="{ show: false }">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" 
                            class="w-full bg-gray-50 border border-gray-100 p-4 pr-12 rounded-2xl focus:ring-2 focus:ring-red-500 outline-none font-bold transition-all">
                        
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-600 transition">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.822 7.822L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="bg-red-600 text-white px-10 py-3.5 rounded-2xl font-bold shadow-lg shadow-red-100 hover:bg-red-700 transition-all">
                Update Password
            </button>
        </form>
    </div>
</div>
@endsection