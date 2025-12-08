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
        Schema::table('peminjaman_lab', function (Blueprint $table) {
            $table->string('no_wa', 20)->nullable()->after('kegiatan');
        });
    }
    
    public function down(): void
    {
        Schema::table('peminjaman_lab', function (Blueprint $table) {
            $table->dropColumn('no_wa');
        });
    }
};
