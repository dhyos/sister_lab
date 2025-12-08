<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_lab_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab', function (Blueprint $table) {
            $table->id('id_lab'); // Primary Key custom sesuai model Anda
            // Relasi ke tabel users (admin lab)
            $table->foreignId('id_admin')->constrained('users')->onDelete('cascade');
            
            $table->string('nama_lab');
            $table->integer('kapasitas');
            $table->string('lokasi');
            $table->text('deskripsi')->nullable();
            $table->text('fasilitas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab');
    }
};