<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lab;
use App\Models\JadwalLab;
use App\Models\PeminjamanLab;
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
        $this->command->info('Mulai seeding database...');

        // ==========================================
        // 1. BUAT USER (ADMIN & MAHASISWA)
        // ==========================================
        
        $this->command->info('Membuat user...');
        
        $admin = User::create([
            'name' => 'Admin Lab',
            'email' => 'admin@email.com',
            'nim' => '999999',
            'role' => 'admin',
            'jurusan' => 'Teknik Informatika',
            'password' => Hash::make('password123'),
        ]);
    
        $mahasiswa1 = User::create([
            'name' => 'Hanin Hammoud',
            'email' => 'hanin@email.com',
            'nim' => '230101001',
            'role' => 'user',
            'jurusan' => 'Teknik Informatika',
            'password' => Hash::make('password123'),
        ]);

        $mahasiswa2 = User::create([
            'name' => 'Linda Airdina',
            'email' => 'linda@email.com',
            'nim' => '230101002',
            'role' => 'user',
            'jurusan' => 'Teknik Informatika',
            'password' => Hash::make('password123'),
        ]);

        $mahasiswa3 = User::create([
            'name' => 'Ahmad Fayez',
            'email' => 'ahmad@email.com',
            'nim' => '230101003',
            'role' => 'user',
            'jurusan' => 'Teknik Informatika',
            'password' => Hash::make('password123'),
        ]);

        $this->command->info('User berhasil dibuat: ' . User::count() . ' user');

        // ==========================================
        // 2. BUAT DATA LABORATORIUM
        // ==========================================
        
        $this->command->info('Membuat laboratorium...');
        
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
            'fasilitas' => "1. Router & Switch Cisco\n2. PC Standar\n3. Kabel LAN & Crimping Tools\n4. Server Dedicated",
        ]);

        $lab3 = Lab::create([
            'id_admin' => $admin->id,
            'nama_lab' => 'Laboratorium Multimedia',
            'kapasitas' => 30,
            'lokasi' => 'Gedung D Lantai 1',
            'deskripsi' => 'Laboratorium untuk praktikum desain grafis dan video editing.',
            'fasilitas' => "1. iMac & PC Gaming\n2. Tablet Wacom\n3. Green Screen\n4. Kamera DSLR",
        ]);

        $this->command->info('Laboratorium berhasil dibuat: ' . Lab::count() . ' lab');

        // ==========================================
        // 3. BUAT JADWAL LAB
        // ==========================================
        
        $this->command->info('Membuat jadwal laboratorium...');
        
        $besok = Carbon::tomorrow()->format('Y-m-d');
        $lusa = Carbon::tomorrow()->addDay()->format('Y-m-d');
        
        // Jadwal Lab 1 (RPL) - Besok
        $jadwal1 = JadwalLab::create([
            'lab_id' => $lab1->id_lab,
            'tanggal' => $besok,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'status' => 'tersedia',
            'kegiatan' => 'Slot Kosong Pagi',
        ]);

        $jadwal2 = JadwalLab::create([
            'lab_id' => $lab1->id_lab,
            'tanggal' => $besok,
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'status' => 'terpakai',
            'kegiatan' => 'Praktikum Pemrograman Web Lanjut',
        ]);
        
        $jadwal3 = JadwalLab::create([
            'lab_id' => $lab1->id_lab,
            'tanggal' => $besok,
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
            'status' => 'tersedia',
            'kegiatan' => 'Slot Kosong Siang',
        ]);

        // Jadwal Lab 2 (Jaringan) - Besok
        $jadwal4 = JadwalLab::create([
            'lab_id' => $lab2->id_lab,
            'tanggal' => $besok,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'status' => 'tersedia',
            'kegiatan' => 'Slot Kosong Pagi',
        ]);

        // Jadwal Lab 3 (Multimedia) - Lusa
        $jadwal5 = JadwalLab::create([
            'lab_id' => $lab3->id_lab,
            'tanggal' => $lusa,
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
            'status' => 'tersedia',
            'kegiatan' => 'Slot Kosong',
        ]);

        $this->command->info('Jadwal berhasil dibuat: ' . JadwalLab::count() . ' jadwal');

        // ==========================================
        // 4. BUAT CONTOH PEMINJAMAN
        // ==========================================
        
        $this->command->info('Membuat data peminjaman...');
        
        // Peminjaman 1: PENDING
        PeminjamanLab::create([
            'user_id' => $mahasiswa1->id,
            'lab_id' => $lab1->id_lab,
            'jadwal_id' => $jadwal1->id,
            'kegiatan' => 'Praktikum Pemrograman Web - Materi Laravel Framework untuk membuat sistem informasi perpustakaan.',
            'no_wa' => '081234567890',
            'status' => 'pending',
            'surat_file' => null,
            'alasan_penolakan' => null,
        ]);
        
        // Peminjaman 2: DISETUJUI
        PeminjamanLab::create([
            'user_id' => $mahasiswa2->id,
            'lab_id' => $lab2->id_lab,
            'jadwal_id' => $jadwal4->id,
            'kegiatan' => 'Workshop React JS dan Node.js untuk anggota Himpunan Mahasiswa.',
            'no_wa' => '082345678901',
            'status' => 'disetujui',
            'surat_file' => null,
            'alasan_penolakan' => null,
        ]);
        
        // Peminjaman 3: DITOLAK
        PeminjamanLab::create([
            'user_id' => $mahasiswa3->id,
            'lab_id' => $lab1->id_lab,
            'jadwal_id' => $jadwal3->id,
            'kegiatan' => 'Penelitian Tugas Akhir tentang Machine Learning dan Deep Learning.',
            'no_wa' => '083456789012',
            'status' => 'ditolak',
            'surat_file' => null,
            'alasan_penolakan' => 'Maaf, jadwal bentrok dengan kegiatan praktikum reguler. Silakan pilih jadwal alternatif di hari lain.',
        ]);

        // Peminjaman 4: SELESAI
        PeminjamanLab::create([
            'user_id' => $mahasiswa1->id,
            'lab_id' => $lab3->id_lab,
            'jadwal_id' => $jadwal5->id,
            'kegiatan' => 'Ujian Praktikum Desain Grafis menggunakan Adobe Photoshop dan Illustrator.',
            'no_wa' => '081234567890',
            'status' => 'selesai',
            'surat_file' => null,
            'alasan_penolakan' => null,
        ]);

        // Peminjaman 5: PENDING
        PeminjamanLab::create([
            'user_id' => $mahasiswa2->id,
            'lab_id' => $lab1->id_lab,
            'jadwal_id' => $jadwal3->id,
            'kegiatan' => 'Pelatihan IoT dan Robotika untuk anggota UKM Teknologi.',
            'no_wa' => '082345678901',
            'status' => 'pending',
            'surat_file' => null,
            'alasan_penolakan' => null,
        ]);

        $this->command->info('Peminjaman berhasil dibuat: ' . PeminjamanLab::count() . ' peminjaman');

        // ==========================================
        // SUMMARY
        // ==========================================
        
        $this->command->newLine();
        $this->command->info('========================================');
        $this->command->info('SEEDING BERHASIL!');
        $this->command->info('========================================');
        $this->command->table(
            ['Data', 'Jumlah'],
            [
                ['Users', User::count()],
                ['Laboratorium', Lab::count()],
                ['Jadwal Lab', JadwalLab::count()],
                ['Peminjaman Lab', PeminjamanLab::count()],
            ]
        );
        $this->command->newLine();
        $this->command->info('Login Credentials:');
        $this->command->info('   Admin  : admin@email.com / password123');
        $this->command->info('   User 1 : hanin@email.com / password123');
        $this->command->info('   User 2 : linda@email.com / password123');
        $this->command->info('   User 3 : ahmad@email.com / password123');
        $this->command->newLine();
    }
}