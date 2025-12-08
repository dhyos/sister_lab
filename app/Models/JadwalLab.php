<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalLab extends Model
{
    use HasFactory;

    protected $table = 'jadwal_lab';

    protected $fillable = [
        'lab_id', 
        'tanggal', 
        'jam_mulai', 
        'jam_selesai', 
        'status', 
        'kegiatan',
        'pengisi', // <--- TAMBAHKAN INI
    ];

    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id', 'id_lab');
    }
}