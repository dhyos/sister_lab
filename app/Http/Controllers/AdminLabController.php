<?php

namespace App\Http\Controllers;

use App\Models\LabRequest;
use Illuminate\Http\Request;

class AdminLabController extends Controller
{
    // Menampilkan semua pengajuan lab
    public function index()
    {
        // Load data beserta relasinya
        $requests = LabRequest::with(['user', 'lab', 'jadwal'])
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('admin.lab-requests.index', compact('requests'));
    }

    // Menampilkan detail satu pengajuan
    public function show($id)
    {
        // Cari data berdasarkan ID
        $request = LabRequest::findOrFail($id);
        
        return view('admin.lab-requests.show', compact('request'));
    }

    // Update status pengajuan
    public function updateStatus(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak',
            'catatan_admin' => 'nullable|string'
        ]);

        // Cari data yang akan diupdate
        $labRequest = LabRequest::findOrFail($id);
        
        // Update status dan catatan
        $labRequest->status = $request->status;
        $labRequest->catatan_admin = $request->catatan_admin;
        $labRequest->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Status berhasil diperbarui!');
    }
}