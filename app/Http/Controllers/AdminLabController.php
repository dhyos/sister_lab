<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanLab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class AdminLabController extends Controller
{
    /**
     * Menampilkan semua pengajuan laboratorium
     * Filter: semua, pending, disetujui, ditolak
     */
    public function index(Request $request)
    {
        // Ambil filter status dari query parameter
        $status = $request->query('status', 'semua');
        
        // Query dengan eager loading untuk optimasi
        $query = PeminjamanLab::with(['user', 'lab', 'jadwal'])
                    ->orderBy('created_at', 'desc');
        
        // Filter berdasarkan status jika bukan "semua"
        if ($status !== 'semua') {
            $query->where('status', $status);
        }
        
        $pengajuan = $query->paginate(15);
        
        // Hitung statistik untuk card dashboard
        $stats = [
            'total' => PeminjamanLab::count(),
            'pending' => PeminjamanLab::where('status', 'pending')->count(),
            'disetujui' => PeminjamanLab::where('status', 'disetujui')->count(),
            'ditolak' => PeminjamanLab::where('status', 'ditolak')->count(),
            'selesai' => PeminjamanLab::where('status', 'selesai')->count(),
        ];

        return view('admin.lab-requests.index', compact('pengajuan', 'stats', 'status'));
    }

    /**
     * Menampilkan detail pengajuan spesifik
     */
    public function show($id)
    {
        // Cari pengajuan dengan relasi lengkap
        $pengajuan = PeminjamanLab::with(['user', 'lab', 'jadwal'])
                        ->findOrFail($id);

        return view('admin.lab-requests.show', compact('pengajuan'));
    }

    /**
     * Update status pengajuan (Approve/Reject)
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak,selesai',
            'alasan_penolakan' => 'required_if:status,ditolak|nullable|string|max:500'
        ], [
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
            'alasan_penolakan.required_if' => 'Alasan penolakan wajib diisi jika menolak pengajuan',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 500 karakter'
        ]);

        // Cari pengajuan
        $pengajuan = PeminjamanLab::findOrFail($id);

        // Update status (HAPUS validasi status final agar bisa diubah kapan saja)
        $pengajuan->status = $validated['status'];
        
        // Simpan alasan penolakan jika ditolak
        if ($validated['status'] === 'ditolak') {
            $pengajuan->alasan_penolakan = $validated['alasan_penolakan'];
        } else {
            $pengajuan->alasan_penolakan = null;
        }
        
        $pengajuan->save();

        // Pesan sukses berdasarkan status
        $messages = [
            'pending' => 'Status dikembalikan ke pending',
            'disetujui' => 'Pengajuan berhasil disetujui',
            'ditolak' => 'Pengajuan ditolak',
            'selesai' => 'Pengajuan ditandai selesai'
        ];

        return redirect()->back()->with('success', $messages[$validated['status']]);
    }

    /**
     * Download file surat pengajuan
     */
    public function downloadSurat($id)
    {
        $pengajuan = PeminjamanLab::findOrFail($id);
        
        // Cek apakah file surat ada
        if (!$pengajuan->surat_file) {
            return redirect()->back()->with('error', 'File surat tidak ditemukan');
        }

        // Cek apakah file exists di storage
        if (!Storage::disk('public')->exists($pengajuan->surat_file)) {
            return redirect()->back()->with('error', 'File surat tidak ada di server');
        }

        // Download file
        $filename = 'Surat_Pengajuan_' . $pengajuan->user->name . '_' . $pengajuan->id . '.pdf';
        return Response::download(
            storage_path('app/public/' . $pengajuan->surat_file),
            $filename
        );
    }
}