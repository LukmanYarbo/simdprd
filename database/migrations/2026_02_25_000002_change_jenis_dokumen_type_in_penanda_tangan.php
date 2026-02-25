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
        Schema::table('penanda_tangan', function (Blueprint $table) {
            $table->string('jenis_dokumen')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penanda_tangan', function (Blueprint $table) {
            $table->enum('jenis_dokumen', ['Surat Tugas', 'SPPD', 'Surat Keputusan'])->change();
        });
    }
};
