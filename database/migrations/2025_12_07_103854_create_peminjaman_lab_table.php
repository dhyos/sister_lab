<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman_lab', function (Blueprint $table) {
            $table->id();

            // Relasi ke 3 tabel utama
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lab_id')->constrained('lab', 'id_lab')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwal_lab')->onDelete('cascade');

            // TAMBAHAN: Kolom kegiatan dan no_wa
            $table->text('kegiatan')->nullable();
            $table->string('no_wa', 20)->nullable();

            $table->string('surat_file')->nullable(); // Untuk URL file dari MinIO/Storage
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'selesai'])->default('pending');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();

            // Indexing untuk filter cepat di dashboard admin
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_lab');
    }
};