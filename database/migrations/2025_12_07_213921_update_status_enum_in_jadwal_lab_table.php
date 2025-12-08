<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menggunakan Raw SQL karena Doctrine DBAL (bawaan Laravel) 
        // kadang bermasalah mengubah kolom ENUM secara langsung.

        // Kita ubah definisi kolom status untuk menyertakan 'matakuliah'
        DB::statement("ALTER TABLE jadwal_lab MODIFY COLUMN status ENUM( 'terpakai', 'dipesan', 'matakuliah') NOT NULL DEFAULT 'dipesan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke opsi semula jika di-rollback
        // PERINGATAN: Data dengan status 'matakuliah' mungkin akan error atau hilang
        DB::statement("ALTER TABLE jadwal_lab MODIFY COLUMN status ENUM('tersedia', 'terpakai', 'dipesan') NOT NULL DEFAULT 'tersedia'");
    }
};