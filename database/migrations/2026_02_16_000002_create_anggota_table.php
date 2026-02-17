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
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nokk');
            $table->string('nama_anggota');
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->foreignId('id_agama')
                ->constrained('agama')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->enum('jk', ['L', 'P']);
            $table->foreignId('id_status_kawin')
                ->constrained('status_kawin')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->integer('jmlh_istri')->default(0);
            $table->integer('jmlh_anak')->default(0);
            $table->string('no_telp');
            $table->string('email')->unique();
            $table->string('no_rekening');
            $table->string('prov');
            $table->string('kab');
            $table->string('kec');
            $table->string('desa');
            $table->string('alamat_lengkap');
            $table->foreignId('id_status_keanggotaan')
                ->constrained('status_keanggotaan')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('id_jabatan')
                ->constrained('jabatan')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->date('tgl_mulai');
            $table->date('tgl_berhenti')->nullable();

            $table->enum('status_bpjs', ['Y', 'T'])->default('T');
            $table->string('no_bpjs')->nullable();
            $table->enum('status_jkk', ['Y', 'T'])->default('T');
            $table->string('no_jkk')->nullable();
            $table->enum('status_jkm', ['Y', 'T'])->default('T');
            $table->string('no_jkm')->nullable();
            $table->enum('status_tjgn_perum', ['Y', 'T'])->default('T');
            $table->enum('status_tjgn_transport', ['Y', 'T'])->default('T');
            $table->string('foto_anggota')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
