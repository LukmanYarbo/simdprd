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
        Schema::create('dsb_gaji', function (Blueprint $table) {
            $table->id();
            $table->string('bln_thn')->index();
            $table->integer('jumlah_jiwa')->default(0);
            $table->integer('jumlah_pegawai')->default(0);
            $table->integer('jumlah_is')->default(0);
            $table->integer('jumlah_anak')->default(0);
            $table->integer('jumlah_ketua')->default(0);
            $table->integer('jumlah_wakil')->default(0);
            $table->integer('jumlah_anggota')->default(0);
            $table->integer('jumlah_is_ketua')->default(0);
            $table->integer('jumlah_anak_ketua')->default(0);
            $table->integer('jumlah_is_wakil')->default(0);
            $table->integer('jumlah_anak_wakil')->default(0);
            $table->integer('jumlah_is_anggota')->default(0);
            $table->integer('jumlah_anak_anggota')->default(0);
            $table->string('nama_pa')->nullable();
            $table->string('nip_pa')->nullable();
            $table->string('golongan_pa')->nullable();
            $table->string('jabatan_pa')->nullable();
            $table->string('nama_bendahara')->nullable();
            $table->string('nip_bendahara')->nullable();
            $table->string('golongan_bendahara')->nullable();
            $table->string('jabatan_bendahara')->nullable();
            $table->date('tanggal_proses')->nullable();
            $table->string('status')->default('FINAL');
            $table->text('alasan_perubahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dsb_gaji');
    }
};
