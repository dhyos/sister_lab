@extends('layouts.public')

@section('title', 'Jadwal Seluruh Lab')

@section('content')
<div x-data="{ 
    modalOpen: false, 
    selectedDate: '', 
    dailySchedules: [],
    
    openModal(date, schedules) {
        this.selectedDate = date;
        this.dailySchedules = schedules;
        this.modalOpen = true;
    },
    
    formatTime(time) { return time.substring(0, 5); },

    getStatusColor(status) {
        if (status === 'matakuliah') return 'bg-red-100 text-red-800 border-red-200';
        if (status === 'terpakai') return 'bg-green-100 text-green-800 border-green-200';
        if (status === 'dipesan') return 'bg-yellow-100 text-yellow-800 border-yellow-200';
        return 'bg-gray-100 text-gray-600 border-gray-200';
    }
}">

    <header class="hero-pattern text-white py-12 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end gap-6">
                
                <div>
                    <a href="{{ url('/') }}" class="inline-flex items-center text-blue-200 hover:text-white transition font-medium mb-4 text-sm">
                        <i class="bi bi-arrow-left me-2"></i> Kembali ke Beranda
                    </a>
                    <h1 class="text-3xl font-extrabold tracking-tight mb-2 leading-tight">
                        Jadwal Seluruh Lab
                    </h1>
                    <p class="text-blue-100 text-lg max-w-2xl">
                        Pantau ketersediaan ruangan laboratorium secara real-time.
                    </p>
                </div>

                <div class="w-full md:w-auto bg-white/10 p-1.5 rounded-xl backdrop-blur-sm border border-white/20">
                    <form action="{{ route('public.schedule') }}" method="GET" class="flex w-full">
                        <input type="hidden" name="view" value="{{ $viewType }}">
                        <input type="hidden" name="date" value="{{ $currentDate->format('Y-m-d') }}">

                        <div class="relative w-full md:w-72">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                                <i class="bi bi-funnel-fill text-utm-secondary"></i>
                            </div>
                            <select name="lab_id" onchange="this.form.submit()" class="pl-10 pr-8 py-2.5 w-full border-0 rounded-lg text-sm font-bold text-gray-800 focus:ring-2 focus:ring-utm-accent cursor-pointer">
                                <option value="">-- Tampilkan Semua Lab --</option>
                                @foreach($allLabs as $l)
                                    <option value="{{ $l->id_lab }}" {{ $labId == $l->id_lab ? 'selected' : '' }}>
                                        {{ $l->nama_lab }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-20">
        
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex flex-col lg:flex-row justify-between items-center gap-4">
                
                <div class="flex flex-wrap gap-2 text-[10px] font-bold">
                    <span class="px-2 py-1 rounded bg-red-100 text-red-800 border border-red-200">Matakuliah</span>
                    <span class="px-2 py-1 rounded bg-green-100 text-green-800 border border-green-200">Terpakai</span>
                    <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-800 border border-yellow-200">Dipesan</span>
                    <span class="px-2 py-1 rounded bg-gray-100 text-gray-600 border border-gray-200">Tersedia</span>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="flex bg-white border border-gray-200 p-1 rounded-lg shadow-sm">
                        <a href="{{ route('public.schedule', array_merge(request()->all(), ['view' => 'month'])) }}" class="px-3 py-1 text-xs font-bold rounded-md transition {{ $viewType == 'month' ? 'bg-utm-primary text-white shadow' : 'text-gray-500 hover:bg-gray-100' }}">Month</a>
                        <a href="{{ route('public.schedule', array_merge(request()->all(), ['view' => 'week'])) }}" class="px-3 py-1 text-xs font-bold rounded-md transition {{ $viewType == 'week' ? 'bg-utm-primary text-white shadow' : 'text-gray-500 hover:bg-gray-100' }}">Week</a>
                        <a href="{{ route('public.schedule', array_merge(request()->all(), ['view' => 'day'])) }}" class="px-3 py-1 text-xs font-bold rounded-md transition {{ $viewType == 'day' ? 'bg-utm-primary text-white shadow' : 'text-gray-500 hover:bg-gray-100' }}">Day</a>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('public.schedule', array_merge(request()->all(), ['date' => $prevDate])) }}" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 rounded hover:bg-gray-100"><i class="bi bi-chevron-left"></i></a>
                        
                        <form action="{{ route('public.schedule') }}" method="GET">
                            <input type="hidden" name="view" value="{{ $viewType }}">
                            <input type="hidden" name="lab_id" value="{{ $labId }}">
                            <input type="{{ $viewType == 'month' ? 'month' : 'date' }}" name="date" value="{{ $viewType == 'month' ? $currentDate->format('Y-m') : $currentDate->format('Y-m-d') }}" onchange="this.form.submit()" class="py-1 px-3 border border-gray-300 rounded text-sm font-bold text-gray-700 cursor-pointer hover:bg-white focus:ring-utm-primary focus:border-utm-primary shadow-sm">
                        </form>

                        <a href="{{ route('public.schedule', array_merge(request()->all(), ['date' => $nextDate])) }}" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 rounded hover:bg-gray-100"><i class="bi bi-chevron-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                @if($viewType == 'month' || $viewType == 'week')
                    <div class="min-w-[1000px]">
                        <div class="grid grid-cols-7 border-b border-gray-200">
                            @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Mng'] as $hari)
                                <div class="text-center font-bold text-gray-500 text-xs py-2 uppercase bg-gray-50">{{ $hari }}</div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-7 border-l border-gray-200 bg-gray-100">
                            @if($viewType == 'month')
                                @for ($i = 1; $i < $firstDayOfWeek; $i++)
                                    <div class="h-36 bg-gray-50/50 border-r border-b border-gray-200"></div>
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

                                <div @click="openModal('{{ $dateDisplay }}', {{ json_encode($daySchedules) }})" class="h-36 bg-white border-r border-b border-gray-200 p-1 flex flex-col hover:bg-blue-50 cursor-pointer transition relative group">
                                    <div class="flex justify-between items-start mb-1">
                                        <span class="text-xs font-bold {{ $isToday ? 'bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center shadow' : 'text-gray-700 px-1' }}">
                                            {{ $iterDate->day }}
                                        </span>
                                        @if(count($daySchedules) > 0)
                                            <span class="text-[9px] bg-gray-200 px-1.5 rounded-full text-gray-600 font-bold">
                                                {{ count($daySchedules) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex-1 overflow-y-auto space-y-1 custom-scrollbar pr-1">
                                        @foreach($daySchedules as $jadwal)
                                            @php
                                                $statusColor = match($jadwal->status) {
                                                    'matakuliah' => 'bg-red-50 text-red-700 border-red-100',
                                                    'terpakai' => 'bg-green-50 text-green-700 border-green-100',
                                                    'dipesan' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                                    default => 'bg-gray-50 text-gray-600 border-gray-100'
                                                };
                                            @endphp
                                            <div class="text-[9px] p-1 rounded border {{ $statusColor }} leading-tight">
                                                <div class="font-bold flex justify-between">
                                                    <span>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</span>
                                                    <span class="uppercase tracking-tighter opacity-80 text-[8px] border px-0.5 rounded bg-white/50">
                                                        {{ substr($jadwal->lab->nama_lab, 0, 10) }}..
                                                    </span>
                                                </div>
                                                <div class="truncate opacity-90 mt-0.5">{{ $jadwal->kegiatan }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endfor

                            @if($viewType == 'month')
                                @php $remainingCells = (7 - (($daysInPeriod + $firstDayOfWeek - 1) % 7)) % 7; @endphp
                                @for ($i = 0; $i < $remainingCells; $i++)
                                    <div class="h-36 bg-gray-50/50 border-r border-b border-gray-200"></div>
                                @endfor
                            @endif
                        </div>
                    </div>
                
                {{-- VIEW: DAY --}}
                @elseif($viewType == 'day')
                    <div class="min-h-[400px]">
                        <div class="bg-gray-50 px-6 py-3 border-b font-bold text-gray-700">
                            Timeline: {{ $currentDate->translatedFormat('l, d F Y') }}
                        </div>
                        <div class="divide-y divide-gray-100">
                            @php $todaySchedules = $jadwals[$currentDate->format('Y-m-d')] ?? collect(); @endphp
                            @if($todaySchedules->count() > 0)
                                @foreach($todaySchedules as $jadwal)
                                    @php
                                        $borderClass = match($jadwal->status) {
                                            'matakuliah' => 'border-l-4 border-red-500 bg-red-50/30',
                                            'terpakai' => 'border-l-4 border-green-500 bg-green-50/30',
                                            'dipesan' => 'border-l-4 border-yellow-500 bg-yellow-50/30',
                                            default => 'border-l-4 border-gray-300'
                                        };
                                    @endphp
                                    <div class="p-4 flex flex-col sm:flex-row gap-4 {{ $borderClass }} hover:bg-gray-50 transition cursor-pointer" 
                                         @click="openModal('{{ $currentDate->translatedFormat('l, d F Y') }}', [{{ json_encode($jadwal) }}])">
                                        <div class="sm:w-32">
                                            <span class="block font-mono text-lg font-bold text-gray-800">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</span>
                                            <span class="block text-xs text-gray-500">s/d {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-white border shadow-sm text-utm-primary">
                                                    {{ $jadwal->lab->nama_lab }}
                                                </span>
                                                <span class="text-xs font-medium text-gray-500 uppercase">{{ $jadwal->status }}</span>
                                            </div>
                                            <h4 class="font-bold text-lg">{{ $jadwal->kegiatan }}</h4>
                                            <div class="text-sm text-gray-600 flex items-center gap-2 mt-1">
                                                <i class="bi bi-person-fill"></i> {{ $jadwal->pengisi ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="p-12 text-center text-gray-400">
                                    <i class="bi bi-calendar-x text-4xl mb-3 block"></i>
                                    Tidak ada kegiatan terjadwal pada hari ini.
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <div x-cloak x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" @click.self="modalOpen = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="bg-utm-primary px-6 py-4 flex justify-between items-center text-white">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <i class="bi bi-info-circle"></i> Detail Jadwal
                </h3>
                <button @click="modalOpen = false" class="text-white/70 hover:text-white"><i class="bi bi-x-lg text-xl"></i></button>
            </div>

            <div class="p-6 bg-gray-50 max-h-[70vh] overflow-y-auto custom-scrollbar">
                <div class="mb-4 text-center">
                    <span class="px-4 py-1 bg-white border border-gray-200 rounded-full text-sm font-bold text-utm-primary shadow-sm" x-text="selectedDate"></span>
                </div>

                <template x-if="dailySchedules.length === 0">
                    <div class="text-center py-8 text-gray-400">
                        <p>Tidak ada kegiatan pada tanggal ini.</p>
                    </div>
                </template>

                <div class="space-y-3">
                    <template x-for="schedule in dailySchedules" :key="schedule.id">
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <span class="px-2 py-1 rounded bg-blue-50 text-blue-700 text-[10px] font-bold uppercase border border-blue-100" 
                                      x-text="schedule.lab.nama_lab"></span>
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider" 
                                      :class="getStatusColor(schedule.status)" 
                                      x-text="schedule.status"></span>
                            </div>

                            <div class="text-right border-b border-gray-100 pb-2 mb-2">
                                <p class="font-mono font-bold text-gray-800 text-lg" 
                                   x-text="formatTime(schedule.jam_mulai) + ' - ' + formatTime(schedule.jam_selesai)"></p>
                            </div>
                            
                            <h4 class="text-gray-900 font-bold text-lg mb-1" x-text="schedule.kegiatan"></h4>
                            <div class="flex items-center gap-2 text-gray-500 text-sm mt-1">
                                <i class="bi bi-person-fill text-utm-accent"></i>
                                <span class="font-medium">Pengisi:</span>
                                <span x-text="schedule.pengisi ? schedule.pengisi : '-'"></span>
                            </div>

                            <template x-if="schedule.status === 'tersedia'">
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    @auth
                                        <a :href="`/peminjaman/create/${schedule.lab_id}/${schedule.id}`" 
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

</div>
@endsection