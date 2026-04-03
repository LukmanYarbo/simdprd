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
        Schema::create('kertas_kerja_rincians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kertas_kerja_id')->constrained('kertas_kerjas')->onDelete('cascade');
            $table->string('kategori'); // e.g. Gaji Pokok, Tunjangan Jabatan, Alat Kelengkapan
            $table->string('jabatan'); // e.g. Ketua, Wakil Ketua, Sekretaris, Anggota
            $table->string('uraian'); // e.g. Tunjangan Komisi - Anggota
            $table->bigInteger('besaran')->default(0);
            $table->integer('orang')->default(1);
            $table->integer('bulan_kali')->default(12);
            $table->bigInteger('jumlah')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kertas_kerja_rincians');
    }
};
