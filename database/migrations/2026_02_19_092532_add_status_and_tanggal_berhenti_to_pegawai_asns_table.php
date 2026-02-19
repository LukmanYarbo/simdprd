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
        Schema::table('pegawai_asns', function (Blueprint $table) {
            $table->foreignId('id_status_pegawai')->after('id_jabatan')->constrained('status_pegawais')->onDelete('restrict');
            $table->date('tanggal_berhenti')->nullable()->after('tanggal_mulai_kerja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai_asns', function (Blueprint $table) {
            $table->dropForeign(['id_status_pegawai']);
            $table->dropColumn(['id_status_pegawai', 'tanggal_berhenti']);
        });
    }
};
