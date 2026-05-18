<nav x-data="{ showLogoutModal: false, mobileMenuOpen: false }" class="bg-white border-b border-gray-100 min-h-[5rem] px-4 sticky top-0 z-50 shadow-sm flex items-center">
    <div class="max-w-7xl mx-auto w-full flex justify-between items-center transition-all duration-300">
        
        <a href="{{ route('landing') }}" class="flex items-center gap-2 group">
            <img src="{{ asset('img/logo-hydro2.ico') }}" alt="Logo HydroMart" class="w-11 h-11 object-contain">
            <span class="text-2xl font-bold text-gray-900 tracking-tight">Hydro<span class="text-green-600">Mart</span></span>
        </a>

        <div class="hidden md:flex items-center gap-8">
            @if(!Route::is('login', 'register', 'password.request', 'password.reset'))
                @if(!auth()->check() || (auth()->check() && auth()->user()->role !== 'admin'))
                    <div class="flex items-center gap-8 mr-2">
                        <a href="{{ route('produk.index') }}" class="text-gray-600 font-medium hover:text-green-600 transition text-sm">Produk</a>
                        <a href="{{ route('layanan.index') }}" class="text-gray-600 font-medium hover:text-green-600 transition text-sm">Layanan</a>
                        
                        @if(auth()->check() && auth()->user()->role === 'pelanggan')
                            {{-- Icon Keranjang Desktop --}}
                            <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-green-600 transition group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                                </svg>
                                @if($cartCount > 0)
                                    <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">{{ $cartCount }}</span>
                                @endif
                        @endif
                        </a>
                    </div>
                @endif
            @endif
            
            <div class="flex items-center gap-3">
                @auth
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open" class="h-11 flex items-center gap-3 px-3 rounded-xl hover:bg-gray-50 transition duration-300">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center border border-green-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">{{ auth()->user()->username }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak x-transition 
                            class="absolute right-0 w-52 mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 py-2">
                            
                            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-green-50 hover:text-green-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                Profil Saya
                            </a>

                            @if(auth()->user()->role == 'pelanggan')
                                <a href="{{ route('transaksi.history') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-green-50 hover:text-green-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                                    Pesanan Saya
                                </a>

                                <a href="{{ route('reward.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-green-50 hover:text-green-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                    </svg>
                                    Reward Saya
                                </a>
                            @endif

                            <hr class="my-1 border-gray-100">

                            <button @click="showLogoutModal = true; open = false" 
                                    class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition text-left font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Keluar
                            </button>
                        </div>
                    </div>
                @else
                    @if(Route::is('login', 'register', 'password.request', 'password.reset'))
                    @else
                        <a href="{{ route('login') }}" class="h-11 px-6 border-2 border-green-600 text-green-600 font-bold rounded-xl hover:bg-green-50 transition text-sm flex items-center">Login</a>
                        <a href="{{ route('register') }}" class="h-11 px-6 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-md shadow-green-100 text-sm flex items-center">Register</a>
                    @endif
                @endauth
            </div>
        </div>

        @if(!Route::is('login', 'register', 'password.request', 'password.reset'))
        <div class="md:hidden flex items-center gap-2">

            @if((auth()->check() && auth()->user()->role !== 'admin'))
                {{-- Icon Keranjang Mobile --}}
                <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-green-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center shadow-sm">{{ $cartCount }}</span>
                    @endif
                </a>
            @endif

            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 p-2 focus:outline-none">
                <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>
        @endif
    </div>

    <div x-show="mobileMenuOpen" 
        x-cloak 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="absolute top-20 left-0 w-full bg-white border-b border-gray-100 shadow-lg md:hidden z-40">
        
        <div class="px-4 py-6 space-y-4">
            @auth
                {{-- User Sudah Login --}}
                <div class="flex items-center gap-3 py-2 border-b border-gray-50 pb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center border border-green-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ auth()->user()->username }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                @if(auth()->user()->role !== 'admin')
                    <a href="{{ route('produk.index') }}" class="block text-gray-600 font-medium hover:text-green-600 py-2">Produk</a>
                    <a href="{{ route('layanan.index') }}" class="block text-gray-600 font-medium hover:text-green-600 py-2">Layanan</a>
                @endif

                <a href="{{ route('profile') }}" class="block text-gray-600 font-medium hover:text-green-600 py-2">Profil Saya</a>
                
                @if(auth()->user()->role == 'pelanggan')
                    <a href="{{ route('transaksi.history') }}" class="block text-gray-600 font-medium hover:text-green-600 py-2">Pesanan Saya</a>
                    <a href="{{ route('reward.index') }}" class="block text-gray-600 font-medium hover:text-green-600 py-2">Reward Saya</a>
                @endif
                
                <button @click="showLogoutModal = true; mobileMenuOpen = false" class="w-full text-left text-red-500 font-bold py-2">Keluar</button>

            @else
                {{-- User Belum Login (Guest) --}}
                @if(Route::is('login', 'register', 'password.request', 'password.reset'))

                @else
                    {{-- Di Halaman Biasa: Tampil Produk, Layanan, & Tombol Auth --}}
                    <a href="{{ route('produk.index') }}" class="block text-gray-600 font-medium hover:text-green-600 py-2">Produk</a>
                    <a href="{{ route('layanan.index') }}" class="block text-gray-600 font-medium hover:text-green-600 py-2">Layanan</a>

                    <hr class="border-gray-50 my-2">

                    <div class="grid grid-cols-1 gap-3 pt-2">
                        <a href="{{ route('login') }}" class="w-full h-12 flex items-center justify-center border-2 border-green-600 text-green-600 font-bold rounded-xl">Login</a>
                        <a href="{{ route('register') }}" class="w-full h-12 flex items-center justify-center bg-green-600 text-white font-bold rounded-xl shadow-md">Register</a>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="showLogoutModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
             style="display: none;">
            
            <div @click.away="showLogoutModal = false" 
                 x-show="showLogoutModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center">
                
                <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Logout</h3>
                <p class="text-gray-500 text-sm mb-8 leading-relaxed">Apakah anda yakin ingin melakukan Log Out?</p>

                <div class="flex flex-col gap-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-red-100 active:scale-95">
                            Ya, Keluar Sekarang
                        </button>
                    </form>
                    
                    <button @click="showLogoutModal = false" type="button" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-4 rounded-2xl transition-all active:scale-95">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </template>
</nav>