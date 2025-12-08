<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminLabController;  // Controller Admin
use App\Http\Controllers\PublicLabController; // Controller Public (Detail Lab & Jadwal Global)
use App\Models\Lab;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/

// Halaman Depan (Welcome)
Route::get('/', function () {
    // Ambil semua data lab dari database untuk ditampilkan di grid
    $labs = Lab::all();
    return view('welcome', compact('labs'));
});

// --- FITUR BARU: Jadwal Global (Semua Lab) ---
// Ini route untuk melihat kalender gabungan seluruh lab
Route::get('/jadwal', [PublicLabController::class, 'globalSchedule'])->name('public.schedule');

// Halaman Detail Lab Specific
Route::get('/lab/{id}', [PublicLabController::class, 'show'])->name('public.lab.show');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Wajib Login)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    
    // --- 1. Manajemen Profil User (Bawaan Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- 2. Fitur Peminjaman (User/Mahasiswa) ---
    // Route ini diakses saat tombol "Booking" atau "Ajukan" diklik
    Route::get('/peminjaman/create/{lab_id}/{jadwal_id?}', [PublicLabController::class, 'createPeminjaman'])
        ->name('peminjaman.create');

    // --- 3. Fitur Admin Kelola Lab ---
    // Sebaiknya nanti ditambahkan middleware 'admin' jika sudah ada role
    Route::prefix('admin/lab')->group(function () {
        // This creates the URL: /admin/lab
        Route::get('/', [AdminLabController::class, 'index'])->name('admin.lab.index');
        
        Route::get('/{id}', [AdminLabController::class, 'show'])->name('admin.lab.show');
        Route::post('/{id}/update-status', [AdminLabController::class, 'updateStatus'])->name('admin.lab.updateStatus');
    });

});

// Memuat route autentikasi (Login, Register, dll)
require __DIR__.'/auth.php';