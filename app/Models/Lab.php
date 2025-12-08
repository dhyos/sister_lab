<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory;

    protected $table = 'lab';
    protected $primaryKey = 'id_lab'; // Penting karena PK Anda bukan 'id'

    protected $fillable = [
        'nama_lab', 'id_admin', 'kapasitas', 'deskripsi', 'lokasi', 'fasilitas'
    ];
}