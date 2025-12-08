
@extends('layouts.public')

@section('title', 'Detail ' . $lab->nama_lab)

@section('content')
<body class="antialiased font-sans bg-gray-50 text-gray-800" 
      x-data="{ 
          modalOpen: false, 
          selectedDate: '', 
          dailySchedules: [],
          labId: {{ $lab->id_lab }},
          
          openModal(date, schedules) {
              this.selectedDate = date;
              this.dailySchedules = schedules;
              this.modalOpen = true;
          },
          
          formatTime(time) {
              return time.substring(0, 5); 
          },

          getStatusColor(status) {
              if (status === 'matakuliah') return 'bg-red-100 text-red-800 border-red-200';
              if (status === 'terpakai') return 'bg-green-100 text-green-800 border-green-200';
              if (status === 'dipesan') return 'bg-yellow-100 text-yellow-800 border-yellow-200';
              return 'bg-gray-100 text-gray-600 border-gray-200';
          }
      }">

      <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-utm-primary rounded-lg flex items-center justify-center text-white font-bold text-xl">
                            TI
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-utm-primary text-lg leading-tight">Laboratorium</span>
                            <span class="text-xs text-gray-500 font-medium">Teknik Informatika UTM</span>
                        </div>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ url('/') }}#lab-info" class="text-gray-600 hover:text-utm-primary font-medium transition">Laboratorium</a>
                    <a href="{{ route('public.schedule') }}" class="text-gray-600 hover:text-utm-primary font-medium transition">Jadwal</a>
                    <a href="{{ url('/') }}#barang" class="text-gray-600 hover:text-utm-primary font-medium transition">Peminjaman Barang</a>
                </div>

                <div class="flex items-center space-x-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-utm-primary text-white rounded-full font-medium hover:bg-utm-secondary transition shadow-lg shadow-blue-900/20 text-sm">
                                Dashboard Saya
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-utm-primary font-medium hover:bg-blue-50 rounded-lg transition text-sm">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2 bg-utm-primary text-white rounded-full font-medium hover:bg-utm-secondary transition shadow-md text-sm">
                                    Daftar Akun
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <header class="bg-utm-primary text-white py-8 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="mb-6">
                <a href="{{ url('/') }}" class="inline-flex items-center text-blue-200 hover:text-white transition font-medium hover:-translate-x-1 duration-200">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Beranda
                </a>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                <div class="w-full lg:w-1/3 shrink-0">
                    <div class="rounded-xl overflow-hidden shadow-2xl border-4 border-white/20 aspect-video relative bg-gray-800">
                        <img src="https://placehold.co/600x400/003366/FFFFFF?text={{ urlencode($lab->nama_lab) }}" alt="{{ $lab->nama_lab }}" class="w-full h-full object-cover">
                    </div>
                </div>

                <div class="w-full lg:w-2/3 pt-1">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="px-3 py-1 bg-blue-500/30 border border-blue-400 rounded-full text-xs font-semibold backdrop-blur-md">
                            <i class="bi bi-people-fill me-1"></i> Kapasitas: {{ $lab->kapasitas }}
                        </span>
                        <span class="px-3 py-1 bg-yellow-500/20 border border-yellow-400 text-yellow-300 rounded-full text-xs font-semibold backdrop-blur-md">
                            <i class="bi bi-building-fill me-1"></i> Gedung Lab Terpadu
                        </span>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold mb-4 leading-tight tracking-tight">{{ $lab->nama_lab }}</h1>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
                        <p class="text-utm-accent text-lg font-medium flex items-center">
                            <i class="bi bi-geo-alt-fill me-2"></i> {{ $lab->lokasi }}
                        </p>

                        @auth
                            <a href="{{ route('peminjaman.create', ['lab_id' => $lab->id_lab]) }}" class="inline-flex items-center px-5 py-2 bg-white text-utm-primary font-bold rounded-lg hover:bg-gray-100 transition shadow-lg text-sm">
                                <i class="bi bi-calendar-plus me-2"></i> Ajukan Peminjaman
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2 bg-white text-utm-primary font-bold rounded-lg hover:bg-gray-100 transition shadow-lg text-sm">
                                <i class="bi bi-lock-fill me-2"></i> Login untuk Meminjam
                            </a>
                        @endauth
                    </div>

                    <div class="bg-white/10 rounded-xl p-5 border border-white/10 backdrop-blur-sm">
                        <h3 class="text-sm font-bold text-blue-200 uppercase tracking-wider mb-2">Tentang Laboratorium</h3>
                        <p class="text-gray-100 leading-relaxed text-justify text-sm md:text-base">
                            {{ $lab->deskripsi }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 md:p-8">
                <h3 class="text-xl font-bold text-utm-primary mb-4 flex items-center border-b pb-3 border-gray-100">
                    <i class="bi bi-pc-display-horizontal me-3 text-lg"></i> Fasilitas Tersedia
                </h3>
                <div class="bg-blue-50/50 p-6 rounded-xl border border-blue-100 h-full">
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {!! $lab->fasilitas !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden" id="jadwal">
            
            <div class="p-6 bg-gray-50 border-b border-gray-200 flex flex-col lg:flex-row justify-between items-center gap-4">
                <div class="flex flex-col gap-1">
                    <h3 class="text-xl font-bold text-utm-primary flex items-center">
                        <i class="bi bi-calendar-week-fill me-3"></i> Jadwal Pemakaian Lab
                    </h3>
                    <div class="flex flex-wrap gap-2 text-[10px] font-bold mt-1">
                        <span class="px-2 py-0.5 rounded bg-red-100 text-red-800 border border-red-200">Matakuliah</span>
                        <span class="px-2 py-0.5 rounded bg-green-100 text-green-800 border border-green-200">Terpakai</span>
                        <span class="px-2 py-0.5 rounded bg-yellow-100 text-yellow-800 border border-yellow-200">Dipesan</span>
                        <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600 border border-gray-200">Tersedia</span>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <a href="{{ route('public.lab.show', ['id' => $lab->id_lab, 'view' => 'month', 'date' => $currentDate->format('Y-m-d')]) }}" class="px-3 py-1 text-xs font-bold rounded-md transition {{ $viewType == 'month' ? 'bg-white shadow text-utm-primary' : 'text-gray-500 hover:text-gray-700' }}">Month</a>
                        <a href="{{ route('public.lab.show', ['id' => $lab->id_lab, 'view' => 'week', 'date' => $currentDate->format('Y-m-d')]) }}" class="px-3 py-1 text-xs font-bold rounded-md transition {{ $viewType == 'week' ? 'bg-white shadow text-utm-primary' : 'text-gray-500 hover:text-gray-700' }}">Week</a>
                        <a href="{{ route('public.lab.show', ['id' => $lab->id_lab, 'view' => 'day', 'date' => $currentDate->format('Y-m-d')]) }}" class="px-3 py-1 text-xs font-bold rounded-md transition {{ $viewType == 'day' ? 'bg-white shadow text-utm-primary' : 'text-gray-500 hover:text-gray-700' }}">Day</a>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('public.lab.show', ['id' => $lab->id_lab, 'view' => $viewType, 'date' => $prevDate]) }}" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 rounded hover:bg-gray-100 text-sm"><i class="bi bi-chevron-left"></i></a>
                        <form action="{{ route('public.lab.show', ['id' => $lab->id_lab]) }}" method="GET" class="relative">
                            <input type="hidden" name="view" value="{{ $viewType }}">
                            <input type="{{ $viewType == 'month' ? 'month' : 'date' }}" name="date" value="{{ $viewType == 'month' ? $currentDate->format('Y-m') : $currentDate->format('Y-m-d') }}" onchange="this.form.submit()" class="py-1 px-3 border border-gray-300 rounded text-sm font-bold text-gray-700 focus:ring-utm-primary focus:border-utm-primary cursor-pointer hover:bg-gray-50">
                        </form>
                        <a href="{{ route('public.lab.show', ['id' => $lab->id_lab, 'view' => $viewType, 'date' => $nextDate]) }}" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 rounded hover:bg-gray-100 text-sm"><i class="bi bi-chevron-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="p-4 md:p-6 overflow-x-auto">
                
                {{-- VIEW: MONTH & WEEK --}}
                @if($viewType == 'month' || $viewType == 'week')
                    <div class="grid grid-cols-7 gap-1 mb-1 min-w-[800px]">
                        @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Mng'] as $hari)
                            <div class="text-center font-bold text-gray-500 text-xs py-2 uppercase bg-gray-100 rounded-t">{{ $hari }}</div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-7 gap-1 min-w-[800px] border-l border-t border-gray-200 bg-gray-200">
                        @if($viewType == 'month')
                            @for ($i = 1; $i < $firstDayOfWeek; $i++)
                                <div class="h-32 bg-gray-50 border-r border-b border-gray-200"></div>
                            @endfor
                        @endif

                        @php $loopStart = $viewType == 'month' ? 1 : 0; $loopEnd = $viewType == 'month' ? $daysInPeriod : 6; @endphp

                        @for ($i = $loopStart; $i <= $loopEnd; $i++)
                            @php
                                $iterDate = ($viewType == 'month') ? $startDate->copy()->day($i) : $startDate->copy()->addDays($i);
                                $dateStr = $iterDate->format('Y-m-d');
                                $isToday = $dateStr == now()->format('Y-m-d');
                                $daySchedules = $jadwals[$dateStr] ?? [];
                                $dateDisplay = $iterDate->translatedFormat('l, d F Y');
                            @endphp

                            <div @click="openModal('{{ $dateDisplay }}', {{ json_encode($daySchedules) }})" 
                                 class="min-h-[140px] bg-white border-r border-b border-gray-200 relative group p-1 flex flex-col hover:bg-blue-50 cursor-pointer transition">
                                
                                <div class="flex justify-between items-start mb-1 pointer-events-none">
                                    <span class="text-xs font-bold {{ $isToday ? 'bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center shadow' : 'text-gray-700 px-1' }}">
                                        {{ $iterDate->day }}
                                    </span>
                                </div>

                                <div class="flex-1 space-y-1 overflow-y-auto custom-scrollbar pr-1 max-h-[120px] pointer-events-none">
                                    @foreach($daySchedules as $jadwal)
                                        @php
                                            $statusColor = match($jadwal->status) {
                                                'matakuliah' => 'bg-red-100 border-red-200 text-red-800',
                                                'terpakai' => 'bg-green-100 border-green-200 text-green-800',
                                                'dipesan' => 'bg-yellow-100 border-yellow-200 text-yellow-800',
                                                default => 'bg-gray-100 border-gray-200 text-gray-800'
                                            };
                                        @endphp
                                        <div class="text-[10px] p-1.5 rounded border {{ $statusColor }} leading-tight shadow-sm">
                                            <div class="font-bold">
                                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                            </div>
                                            <div class="truncate font-medium">{{ $jadwal->kegiatan }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endfor

                        @if($viewType == 'month')
                            @php $remainingCells = (7 - (($daysInPeriod + $firstDayOfWeek - 1) % 7)) % 7; @endphp
                            @for ($i = 0; $i < $remainingCells; $i++)
                                <div class="h-32 bg-gray-50 border-r border-b border-gray-200"></div>
                            @endfor
                        @endif
                    </div>

                {{-- VIEW: DAY --}}
                @elseif($viewType == 'day')
                    <div class="bg-white border rounded-xl overflow-hidden min-h-[400px]">
                        <div class="bg-gray-50 px-6 py-3 border-b font-bold text-gray-700 flex justify-between">
                            <span>Jadwal: {{ $currentDate->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @php $todaySchedules = $jadwals[$currentDate->format('Y-m-d')] ?? collect(); @endphp
                            @if($todaySchedules->count() > 0)
                                @foreach($todaySchedules as $jadwal)
                                    {{-- Kode Day View Anda sebelumnya sudah bagus, bisa dipakai di sini --}}
                                    {{-- Disini saya singkat karena fokus kita adalah memperbaiki interaksi kalender --}}
                                    <div class="p-4 flex gap-4 hover:bg-gray-50 cursor-pointer" @click="openModal('{{ $currentDate->translatedFormat('l, d F Y') }}', [{{ json_encode($jadwal) }}])">
                                        <div class="w-32 font-bold text-gray-700">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</div>
                                        <div class="flex-1">
                                            <div class="font-bold text-lg">{{ $jadwal->kegiatan }}</div>
                                            <div class="text-sm text-gray-500">{{ $jadwal->pengisi ?? '-' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="p-10 text-center text-gray-400">Tidak ada jadwal.</div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <div x-cloak x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" @click.self="modalOpen = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <div class="bg-utm-primary px-6 py-4 flex justify-between items-center text-white">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <i class="bi bi-calendar-event"></i> Detail Jadwal
                </h3>
                <button @click="modalOpen = false" class="text-white/70 hover:text-white transition">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>

            <div class="p-6 bg-gray-50 max-h-[60vh] overflow-y-auto custom-scrollbar">
                <div class="mb-4 text-center">
                    <span class="px-4 py-1 bg-white border border-gray-200 rounded-full text-sm font-bold text-utm-primary shadow-sm" x-text="selectedDate"></span>
                </div>

                <template x-if="dailySchedules.length === 0">
                    <div class="text-center py-8 text-gray-400">
                        <i class="bi bi-calendar-x text-4xl mb-2 block"></i>
                        <p>Tidak ada kegiatan pada tanggal ini.</p>
                        @auth
                        <a :href="`/peminjaman/create/${labId}`" class="mt-4 inline-block px-4 py-2 bg-utm-secondary text-white text-sm font-bold rounded-lg hover:bg-blue-700">
                            Ajukan Peminjaman
                        </a>
                        @endauth
                    </div>
                </template>

                <div class="space-y-3">
                    <template x-for="schedule in dailySchedules" :key="schedule.id">
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider" 
                                          :class="getStatusColor(schedule.status)" 
                                          x-text="schedule.status"></span>
                                </div>
                                <div class="text-right">
                                    <p class="font-mono font-bold text-gray-800 text-lg" 
                                       x-text="formatTime(schedule.jam_mulai) + ' - ' + formatTime(schedule.jam_selesai)"></p>
                                </div>
                            </div>
                            
                            <h4 class="text-gray-900 font-bold text-lg mb-1" x-text="schedule.kegiatan"></h4>
                            
                            <div class="flex items-center gap-2 text-gray-500 text-sm mt-2 pt-2 border-t border-gray-100">
                                <i class="bi bi-person-fill text-utm-accent"></i>
                                <span class="font-medium">Pengisi:</span>
                                <span x-text="schedule.pengisi ? schedule.pengisi : '-'"></span>
                            </div>

                            <template x-if="schedule.status === 'tersedia'">
                                <div class="mt-3">
                                    @auth
                                        <a :href="`/peminjaman/create/${labId}/${schedule.id}`" 
                                           class="block w-full text-center px-4 py-2 bg-utm-success text-white text-sm font-bold rounded-lg hover:bg-green-700 transition">
                                            Booking Slot Ini
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 bg-gray-100 text-gray-500 text-sm font-bold rounded-lg hover:bg-gray-200">
                                            Login untuk Booking
                                        </a>
                                    @endauth
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-900 text-white py-10 mt-12 text-center text-sm border-t border-gray-800">
        <p class="mb-2 font-bold text-gray-200 text-lg">Laboratorium Teknik Informatika</p>
        <p class="text-gray-500">&copy; {{ date('Y') }} Universitas Trunojoyo Madura. All rights reserved.</p>
    </footer>
</body>
</html>