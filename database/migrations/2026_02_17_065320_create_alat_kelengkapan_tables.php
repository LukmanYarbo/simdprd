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
        // 1. Tabel alat_kelengkapan
        Schema::create('alat_kelengkapan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('ket')->nullable();
            $table->string('nama_komisi')->nullable();
            $table->timestamps();
        });

        // 2. Tabel surat_keputusan
        Schema::create('surat_keputusan', function (Blueprint $table) {
            $table->id();
            $table->string('no_sk');
            $table->string('ket_sk')->nullable();
            $table->date('tgl_sk');
            $table->string('file_sk')->nullable();
            $table->string('status', 1)->default('T')->comment('A: Aktif, T: Tidak Aktif');
            $table->foreignId('id_alat_kelengkapan')
                  ->constrained('alat_kelengkapan')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
            $table->timestamps();
        });

        // 3. Tabel jabatan_anggota
        Schema::create('jabatan_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_alat_kelengkapan')
                  ->constrained('alat_kelengkapan')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
            
            $table->string('nama_komisi')->nullable();

            $table->foreignId('id_jabatan_alat_kelengkapan')
                  ->constrained('jabatan_alat_kelengkapan')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();

            $table->foreignId('id_anggota')
                  ->constrained('anggota')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();

            // Relasi ke surat_keputusan
            $table->foreignId('id_surat_keputusan')
                  ->constrained('surat_keputusan')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan_anggota');
        Schema::dropIfExists('surat_keputusan');
        Schema::dropIfExists('alat_kelengkapan');
    }
};
