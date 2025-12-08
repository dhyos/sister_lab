@extends('layouts.public')

@section('title', 'Pengajuan Berhasil')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 mb-6 animate-bounce">
            <i class="bi bi-check-lg text-5xl text-green-600"></i>
        </div>

        <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900">
            Pengajuan Terkirim!
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
        <div class="bg-white py-8 px-4 shadow-xl sm:rounded-xl sm:px-10 border border-gray-100 text-center">
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-bold text-blue-800 mb-2">Langkah Selanjutnya:</h3>
                <p class="text-blue-900 leading-relaxed text-sm md:text-base">
                    "Pengajuan sudah dikirimkan. Silahkan serahkan <strong>Surat Fisik (Hardcopy)</strong> pada Admin di Laboratorium untuk persetujuan berikutnya paling lama <strong>2x24 jam</strong>."
                </p>
            </div>

            <div class="space-y-4">
                <p class="text-gray-500 text-sm">
                    Status jadwal Anda saat ini adalah <span class="font-bold text-yellow-600 bg-yellow-100 px-2 py-0.5 rounded">Dipesan</span>. 
                    Jadwal akan berubah menjadi <span class="font-bold text-green-600 bg-green-100 px-2 py-0.5 rounded">Terpakai</span> setelah disetujui Admin.
                </p>

                <div class="border-t border-gray-100 pt-6 mt-6 flex flex-col gap-3">
                    <a href="{{ route('public.schedule') }}" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-utm-primary hover:bg-utm-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-utm-primary transition">
                        Lihat Jadwal
                    </a>
                    
                    <a href="{{ url('/') }}" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-utm-primary transition">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection