<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanLab extends Model
{
    use HasFactory;

    // Arahkan ke tabel yang benar
    protected $table = 'peminjaman_lab';

    protected $guarded = ['id']; // Semua kolom boleh diisi kecuali ID

    // Relasi ke User (Mahasiswa)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Lab
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'id_lab');
    }

    // Relasi ke Jadwal
    public function jadwal()
    {
        return $this->belongsTo(JadwalLab::class, 'jadwal_id');
    }
}