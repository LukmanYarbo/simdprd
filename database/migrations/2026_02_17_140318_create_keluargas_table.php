<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keluarga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_anggota')->constrained('anggota')->onDelete('cascade');
            $table->foreignId('id_ikatan_keluarga')->constrained('ikatan_keluarga')->onDelete('restrict');
            $table->foreignId('id_status_kawin')->constrained('status_kawin')->onDelete('restrict');
            $table->string('nik')->unique();
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->enum('jk', ['L', 'P']);
            $table->string('pekerjaan');
            $table->enum('status_anak', ['AK', 'AA'])->nullable(); // Anak Kandung, Anak Angkat
            $table->enum('status_tunjangan', ['Y', 'T']); // Ditunjang, Tidak
            $table->string('no_sk_pengadilan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluarga');
    }
};
