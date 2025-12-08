<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\JadwalLab;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicLabController extends Controller
{
    public function show(Request $request, $id)
    {
        $lab = Lab::findOrFail($id);

        // 1. Ambil Parameter Filter
        $viewType = $request->get('view', 'month'); // default: month
        $dateParam = $request->get('date', now()->format('Y-m-d'));
        $currentDate = Carbon::parse($dateParam);

        // 2. Tentukan Rentang Tanggal (Start & End) berdasarkan Tipe View
        if ($viewType === 'day') {
            $startDate = $currentDate->copy()->startOfDay();
            $endDate = $currentDate->copy()->endOfDay();
            $dateLabel = $currentDate->translatedFormat('l, d F Y');
            
            // Navigasi
            $prevDate = $currentDate->copy()->subDay()->format('Y-m-d');
            $nextDate = $currentDate->copy()->addDay()->format('Y-m-d');

        } elseif ($viewType === 'week') {
            $startDate = $currentDate->copy()->startOfWeek();
            $endDate = $currentDate->copy()->endOfWeek();
            $dateLabel = $startDate->translatedFormat('d M') . ' - ' . $endDate->translatedFormat('d M Y');

            // Navigasi
            $prevDate = $currentDate->copy()->subWeek()->format('Y-m-d');
            $nextDate = $currentDate->copy()->addWeek()->format('Y-m-d');

        } else { // Month (Default)
            $startDate = $currentDate->copy()->startOfMonth();
            $endDate = $currentDate->copy()->endOfMonth();
            $dateLabel = $currentDate->translatedFormat('F Y');

            // Navigasi
            $prevDate = $currentDate->copy()->subMonth()->format('Y-m-d');
            $nextDate = $currentDate->copy()->addMonth()->format('Y-m-d');
        }

        // 3. Data Pendukung Kalender (Khusus View Month)
        $firstDayOfWeek = $startDate->dayOfWeekIso; // 1 (Senin) - 7 (Minggu)
        $daysInPeriod = $viewType === 'month' ? $currentDate->daysInMonth : 7;

        // 4. Query Jadwal dalam Rentang Tanggal Tersebut
        $jadwals = JadwalLab::where('lab_id', $id)
                    ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->orderBy('jam_mulai', 'asc')
                    ->get()
                    ->groupBy('tanggal'); // Grouping biar mudah dipanggil: $jadwals['2025-12-01']

        return view('public-lab.show', compact(
            'lab', 
            'jadwals', 
            'viewType',
            'currentDate',
            'dateLabel',
            'prevDate',
            'nextDate',
            'startDate', // Penting untuk looping week/day
            'firstDayOfWeek',
            'daysInPeriod'
        ));
    }

    public function createPeminjaman($lab_id, $jadwal_id = null)
    {
        // ... logika peminjaman Anda ...
        return redirect()->route('login'); 
    }
    // Tambahkan method ini di dalam class PublicLabController

    public function globalSchedule(Request $request)
    {
        // 1. Ambil Parameter Filter
        $viewType = $request->get('view', 'month');
        $dateParam = $request->get('date', now()->format('Y-m-d'));
        $labId = $request->get('lab_id'); // Filter Lab (Opsional)
        
        $currentDate = \Carbon\Carbon::parse($dateParam);

        // 2. Tentukan Rentang Tanggal (Sama seperti sebelumnya)
        if ($viewType === 'day') {
            $startDate = $currentDate->copy()->startOfDay();
            $endDate = $currentDate->copy()->endOfDay();
            $dateLabel = $currentDate->translatedFormat('l, d F Y');
            $prevDate = $currentDate->copy()->subDay()->format('Y-m-d');
            $nextDate = $currentDate->copy()->addDay()->format('Y-m-d');
        } elseif ($viewType === 'week') {
            $startDate = $currentDate->copy()->startOfWeek();
            $endDate = $currentDate->copy()->endOfWeek();
            $dateLabel = $startDate->translatedFormat('d M') . ' - ' . $endDate->translatedFormat('d M Y');
            $prevDate = $currentDate->copy()->subWeek()->format('Y-m-d');
            $nextDate = $currentDate->copy()->addWeek()->format('Y-m-d');
        } else { // Month
            $startDate = $currentDate->copy()->startOfMonth();
            $endDate = $currentDate->copy()->endOfMonth();
            $dateLabel = $currentDate->translatedFormat('F Y');
            $prevDate = $currentDate->copy()->subMonth()->format('Y-m-d');
            $nextDate = $currentDate->copy()->addMonth()->format('Y-m-d');
        }

        $firstDayOfWeek = $startDate->dayOfWeekIso;
        $daysInPeriod = $viewType === 'month' ? $currentDate->daysInMonth : 7;

        // 3. Query Jadwal (Include relasi 'lab')
        $query = \App\Models\JadwalLab::with('lab') // Penting: ambil data labnya
                    ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        // Jika user memilih filter lab tertentu
        if ($labId) {
            $query->where('lab_id', $labId);
        }

        $jadwals = $query->orderBy('jam_mulai', 'asc')
                    ->get()
                    ->groupBy('tanggal');

        // Ambil daftar semua lab untuk dropdown filter
        $allLabs = \App\Models\Lab::all();

        return view('public-lab.schedule', compact(
            'jadwals', 'allLabs', 'labId',
            'viewType', 'currentDate', 'dateLabel',
            'prevDate', 'nextDate', 'startDate',
            'firstDayOfWeek', 'daysInPeriod'
        ));
    }
}