<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminLabController;  // Controller Admin
use App\Http\Controllers\PublicLabController; // Controller Public
use App\Http\Controllers\BarangController; // Controller Public
use App\Models\Lab;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/

// Halaman Depan (Welcome)
Route::get('/', function () {
    $labs = Lab::all();
    return view('welcome', compact('labs'));
});

// Jadwal Global (Semua Lab)
Route::get('/jadwal', [PublicLabController::class, 'globalSchedule'])->name('public.schedule');

// Halaman Detail Lab
Route::get('/lab/{id}', [PublicLabController::class, 'show'])->name('public.lab.show');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Wajib Login)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::middleware('auth')->group(function () {
    
    // --- 1. Fitur Peminjaman (User/Mahasiswa) ---
    // Form Pengajuan
    Route::get('/peminjaman/create/{lab_id}/{jadwal_id?}', [PublicLabController::class, 'createPeminjaman'])
        ->name('peminjaman.create');
    
    // Proses Simpan Pengajuan
    Route::post('/peminjaman/store', [PublicLabController::class, 'storePeminjaman'])
        ->name('peminjaman.store');
        
    // Halaman Sukses
    Route::get('/peminjaman/sukses', [PublicLabController::class, 'success'])
        ->name('peminjaman.success');

    Route::get('/riwayat', [PublicLabController::class, 'riwayat'])->name('peminjaman.riwayat');
    Route::delete('/peminjaman/{id}/cancel', [PublicLabController::class, 'cancelPeminjaman'])->name('peminjaman.cancel');

    // --- 2. Manajemen Profil User (Bawaan Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // --- 3. Fitur Admin Kelola Lab ---
    Route::prefix('admin/lab')->group(function () {
        Route::get('/', [AdminLabController::class, 'index'])->name('admin.lab.index');   
        Route::get('/{id}', [AdminLabController::class, 'show'])->name('admin.lab.show'); 
        Route::post('/{id}/update-status', [AdminLabController::class, 'updateStatus'])->name('admin.lab.updateStatus');
        Route::get('/{id}/download-surat', [AdminLabController::class, 'downloadSurat'])->name('admin.lab.downloadSurat'); // ← Tambahkan ini jika belum ada
    });

    Route::prefix('admin/barang')->group(function(){
        Route::get('/', [BarangController::class, 'index'])->name('barang.index');
        Route::get('/barang', [BarangController::class, 'index'])->name('barang.all');
        Route::get('/create', [BarangController::class, 'create'])->name('create.view');
        Route::post('layananan_penyimpanan', [BarangController::class, 'storeadd'])->name('create.store');
        Route::get('/layanan_penyimpanan/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
        Route::put('/layanan_penyimpanan/{id}', [BarangController::class, 'update'])->name('barang.update');
        Route::delete('/layanan_penyimpanan/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
    });

});



// Memuat route autentikasi (Login, Register, dll)
require __DIR__.'/auth.php';