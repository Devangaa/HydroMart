@extends('layouts.app')

@section('title', 'Buat Akun Anda')

@section('content')
<div class="w-full">
    <div class="max-w-4xl mx-auto px-6">
<div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-gray-100 w-full max-w-4xl"
    x-data="{ loading: false }">
    
    <div class="flex justify-center mb-6">
        <img src="{{ asset('img/logo-hydro2.ico') }}" 
        alt="Logo HydroMart" 
        class="w-20 h-20 object-contain">
    </div>

    <h2 class="text-2xl font-bold text-center text-gray-900 mb-1">Buat Akun Anda</h2>
    <p class="text-center text-gray-400 text-sm mb-8">Selamat datang di HydroMart</p>

    <form action="{{ route('register') }}" method="POST" @submit="loading = true">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 text-left">
        
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300" placeholder="Nama sesuai KTP" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300" placeholder="contoh@mail.com" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">No. Telepon</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300" placeholder="085xxxxx" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300" placeholder="Buat username unik" required>
            </div>

            <div x-data="{ show: false }">
                <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300" placeholder="Minimal 8 karakter" required>
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400">
                        <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                    </button>
                </div>
            </div>

            <div x-data="{ show: false }">
                <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password_confirmation" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300" placeholder="Ulangi password" required>
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400">
                        <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                    </button>
                </div>
            </div>

            {{-- Provinsi, Kota, Kecamatan - Full row with 3 columns --}}
            <div x-data="cascadingDropdown()" x-init="init()" class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Provinsi Custom Dropdown --}}
                <div class="relative" x-data="{ open: false }" @keydown.escape="open = false">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Provinsi</label>
                    <input type="hidden" name="provinsi" :value="selectedProvince">

                    <button type="button"
                        @click="open = !open"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition text-sm font-semibold text-left flex items-center justify-between gap-2"
                        :class="open ? 'ring-2 ring-green-500 border-green-300' : 'hover:border-gray-300'"
                    >
                        <span :class="selectedProvinceName ? 'text-gray-800' : 'text-gray-400'"
                              x-text="selectedProvinceName || 'Pilih Provinsi'"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-cloak
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden z-50">
                        {{-- Search --}}
                        <div class="px-3 pt-3 pb-2 border-b border-gray-50">
                            <input type="text" x-model="provinceSearch" placeholder="Cari provinsi..."
                                   class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-100 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none placeholder-gray-300">
                        </div>
                        <div class="max-h-52 overflow-y-auto">
                            <template x-if="filteredProvinces.length === 0">
                                <p class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ditemukan</p>
                            </template>
                            <template x-for="prov in filteredProvinces" :key="prov.id">
                                <button type="button"
                                    @click="selectProvince(prov); open = false"
                                    class="w-full text-left px-4 py-3 text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-600 transition"
                                    :class="selectedProvince == prov.id ? 'bg-green-50 text-green-600' : ''"
                                    x-text="prov.name">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Kota / Kabupaten Custom Dropdown --}}
                <div class="relative" x-data="{ open: false }" @keydown.escape="open = false">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kota / Kabupaten</label>
                    <input type="hidden" name="kota" :value="selectedCity">

                    <button type="button"
                        @click="if (selectedProvince && !loadingCities) open = !open"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition text-sm font-semibold text-left flex items-center justify-between gap-2"
                        :class="{
                            'ring-2 ring-green-500 border-green-300': open,
                            'hover:border-gray-300': selectedProvince && !loadingCities,
                            'opacity-50 cursor-not-allowed': !selectedProvince || loadingCities
                        }"
                    >
                        <span class="flex items-center gap-2" :class="selectedCityName ? 'text-gray-800' : 'text-gray-400'">
                            <svg x-show="loadingCities" class="animate-spin h-4 w-4 text-green-500 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-text="loadingCities ? 'Memuat...' : (selectedCityName || 'Pilih Kota / Kabupaten')"></span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-cloak
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden z-50">
                        <div class="px-3 pt-3 pb-2 border-b border-gray-50">
                            <input type="text" x-model="citySearch" placeholder="Cari kota..."
                                   class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-100 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none placeholder-gray-300">
                        </div>
                        <div class="max-h-52 overflow-y-auto">
                            <template x-if="filteredCities.length === 0">
                                <p class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ditemukan</p>
                            </template>
                            <template x-for="city in filteredCities" :key="city.id">
                                <button type="button"
                                    @click="selectCity(city); open = false"
                                    class="w-full text-left px-4 py-3 text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-600 transition"
                                    :class="selectedCity == city.id ? 'bg-green-50 text-green-600' : ''"
                                    x-text="city.name">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Kecamatan Custom Dropdown --}}
                <div class="relative" x-data="{ open: false }" @keydown.escape="open = false">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kecamatan</label>
                    <input type="hidden" name="kecamatan" :value="selectedKecamatan">

                    <button type="button"
                        @click="if (selectedCity && !loadingKecamatan) open = !open"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition text-sm font-semibold text-left flex items-center justify-between gap-2"
                        :class="{
                            'ring-2 ring-green-500 border-green-300': open,
                            'hover:border-gray-300': selectedCity && !loadingKecamatan,
                            'opacity-50 cursor-not-allowed': !selectedCity || loadingKecamatan
                        }"
                    >
                        <span class="flex items-center gap-2" :class="selectedKecamatanName ? 'text-gray-800' : 'text-gray-400'">
                            <svg x-show="loadingKecamatan" class="animate-spin h-4 w-4 text-green-500 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-text="loadingKecamatan ? 'Memuat...' : (selectedKecamatanName || 'Pilih Kecamatan')"></span>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open"
                         x-cloak
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden z-50">
                        <div class="px-3 pt-3 pb-2 border-b border-gray-50">
                            <input type="text" x-model="kecamatanSearch" placeholder="Cari kecamatan..."
                                   class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-100 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none placeholder-gray-300">
                        </div>
                        <div class="max-h-52 overflow-y-auto">
                            <template x-if="filteredKecamatan.length === 0">
                                <p class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ditemukan</p>
                            </template>
                            <template x-for="kec in filteredKecamatan" :key="kec.id">
                                <button type="button"
                                    @click="selectKecamatan(kec); open = false"
                                    class="w-full text-left px-4 py-3 text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-600 transition"
                                    :class="selectedKecamatan == kec.id ? 'bg-green-50 text-green-600' : ''"
                                    x-text="kec.name">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

            </div>

            <div class="md:col-span-3">
                <label class="block text-sm font-bold text-gray-700 mb-2">Detail Alamat (No. Rumah / Blok)</label>
                <textarea name="alamat" rows="2" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:outline-none transition placeholder-gray-300" 
                        placeholder="Contoh: No. 12, Blok A, dekat warung." required>{{ old('alamat') }}</textarea>
            </div>
        </div>

        <div class="flex flex-col items-end mb-6">
            @if($errors->any())
                <div class="flex items-center gap-1 text-red-500 text-[11px] w-full italic">
                    <span class="not-italic">ⓘ</span> {{ $errors->first() }}
                </div>
            @endif
        </div>

        <div class="max-w-xs mx-auto">
            <button type="submit" 
                :disabled="loading"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition duration-300 transform active:scale-[0.98] shadow-lg shadow-green-100 flex justify-center items-center gap-2">
            
            {{-- Teks Normal --}}
            <span x-show="!loading">Register</span>
            
            {{-- Teks & Spinner saat Loading --}}
            <span x-show="loading" class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        </button>
        </div>

        <div class="relative my-8">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
            <div class="relative flex justify-center text-xs"><span class="bg-white px-3 text-gray-300 font-medium">atau</span></div>
        </div>

        <p class="text-center text-sm text-gray-400">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-green-600 font-bold hover:underline">Masuk Sekarang</a>
        </p>
    </form>
