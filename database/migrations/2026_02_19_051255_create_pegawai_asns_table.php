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
        Schema::create('pegawai_asns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_skpd')->nullable()->constrained('skpds')->onDelete('set null');
            $table->string('nip')->unique();
            $table->string('nik')->unique();
            $table->string('nokk')->nullable();
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->foreignId('id_agama')->constrained('agama')->onDelete('restrict');
            $table->string('id_status_kawin');
            $table->foreignId('id_pangkat_golongan')->constrained('pangkat_golongans')->onDelete('restrict');
            $table->foreignId('id_jabatan')->constrained('jabatan_asns')->onDelete('restrict');
            $table->foreignId('id_status_pegawai')->constrained('status_pegawais')->onDelete('restrict');
            $table->date('tanggal_mulai_kerja')->nullable();
            $table->date('tanggal_berhenti')->nullable();
            $table->string('ket_jabatan')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('nohp')->nullable();
            $table->string('norek')->nullable();
            $table->string('npwp')->nullable();
            $table->string('foto')->nullable();
            $table->enum('id_ttd', ['Y', 'T'])->default('T');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai_asns');
    }
};
