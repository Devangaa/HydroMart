<nav class="bg-white border-b border-gray-100 h-20 px-4 sticky top-0 z-50 shadow-sm flex items-center">
    <div class="max-w-7xl mx-auto w-full flex justify-between items-center transition-all duration-300">
        
        <a href="{{ route('landing') }}" class="flex items-center gap-2 group">
            <div class="bg-green-600 p-2 rounded-lg">
                <div class="w-4 h-4 bg-white rounded-full"></div>
            </div>
            <span class="text-xl font-bold text-gray-800 tracking-tight">HydroMart</span>
        </a>

        <div class="flex items-center gap-8">
            
            @if(!Route::is('login', 'register'))
            <div class="hidden md:flex items-center gap-8 mr-2">
                <a href="#produk" class="text-gray-600 font-medium hover:text-green-600 transition text-sm">Produk</a>
                <a href="#layanan" class="text-gray-600 font-medium hover:text-green-600 transition text-sm">Layanan</a>
            </div>
            @endif
            
            <div class="flex items-center gap-3">
                @auth
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open" class="h-11 flex items-center gap-3 px-3 rounded-xl hover:bg-gray-50 transition duration-300 focus:outline-none">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center overflow-hidden border border-green-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-700">{{ auth()->user()->username }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak x-transition class="absolute right-0 w-52 mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 py-2">
                            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-green-50 hover:text-green-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Manajemen Profil
                            </a>
                            <hr class="border-gray-50 my-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition text-left font-bold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    @if(Route::is('login') || Route::is('register'))
                        <a href="{{ route('landing') }}" class="text-green-600 font-semibold text-sm hover:text-green-700 transition flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>
                            Kembali ke Beranda
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="h-11 px-6 border-2 border-green-600 text-green-600 font-bold rounded-xl hover:bg-green-50 transition duration-300 text-sm flex items-center justify-center">Login</a>
                        <a href="{{ route('register') }}" class="h-11 px-6 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition duration-300 shadow-md shadow-green-100 text-sm flex items-center justify-center">Register</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>