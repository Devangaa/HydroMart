@extends('layouts.app')

@section('title', 'Data Akun Anda')

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
           class="flex-1 py-3 rounded-xl text-sm font-bold transition-all duration-300 text-center {{ Route::is('profile') ? 'bg-green-600 text-white shadow-md' : 'text-gray-500 hover:text-gray-700' }}">
            Lihat Data Akun
        </a>
        <a href="{{ route('profile.edit') }}" 
           class="flex-1 py-3 rounded-xl text-sm font-bold transition-all duration-300 text-center {{ Route::is('profile.edit') ? 'bg-green-600 text-white shadow-md' : 'text-gray-400 hover:text-gray-600' }}">
            Ubah Data Akun
        </a>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden w-full">
        <div class="p-8 border-b border-gray-200 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center border border-green-100 text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Informasi Akun</h3>
                <p class="text-gray-400 text-xs font-medium">Detail lengkap akun Anda</p>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100/50">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Username</label>
                <p class="text-gray-800 font-bold ml-1">{{ $user->username }}</p>
            </div>

            <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100/50">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                <p class="text-gray-800 font-bold ml-1">{{ $user->nama_lengkap }}</p>
            </div>

            <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100/50">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                <p class="text-gray-800 font-bold ml-1">{{ $user->email }}</p>
            </div>

            <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100/50">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Nomor Telepon</label>
                <p class="text-gray-800 font-bold ml-1">{{ $user->no_hp }}</p>
            </div>

            <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100/50">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Tanggal Bergabung</label>
                <p class="text-gray-800 font-bold ml-1">{{ $user->created_at->format('d F Y') }}</p>
            </div>

            <div class="hidden md:block"></div>

            <div class="md:col-span-2 bg-gray-50/50 p-6 rounded-2xl border border-gray-100/50">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Alamat</label>
                <p class="text-gray-800 font-bold leading-relaxed ml-1">{{ $user->alamat }}</p>
            </div>
        </div>
    </div>
</div>
@endsection