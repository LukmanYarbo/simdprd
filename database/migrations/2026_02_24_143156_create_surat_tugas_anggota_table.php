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
        Schema::create('surat_tugas_anggota', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat_tugas');
            $table->text('uraian')->nullable();
            $table->string('tempat_asal');
            $table->string('tempat_tujuan');
            $table->date('tanggal_berangkat');
            $table->date('tanggal_balik');
            $table->integer('lama_hari');
            $table->date('tanggal_ditetapkan');
            $table->unsignedBigInteger('id_anggota_penandatangan');
            $table->timestamps();

            $table->foreign('id_anggota_penandatangan')
                  ->references('id')
                  ->on('anggota')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tugas_anggota');
    }
};
