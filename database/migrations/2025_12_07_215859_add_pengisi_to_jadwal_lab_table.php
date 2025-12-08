<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jadwal_lab', function (Blueprint $table) {
            // Menambahkan kolom 'pengisi' setelah kolom 'kegiatan'
            // Kita buat nullable() karena jika status 'tersedia', pengisinya kosong
            $table->string('pengisi')->nullable()->after('kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_lab', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn('pengisi');
        });
    }
};