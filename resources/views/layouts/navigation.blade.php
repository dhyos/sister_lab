<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <a href="{{ url('/') }}" class="flex items-center gap-3 hover:opacity-80 transition">
                <div class="w-10 h-10 bg-utm-primary rounded-lg flex items-center justify-center text-white font-bold text-xl">TI</div>
                <div class="flex flex-col">
                    <span class="font-bold text-utm-primary text-lg leading-tight">Laboratorium</span>
                    <span class="text-xs text-gray-500 font-medium">Teknik Informatika UTM</span>
                </div>
            </a>
            
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ url('/') }}#lab-info" class="text-gray-600 hover:text-utm-primary font-medium transition">Laboratorium</a>
                
                <a href="{{ route('public.schedule') }}" class="{{ request()->routeIs('public.schedule') ? 'text-utm-primary font-bold border-b-2 border-utm-primary' : 'text-gray-600 hover:text-utm-primary font-medium' }} transition h-full flex items-center">
                    Jadwal
                </a>
                
                <a href="{{ url('/') }}#barang" class="text-gray-600 hover:text-utm-primary font-medium transition">Peminjaman Barang</a>
            </div>

            <div class="flex items-center gap-3" x-data="{ open: false }">
                @auth
                    <div class="relative">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-full hover:bg-gray-50 hover:text-utm-primary transition shadow-sm">
                            <div class="w-6 h-6 rounded-full bg-utm-primary text-white flex items-center justify-center text-xs font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        
                        <div x-cloak x-show="open" 
                             x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute right-0 z-50 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1 origin-top-right">
                            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50 rounded-t-xl">
                                <p class="text-xs text-gray-500">Login sebagai</p>
                                <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-b-xl">Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-utm-primary font-medium hover:bg-blue-50 rounded-lg transition text-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="px-5 py-2 bg-utm-primary text-white rounded-full font-medium hover:bg-utm-secondary transition shadow-md text-sm">Daftar Akun</a>
                @endauth
            </div>
        </div>
    </div>
</nav>