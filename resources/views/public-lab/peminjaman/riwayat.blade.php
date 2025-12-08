@extends('layouts.public')

@section('title', 'Riwayat Peminjaman')

@section('content')
<header class="hero-pattern text-white py-12 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <h1 class="text-3xl font-extrabold tracking-tight mb-2">Riwayat Peminjaman</h1>
        <p class="text-blue-100">Pantau status pengajuan peminjaman laboratorium Anda.</p>
    </div>
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
</header>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-center gap-3">
                <i class="bi bi-check-circle-fill text-green-500 text-xl"></i>
                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm flex items-center gap-3">
                <i class="bi bi-x-circle-fill text-red-500 text-xl"></i>
                <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
            @if($peminjamanLabs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Laboratorium</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pemakaian</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($peminjamanLabs as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $item->lab->nama_lab }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->lab->lokasi }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">
                                            {{ \Carbon\Carbon::parse($item->jadwal->tanggal)->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($item->jadwal->jam_mulai)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($item->jadwal->jam_selesai)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate">
                                            {{ $item->kegiatan }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                            // Logic Tampilan Status
                                            $badgeClass = 'bg-gray-100 text-gray-800';
                                            $statusText = $item->status;

                                            if ($item->status == 'pending') {
                                                $badgeClass = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                                                $statusText = 'Dipesan'; // Tampil di User sebagai Dipesan
                                            } elseif ($item->status == 'disetujui') {
                                                $badgeClass = 'bg-green-100 text-green-800 border-green-200';
                                                $statusText = 'Disetujui';
                                            } elseif ($item->status == 'ditolak') {
                                                $badgeClass = 'bg-red-100 text-red-800 border-red-200';
                                                $statusText = 'Ditolak';
                                            }
                                        @endphp
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border {{ $badgeClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        
                                        {{-- 
                                            PERBAIKAN UTAMA DI SINI:
                                            Kita siapkan array PHP dulu, baru di-json_encode agar aman dari karakter aneh.
                                        --}}
                                        @php
                                            $modalData = [
                                                'id' => $item->id,
                                                'lab' => $item->lab->nama_lab,
                                                'kegiatan' => $item->kegiatan,
                                                'tanggal' => \Carbon\Carbon::parse($item->jadwal->tanggal)->translatedFormat('l, d F Y'),
                                                'jam' => \Carbon\Carbon::parse($item->jadwal->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($item->jadwal->jam_selesai)->format('H:i'),
                                                'status' => $item->status, // Tetap kirim status asli (pending/disetujui/ditolak) untuk logic JS
                                                'catatan' => $item->catatan_admin ?? 'Tidak ada catatan',
                                                'file' => asset('storage/' . $item->surat_peminjaman)
                                            ];
                                        @endphp

                                        <button 
                                            x-data
                                            @click="$dispatch('open-modal-detail', {{ json_encode($modalData) }})"
                                            class="text-utm-secondary hover:text-utm-primary transition font-bold bg-blue-50 px-4 py-2 rounded-lg border border-blue-100 hover:bg-blue-100 cursor-pointer shadow-sm"
                                        >
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="bi bi-inbox text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada riwayat</h3>
                    <div class="mt-6">
                        <a href="{{ route('public.schedule') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-utm-primary hover:bg-utm-secondary">
                            <i class="bi bi-plus-lg me-2"></i> Ajukan Peminjaman Baru
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div x-data="{ open: false, data: {} }" 
     @open-modal-detail.window="open = true; data = $event.detail; console.log(data);" 
     x-show="open" 
     x-cloak 
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <div x-show="open" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="open = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="open" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100">
            
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10 text-utm-primary">
                        <i class="bi bi-info-circle text-xl"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 flex justify-between items-center">
                            Detail Peminjaman
                            <button @click="open = false" class="text-gray-400 hover:text-gray-500"><i class="bi bi-x-lg"></i></button>
                        </h3>
                        
                        <div class="mt-4 space-y-4">
                            <div>
                                <span class="block text-[10px] text-gray-400 uppercase font-bold tracking-wider">Laboratorium</span>
                                <span class="block text-gray-900 font-bold text-base" x-text="data.lab"></span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <div>
                                    <span class="block text-[10px] text-gray-400 uppercase font-bold tracking-wider">Tanggal</span>
                                    <span class="block text-gray-800 font-medium text-sm" x-text="data.tanggal"></span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-gray-400 uppercase font-bold tracking-wider">Waktu</span>
                                    <span class="block text-gray-800 font-medium text-sm" x-text="data.jam"></span>
                                </div>
                            </div>

                            <div>
                                <span class="block text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Kegiatan</span>
                                <p class="text-gray-700 text-sm bg-gray-50 p-3 rounded-lg border border-gray-100 leading-relaxed" x-text="data.kegiatan"></p>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="block text-[10px] text-gray-400 uppercase font-bold tracking-wider">Status Pengajuan</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border"
                                      :class="{
                                          'bg-yellow-100 text-yellow-800 border-yellow-200': data.status === 'pending',
                                          'bg-green-100 text-green-800 border-green-200': data.status === 'disetujui',
                                          'bg-red-100 text-red-800 border-red-200': data.status === 'ditolak'
                                      }"
                                      x-text="data.status === 'pending' ? 'DIPESAN' : data.status">
                                </span>
                            </div>

                            <div x-show="data.catatan && data.catatan !== 'Tidak ada catatan'" class="bg-red-50 p-3 rounded-lg border border-red-100">
                                <span class="block text-[10px] text-red-500 uppercase font-bold tracking-wider mb-1">Catatan Admin</span>
                                <p class="text-red-700 text-sm" x-text="data.catatan"></p>
                            </div>

                            <div class="pt-2 text-center border-t border-gray-100 mt-2">
                                <a :href="data.file" target="_blank" class="inline-flex items-center text-sm font-medium text-utm-secondary hover:text-utm-primary hover:underline transition gap-2 mt-3">
                                    <i class="bi bi-file-earmark-pdf-fill text-lg"></i> Lihat Surat Peminjaman
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-4 py-4 sm:px-6 flex flex-col sm:flex-row-reverse gap-2">
                
                <button type="button" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm transition" @click="open = false">
                    Tutup
                </button>

                <template x-if="data.status === 'pending'">
                    <form :action="`/peminjaman/${data.id}/cancel`" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan peminjaman ini? Data jadwal yang sudah dipesan juga akan dihapus.');" class="sm:w-auto w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:w-auto sm:text-sm transition">
                            <i class="bi bi-trash me-2"></i> Batalkan Peminjaman
                        </button>
                    </form>
                </template>

            </div>
        </div>
    </div>
</div>
@endsection