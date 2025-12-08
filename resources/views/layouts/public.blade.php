<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laboratorium Teknik Informatika UTM')</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { 
                        utm: { 
                            primary: '#002147', secondary: '#0056b3', accent: '#ffc107',
                            success: '#198754', danger: '#dc3545'
                        } 
                    }
                }
            }
        }
    </script>
    <style>
        .hero-pattern {
            background-color: #002147;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .custom-scrollbar::-webkit-scrollbar { width: 3px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="antialiased font-sans bg-gray-50 text-gray-800 flex flex-col min-h-screen">

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

                    @auth
                        <a href="{{ route('peminjaman.riwayat') }}" class="{{ request()->routeIs('peminjaman.riwayat*') ? 'text-utm-primary font-bold border-b-2 border-utm-primary' : 'text-gray-600 hover:text-utm-primary font-medium' }} transition h-full flex items-center">
                            Riwayat
                        </a>
                    @endauth
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
                                 class="absolute right-0 z-50 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-1 origin-top-right">
                                
                                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50 rounded-t-xl">
                                    <p class="text-xs text-gray-500">Login sebagai</p>
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 flex items-center gap-2">
                                    <i class="bi bi-speedometer2 text-gray-400"></i> Dashboard
                                </a>

                                <a href="{{ route('peminjaman.riwayat') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 flex items-center gap-2">
                                    <i class="bi bi-clock-history text-gray-400"></i> Riwayat Peminjaman
                                </a>

                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 flex items-center gap-2">
                                    <i class="bi bi-person-gear text-gray-400"></i> Edit Profil
                                </a>

                                <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-b-xl flex items-center gap-2">
                                        <i class="bi bi-box-arrow-right"></i> Keluar
                                    </button>
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

    <div class="flex-grow">
        @yield('content')
    </div>

    <footer class="bg-gray-900 text-white py-12 border-t border-gray-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center font-bold">TI</div>
                        <span class="font-bold text-xl">Teknik Informatika</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Universitas Trunojoyo Madura<br>Jl. Raya Telang, Kamal, Bangkalan
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><i class="bi bi-envelope me-2"></i> lab.if@trunojoyo.ac.id</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Tautan</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('public.schedule') }}" class="hover:text-white">Jadwal Lab</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white">Login Mahasiswa</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Laboratorium Teknik Informatika UTM. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>