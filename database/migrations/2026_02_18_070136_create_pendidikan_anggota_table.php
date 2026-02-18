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
        Schema::create('pendidikan_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_anggota')->constrained('anggota')->onDelete('cascade');
            $table->foreignId('id_jenis_pendidikan')->constrained('jenis_pendidikan');
            $table->string('tempat_pendidikan');
            $table->year('tahun_masuk')->nullable();
            $table->year('tahun_lulus')->nullable();
            $table->string('no_induk')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('no_ijazah')->nullable();
            $table->string('file_ijazah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendidikan_anggota');
    }
};
