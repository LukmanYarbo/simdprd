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
        Schema::create('penanda_tangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_skpd')->nullable()->constrained('skpds')->onDelete('set null');
            $table->foreignId('id_anggota')->nullable()->constrained('anggota')->onDelete('set null');
            $table->foreignId('id_pegawai_asn')->nullable()->constrained('pegawai_asns')->onDelete('set null');
            $table->enum('jenis_dokumen', ['Surat Tugas', 'SPPD', 'Surat Keputusan']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penanda_tangan');
    }
};
