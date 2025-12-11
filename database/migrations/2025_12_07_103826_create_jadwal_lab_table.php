<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_lab', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke lab (gunakan id_lab sebagai kolom tujuan)
            $table->foreignId('lab_id')
                  ->constrained('lab', 'id_lab')
                  ->onDelete('cascade');
            
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            
            // PENTING: Tambahkan 'tersedia' di enum!
            $table->enum('status', ['tersedia', 'terpakai', 'dipesan', 'matakuliah'])
                  ->default('tersedia');
            
            $table->string('kegiatan')->nullable();
            $table->string('pengisi')->nullable();
            
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['tanggal', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_lab');
    }
};