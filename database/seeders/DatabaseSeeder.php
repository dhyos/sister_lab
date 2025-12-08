<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lab;
use App\Models\JadwalLab;
use App\Models\PeminjamanLab;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. BUAT USER (ADMIN & MAHASISWA)
        
        $admin = User::create([
            'name' => 'Admin Lab',
            'email' => 'admin@email.com',
            'nim' => '999999',
            'role' => 'admin',
            'jurusan' => 'Teknik Informatika',
            'password' => Hash::make('password123'),
        ]);
    
        $mahasiswa = User::create([
            'name' => 'Mahasiswa Test',
            'email' => 'mhs@email.com',
            'nim' => '111111',
            'role' => 'user',
            'jurusan' => 'Sistem Informasi',
            'password' => Hash::make('password123'),
        ]);

        // 2. BUAT DATA LABORATORIUM
        // Kita butuh ID Admin untuk kolom 'id_admin'
        
        $lab1 = Lab::create([
            'id_admin' => $admin->id,
            'nama_lab' => 'Laboratorium Rekayasa Perangkat Lunak (RPL)',
            'kapasitas' => 40,
            'lokasi' => 'Gedung C Lantai 2',
            'deskripsi' => 'Laboratorium untuk praktikum pemrograman web dan mobile.',
            'fasilitas' => "1. PC High Spec (i7, 16GB RAM)\n2. AC Dingin\n3. Proyektor HD\n4. Whiteboard",
        ]);

        $lab2 = Lab::create([
            'id_admin' => $admin->id,
            'nama_lab' => 'Laboratorium Jaringan Komputer',
            'kapasitas' => 35,
            'lokasi' => 'Gedung C Lantai 3',
            'deskripsi' => 'Laboratorium untuk praktikum jaringan, mikrotik, dan cisco.',
            'fasilitas' => "1. Router & Switch\n2. PC Standar\n3. Kabel LAN Crimping Tools",
        ]);

        // 3. BUAT JADWAL LAB
        // Menggunakan Carbon agar tanggalnya selalu 'Besok' dari hari Anda menjalankan seeder
        
        $besok = Carbon::tomorrow()->format('Y-m-d');
        
        // Jadwal 1: Tersedia (Pagi)
        $jadwal1 = JadwalLab::create([
            'lab_id' => $lab1->id_lab, // Sesuai primary key custom Lab
            'tanggal' => $besok,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'status' => 'tersedia',
            'kegiatan' => 'Slot Kosong Pagi',
        ]);

        // Jadwal 2: Terpakai (Siang) - Misal ada praktikum
        JadwalLab::create([
            'lab_id' => $lab1->id_lab,
            'tanggal' => $besok,
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'status' => 'terpakai',
            'kegiatan' => 'Praktikum Pemrograman Web Lanjut',
        ]);
        
        // Jadwal 3: Tersedia (Sore)
        $jadwal3 = JadwalLab::create([
            'lab_id' => $lab1->id_lab,
            'tanggal' => $besok,
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
            'status' => 'tersedia',
            'kegiatan' => 'Slot Kosong Siang',
        ]);

        // 4. BUAT CONTOH PEMINJAMAN (Agar Dashboard Admin tidak kosong)
        
        PeminjamanLab::create([
            'user_id' => $mahasiswa->id,
            'lab_id' => $lab1->id_lab,
            'jadwal_id' => $jadwal1->id, // Mahasiswa meminjam jadwal pagi
            'status' => 'pending',
            'surat_file' => null,
            'alasan_penolakan' => null,
        ]);
        
        // Update status jadwal menjadi 'dipesan' (jika logika aplikasi Anda mengharuskan update jadwal)
        // $jadwal1->update(['status' => 'dipesan']); 
    }
}