@extends('layouts.public')

@section('title', 'Ajukan Peminjaman Lab')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <a href="{{ route('public.lab.show', $lab->id_lab) }}" class="inline-flex items-center text-gray-500 hover:text-utm-primary mb-6 transition font-medium">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Detail Lab
        </a>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-utm-primary px-8 py-6 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h1 class="text-2xl font-bold flex items-center gap-2">
                        <i class="bi bi-file-earmark-text-fill"></i> Formulir Peminjaman Lab
                    </h1>
                    <p class="text-blue-200 mt-1 text-sm">Silakan lengkapi data berikut untuk mengajukan peminjaman ruangan.</p>
                </div>
                <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            </div>

            <div class="p-8">
                @if ($errors->any())
                    <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-8 border border-red-200 flex items-start gap-3">
                        <i class="bi bi-exclamation-triangle-fill mt-0.5"></i>
                        <div>
                            <h4 class="font-bold text-sm">Terjadi Kesalahan:</h4>
                            <ul class="list-disc list-inside text-sm mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('peminjaman.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="lab_id" value="{{ $lab->id_lab }}">

                    <div class="mb-8 p-4 bg-blue-50 rounded-xl border border-blue-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-utm-primary text-xl font-bold shadow-sm border border-blue-100">
                            <i class="bi bi-pc-display-horizontal"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Laboratorium Tujuan</p>
                            <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $lab->nama_lab }}</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Penanggung Jawab</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="bi bi-person-fill"></i></span>
                                <input type="text" value="{{ Auth::user()->name }}" readonly class="pl-10 w-full bg-gray-100 border-gray-300 rounded-lg text-gray-600 cursor-not-allowed focus:ring-0 text-sm font-medium">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">NIM</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="bi bi-card-heading"></i></span>
                                <input type="text" value="{{ Auth::user()->nim ?? '-' }}" readonly class="pl-10 w-full bg-gray-100 border-gray-300 rounded-lg text-gray-600 cursor-not-allowed focus:ring-0 text-sm font-medium">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">No. WhatsApp <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="bi bi-whatsapp"></i></span>
                                <input type="text" name="no_wa" value="{{ old('no_wa') }}" placeholder="08..." required class="pl-10 w-full border-gray-300 rounded-lg focus:ring-utm-primary focus:border-utm-primary text-sm font-medium shadow-sm">
                            </div>
                            @error('no_wa')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Peminjaman <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $selectedJadwal ? $selectedJadwal->tanggal : '') }}" required class="w-full border-gray-300 rounded-lg focus:ring-utm-primary focus:border-utm-primary text-sm font-medium shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jam Mulai <span class="text-red-500">*</span></label>
                            <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $selectedJadwal ? \Carbon\Carbon::parse($selectedJadwal->jam_mulai)->format('H:i') : '') }}" required class="w-full border-gray-300 rounded-lg focus:ring-utm-primary focus:border-utm-primary text-sm shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jam Selesai <span class="text-red-500">*</span></label>
                            <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $selectedJadwal ? \Carbon\Carbon::parse($selectedJadwal->jam_selesai)->format('H:i') : '') }}" required class="w-full border-gray-300 rounded-lg focus:ring-utm-primary focus:border-utm-primary text-sm shadow-sm">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kegiatan / Keperluan <span class="text-red-500">*</span></label>
                        <textarea name="kegiatan" rows="3" placeholder="Contoh: Praktikum Tambahan Pemrograman Web Kelas A untuk mengganti pertemuan minggu lalu." required class="w-full border-gray-300 rounded-lg focus:ring-utm-primary focus:border-utm-primary text-sm shadow-sm">{{ old('kegiatan') }}</textarea>
                    </div>

                    <div class="mb-8 bg-gray-50 p-5 rounded-xl border border-dashed border-gray-300 hover:bg-gray-100 transition">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Upload Surat Peminjaman (PDF) <span class="text-red-500">*</span></label>
                        
                        <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                            <input type="file" name="surat_peminjaman" accept=".pdf,.doc,.docx" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-utm-primary file:text-white hover:file:bg-utm-secondary transition cursor-pointer">
                            
                            <a href="{{ asset('files/Surat Peminjaman Lab TMJ.pdf') }}" download class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:text-utm-primary hover:border-utm-primary transition shadow-sm whitespace-nowrap">
                                <i class="bi bi-file-earmark-arrow-down-fill text-lg"></i>
                                <span>Download Contoh Surat</span>
                            </a>
                        </div>
                        <p class="text-xs text-gray-400 mt-2"><i class="bi bi-info-circle me-1"></i> Maksimal ukuran file 2MB. Format: PDF, DOC, DOCX.</p>
                        @error('surat_peminjaman')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-100">
                        <a href="{{ url()->previous() }}" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition text-sm">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-3 bg-utm-primary text-white font-bold rounded-xl hover:bg-utm-secondary transition shadow-lg shadow-blue-900/20 text-sm flex items-center gap-2">
                            <i class="bi bi-send-fill"></i> Kirim Pengajuan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection