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
        // SQLite tidak support MODIFY COLUMN
        // Jadi kita skip atau drop & recreate table
        
        // OPSI 1: Skip (karena kolom status sudah dibuat di migration sebelumnya)
        // Tidak perlu lakukan apa-apa
        
        // OPSI 2: Jika benar-benar perlu update, drop & recreate
        // Schema::table('jadwal_lab', function (Blueprint $table) {
        //     $table->dropColumn('status');
        // });
        
        // Schema::table('jadwal_lab', function (Blueprint $table) {
        //     $table->enum('status', ['terpakai', 'dipesan', 'matakuliah', 'tersedia'])
        //           ->default('dipesan')
        //           ->after('jam_selesai');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback juga skip untuk SQLite
    }
};