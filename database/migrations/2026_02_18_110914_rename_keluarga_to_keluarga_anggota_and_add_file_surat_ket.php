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
        Schema::rename('keluarga', 'keluarga_anggota');
        Schema::table('keluarga_anggota', function (Blueprint $table) {
            $table->string('file_surat_ket')->nullable()->after('no_sk_pengadilan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keluarga_anggota', function (Blueprint $table) {
            $table->dropColumn('file_surat_ket');
        });
        Schema::rename('keluarga_anggota', 'keluarga');
    }
};
