<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_jadwal_lab_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_lab', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel lab
            $table->foreignId('lab_id')->constrained('lab', 'id_lab')->onDelete('cascade');
            
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status', ['matakuliah', 'terpakai', 'dipesan'])->default('terpakai');
            $table->string('kegiatan');
            $table->timestamps();
            
            // Indexing untuk performa Read (Arsitektur Terdistribusi)
            $table->index(['tanggal', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_lab');
    }
};