</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function cascadingDropdown() {
        const basePath = "{{ rtrim((string)parse_url(url('/'), PHP_URL_PATH), '/') }}";

        return {
            provinces: [],
            cities: [],
            kecamatan: [],

            selectedProvince: '{{ old('provinsi') ?? '' }}',
            selectedCity: '{{ old('kota') ?? '' }}',
            selectedKecamatan: '{{ old('kecamatan') ?? '' }}',

            selectedProvinceName: '',
            selectedCityName: '',
            selectedKecamatanName: '',

            provinceSearch: '',
            citySearch: '',
            kecamatanSearch: '',

            loadingCities: false,
            loadingKecamatan: false,

            get filteredProvinces() {
                if (!this.provinceSearch) return this.provinces;
                const q = this.provinceSearch.toLowerCase();
                return this.provinces.filter(p => p.name.toLowerCase().includes(q));
            },

            get filteredCities() {
                if (!this.citySearch) return this.cities;
                const q = this.citySearch.toLowerCase();
                return this.cities.filter(c => c.name.toLowerCase().includes(q));
            },

            get filteredKecamatan() {
                if (!this.kecamatanSearch) return this.kecamatan;
                const q = this.kecamatanSearch.toLowerCase();
                return this.kecamatan.filter(k => k.name.toLowerCase().includes(q));
            },

            selectProvince(prov) {
                this.selectedProvince = prov.id;
                this.selectedProvinceName = prov.name;
                this.provinceSearch = '';
                this.selectedCity = '';
                this.selectedCityName = '';
                this.selectedKecamatan = '';
                this.selectedKecamatanName = '';
                this.cities = [];
                this.kecamatan = [];
                this.onProvinceChange();
            },

            selectCity(city) {
                this.selectedCity = city.id;
                this.selectedCityName = city.name;
                this.citySearch = '';
                this.selectedKecamatan = '';
                this.selectedKecamatanName = '';
                this.kecamatan = [];
                this.onCityChange();
            },

            selectKecamatan(kec) {
                this.selectedKecamatan = kec.id;
                this.selectedKecamatanName = kec.name;
                this.kecamatanSearch = '';
            },

            async init() {
                await this.fetchProvinces();

                // Restore old values (validation fail)
                if (this.selectedProvince) {
                    await this.onProvinceChange();
                    const prov = this.provinces.find(p => p.id == this.selectedProvince);
                    if (prov) this.selectedProvinceName = prov.name;
                }
            },

            async fetchProvinces() {
                try {
                    const response = await fetch(`${basePath}/api/provinces`);
                    this.provinces = await response.json();
                } catch (error) {
                    console.error('Error fetching provinces:', error);
                }
            },

            async onProvinceChange() {
                if (!this.selectedProvince) {
                    this.cities = [];
                    this.kecamatan = [];
                    this.selectedCity = '';
                    this.selectedCityName = '';
                    this.selectedKecamatan = '';
                    this.selectedKecamatanName = '';
                    return;
                }

                this.loadingCities = true;
                this.cities = [];
                this.kecamatan = [];

                try {
                    const response = await fetch(`${basePath}/api/cities/${this.selectedProvince}`);
                    this.cities = await response.json();

                    // Restore old city value
                    if (this.selectedCity) {
                        const city = this.cities.find(c => c.id == this.selectedCity);
                        if (city) {
                            this.selectedCityName = city.name;
                            await this.onCityChange();
                        }
                    }
                } catch (error) {
                    console.error('Error fetching cities:', error);
                } finally {
                    this.loadingCities = false;
                }
            },

            async onCityChange() {
                if (!this.selectedCity) {
                    this.kecamatan = [];
                    this.selectedKecamatan = '';
                    this.selectedKecamatanName = '';
                    return;
                }

                this.loadingKecamatan = true;
                this.kecamatan = [];

                try {
                    const response = await fetch(`${basePath}/api/kecamatan/${this.selectedCity}`);
                    this.kecamatan = await response.json();

                    // Restore old kecamatan value
                    if (this.selectedKecamatan) {
                        const kec = this.kecamatan.find(k => k.id == this.selectedKecamatan);
                        if (kec) this.selectedKecamatanName = kec.name;
                    }
                } catch (error) {
                    console.error('Error fetching kecamatan:', error);
                } finally {
                    this.loadingKecamatan = false;
                }
            },
        }
    }
</script>
@endpush

