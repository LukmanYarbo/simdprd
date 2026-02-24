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
        Schema::create('anggota_st', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_surat_tugas_anggota');
            $table->unsignedBigInteger('id_anggota');
            $table->timestamps();

            $table->foreign('id_surat_tugas_anggota')
                  ->references('id')
                  ->on('surat_tugas_anggota')
                  ->onDelete('cascade');

            $table->foreign('id_anggota')
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
        Schema::dropIfExists('anggota_st');
    }
};
