<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use App\Models\JadwalLab;
use App\Models\PeminjamanLab;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PublicLabController extends Controller
{
    /**
     * Menampilkan Detail Lab dan Kalender Jadwalnya
     */
    public function show(Request $request, $id)
    {
        $lab = Lab::findOrFail($id);

        $viewType = $request->get('view', 'month');
        $dateParam = $request->get('date', now()->format('Y-m-d'));
        $currentDate = Carbon::parse($dateParam);

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

        $jadwals = JadwalLab::where('lab_id', $id)
                    ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->orderBy('jam_mulai', 'asc')
                    ->get()
                    ->groupBy('tanggal');

        return view('public-lab.show', compact(
            'lab', 'jadwals', 'viewType', 'currentDate', 'dateLabel',
            'prevDate', 'nextDate', 'startDate', 'firstDayOfWeek', 'daysInPeriod'
        ));
    }

    /**
     * Menampilkan Jadwal Gabungan Semua Lab
     */
    public function globalSchedule(Request $request)
    {
        $viewType = $request->get('view', 'month');
        $dateParam = $request->get('date', now()->format('Y-m-d'));
        $labId = $request->get('lab_id');
        
        $currentDate = Carbon::parse($dateParam);

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

        $query = JadwalLab::with('lab')
                    ->whereBetween('tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        if ($labId) {
            $query->where('lab_id', $labId);
        }

        $jadwals = $query->orderBy('jam_mulai', 'asc')
                    ->get()
                    ->groupBy('tanggal');

        $allLabs = Lab::all();

        return view('public-lab.schedule', compact(
            'jadwals', 'allLabs', 'labId', 'viewType', 'currentDate', 'dateLabel',
            'prevDate', 'nextDate', 'startDate', 'firstDayOfWeek', 'daysInPeriod'
        ));
    }

    /**
     * Menampilkan Form Pengajuan Peminjaman
     */
    public function createPeminjaman($lab_id, $jadwal_id = null)
    {
        $lab = Lab::findOrFail($lab_id);
        
        $selectedJadwal = null;
        if($jadwal_id) {
            $selectedJadwal = JadwalLab::find($jadwal_id);
        }

        return view('public-lab.peminjaman.create', compact('lab', 'selectedJadwal'));
    }

    /**
     * Menampilkan Halaman Sukses
     */
    public function success()
    {
        return view('public-lab.peminjaman.success');
    }

    /**
     * Proses Simpan Pengajuan Peminjaman ke Database
     */
    public function storePeminjaman(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'lab_id' => 'required|exists:lab,id_lab',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kegiatan' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20', // String agar 0 di depan aman
            'surat_peminjaman' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
        ]);

        // 2. Cek Bentrok Jadwal
        $isConflict = JadwalLab::where('lab_id', $request->lab_id)
            ->where('tanggal', $request->tanggal)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                      });
            })
            ->exists();

        if ($isConflict) {
            return back()->withErrors(['jam_mulai' => 'Jadwal bentrok dengan kegiatan lain pada waktu tersebut.'])->withInput();
        }

        // 3. Simpan Data (Transaction)
        try {
            DB::beginTransaction();

            // Upload File Surat
            $filePath = null;
            if ($request->hasFile('surat_peminjaman')) {
                $filePath = $request->file('surat_peminjaman')->store('surat_peminjaman', 'public');
            }

            // A. INSERT KE TABEL JADWAL_LAB (Status: Dipesan)
            $jadwal = JadwalLab::create([
                'lab_id' => $request->lab_id,
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'kegiatan' => $request->kegiatan,
                'status' => 'dipesan', 
                'pengisi' => Auth::user()->name
            ]);

            // B. INSERT KE TABEL PEMINJAMAN_LAB
            PeminjamanLab::create([
                'user_id' => Auth::id(),
                'lab_id' => $request->lab_id,
                'jadwal_id' => $jadwal->id,
                'tanggal_peminjaman' => now(),
                'kegiatan' => $request->kegiatan,
                'no_wa' => $request->no_wa,
                'surat_peminjaman' => $filePath,
                'status' => 'pending'   
            ]);

            DB::commit();

            // C. REDIRECT KE HALAMAN SUKSES
            return redirect()->route('peminjaman.success');

        } catch (\Exception $e) {
            DB::rollBack();
            // Kembalikan error ke halaman sebelumnya agar user tahu (bukan dd)
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan Riwayat Peminjaman User
     */
    public function riwayat()
    {
        $peminjamanLabs = PeminjamanLab::with(['lab', 'jadwal'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('public-lab.peminjaman.riwayat', compact('peminjamanLabs'));
    }

    /**
     * Membatalkan Peminjaman (User)
     */
    public function cancelPeminjaman($id)
    {
        // Cari data milik user yang login
        $peminjaman = PeminjamanLab::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();

        // Cek status
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Hanya peminjaman berstatus Pending yang bisa dibatalkan.');
        }

        try {
            DB::beginTransaction();

            // 1. Hapus Jadwal (Slot Booking)
            if ($peminjaman->jadwal_id) {
                JadwalLab::destroy($peminjaman->jadwal_id);
            }

            // 2. Hapus File Surat (Opsional)
            if ($peminjaman->surat_peminjaman && Storage::disk('public')->exists($peminjaman->surat_peminjaman)) {
                Storage::disk('public')->delete($peminjaman->surat_peminjaman);
            }

            // 3. Hapus Data Peminjaman
            $peminjaman->delete();

            DB::commit();

            return back()->with('success', 'Peminjaman berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan: ' . $e->getMessage());
        }
    }
}