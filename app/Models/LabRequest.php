<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini
use App\Models\User;      // Tambahkan ini
use App\Models\Lab;       // Tambahkan ini (Wajib)
use App\Models\JadwalLab;
class LabRequest extends Model
{
    use HasFactory;

    // 1. SESUAIKAN NAMA TABEL
    protected $table = 'peminjaman_lab';

    // 2. SESUAIKAN KOLOM YANG BISA DIISI (Sesuai gambar database Anda)
    protected $fillable = [
        'user_id',
        'lab_id',
        'jadwal_id',
        'surat_file',
        'status',
        'alasan_penolakan'
    ];

    // 3. DEFINISIKAN RELASI (PENTING!)
    
    // Relasi ke tabel User (untuk ambil Nama, NIM, Jurusan)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke tabel Lab (untuk ambil Nama Lab/Ruangan)
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'id_lab'); // Sesuaikan 'id_lab' dengan primary key tabel lab
    }

    // Relasi ke tabel Jadwal (untuk ambil Tanggal & Waktu)
    public function jadwal()
    {
        return $this->belongsTo(JadwalLab::class, 'jadwal_id'); // Pastikan Anda punya model JadwalLab
    }
}