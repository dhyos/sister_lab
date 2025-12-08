@extends('layouts.public')

@section('title', 'Beranda - Lab TI UTM')

@section('content')
    <header class="hero-pattern text-white py-20 lg:py-28 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-blue-800/50 border border-blue-700 text-sm font-medium mb-6 backdrop-blur-sm">
                Sistem Informasi Manajemen Laboratorium
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-6 leading-tight">
                Kelola Riset & Praktikum <br> <span class="text-blue-300">Lebih Mudah & Efisien</span>
            </h1>
            <p class="text-lg md:text-xl text-blue-100 max-w-2xl mx-auto mb-10">
                Fasilitas peminjaman ruang laboratorium dan peralatan pendukung perkuliahan Teknik Informatika Universitas Trunojoyo Madura.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#barang" class="px-8 py-4 bg-white text-utm-primary font-bold rounded-xl hover:bg-gray-100 transition shadow-xl transform hover:-translate-y-1">
                    <i class="bi bi-box-seam me-2"></i> Pinjam Barang
                </a>
                <a href="{{ route('public.schedule') }}" class="px-8 py-4 bg-white/10 border-2 border-white/30 text-white font-bold rounded-xl hover:bg-white/20 transition backdrop-blur-sm">
                    <i class="bi bi-calendar-check me-2"></i> Cek Jadwal Lab
                </a>
            </div>
        </div>
        
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    </header>

    <section class="py-16 bg-white relative -mt-10 z-20 rounded-t-[3rem] border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-8 bg-white border border-gray-100 rounded-2xl shadow-xl shadow-gray-200/50 hover:-translate-y-1 transition duration-300 group">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center text-utm-primary text-2xl mb-6 group-hover:bg-utm-primary group-hover:text-white transition">
                        <i class="bi bi-pc-display"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Laboratorium Modern</h3>
                    <p class="text-gray-500 mb-4 text-sm leading-relaxed">Informasi lengkap mengenai fasilitas LAB RPL, Jaringan, Multimedia, dan Komputasi Cerdas.</p>
                    <a href="#lab-info" class="text-utm-primary font-bold text-sm hover:underline flex items-center gap-1">
                        Lihat Detail <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="p-8 bg-white border border-gray-100 rounded-2xl shadow-xl shadow-gray-200/50 hover:-translate-y-1 transition duration-300 group">
                    <div class="w-14 h-14 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 text-2xl mb-6 group-hover:bg-orange-500 group-hover:text-white transition">
                        <i class="bi bi-calendar-week"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Jadwal Real-time</h3>
                    <p class="text-gray-500 mb-4 text-sm leading-relaxed">Pantau ketersediaan ruangan laboratorium secara langsung untuk menghindari bentrok jadwal.</p>
                    <a href="{{ route('public.schedule') }}" class="text-orange-600 font-bold text-sm hover:underline flex items-center gap-1">
                        Cek Slot <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="p-8 bg-white border border-gray-100 rounded-2xl shadow-xl shadow-gray-200/50 hover:-translate-y-1 transition duration-300 group">
                    <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center text-green-600 text-2xl mb-6 group-hover:bg-green-500 group-hover:text-white transition">
                        <i class="bi bi-tools"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Peminjaman Alat</h3>
                    <p class="text-gray-500 mb-4 text-sm leading-relaxed">Layanan peminjaman alat praktikum seperti Proyektor, Router, Microcontroller, dll.</p>
                    <a href="#barang" class="text-green-600 font-bold text-sm hover:underline flex items-center gap-1">
                        Pinjam Alat <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="lab-info" class="py-16 bg-gray-50 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-utm-secondary font-bold tracking-wider text-xs uppercase mb-2 block">Fasilitas Kami</span>
                <h2 class="text-3xl font-bold text-utm-primary">Daftar Laboratorium</h2>
                <p class="text-gray-500 mt-2 max-w-2xl mx-auto">Temukan laboratorium yang sesuai dengan kebutuhan praktikum dan penelitian Anda di Teknik Informatika UTM.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($labs as $lab)
                <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 group flex flex-col h-full border border-gray-100 hover:border-blue-200">
                    <div class="h-48 overflow-hidden bg-gray-200 relative">
                        <img 
                            src="https://placehold.co/400x250/002147/FFFFFF?text={{ urlencode(Str::limit($lab->nama_lab, 15)) }}" 
                            alt="{{ $lab->nama_lab }}" 
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                        >
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-2.5 py-1 rounded-md text-xs font-bold text-utm-primary shadow-sm border border-gray-200">
                            <i class="bi bi-people-fill me-1"></i> {{ $lab->kapasitas }}
                        </div>
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        <h4 class="font-bold text-lg text-gray-900 mb-2 leading-snug group-hover:text-utm-secondary transition">
                            {{ $lab->nama_lab }}
                        </h4>
                        
                        <p class="text-sm text-gray-500 mb-4 flex-grow line-clamp-3">
                            {{ Str::limit($lab->deskripsi, 100) }}
                        </p>

                        <div class="text-xs text-gray-500 mb-4 flex items-center gap-1.5 bg-gray-50 p-2 rounded-lg">
                            <i class="bi bi-geo-alt-fill text-utm-secondary"></i>
                            <span class="font-medium">{{ $lab->lokasi }}</span>
                        </div>

                        <div class="mt-auto pt-4 border-t border-gray-100">
                            <a href="{{ route('public.lab.show', $lab->id_lab) }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-white border border-utm-primary text-utm-primary text-sm font-bold rounded-lg hover:bg-utm-primary hover:text-white transition gap-2">
                                Detail & Jadwal <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-4 py-16 text-center">
                    <div class="bg-gray-50 rounded-2xl p-8 border border-gray-200 inline-block">
                        <i class="bi bi-database-x text-4xl text-gray-400 mb-3 block"></i>
                        <p class="text-gray-500 font-medium">Belum ada data laboratorium yang tersedia.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection