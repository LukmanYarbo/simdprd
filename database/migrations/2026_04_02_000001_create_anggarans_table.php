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
        Schema::create('anggarans', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun_anggaran')->unique();
            $table->bigInteger('gaji_pokok')->default(0);
            $table->bigInteger('tunjangan_keluarga')->default(0);
            $table->bigInteger('tunjangan_jabatan')->default(0);
            $table->bigInteger('tunjangan_beras')->default(0);
            $table->bigInteger('tunjangan_pph')->default(0);
            $table->bigInteger('pembulatan')->default(0);
            $table->bigInteger('uang_paket')->default(0);
            $table->bigInteger('tunjangan_alat_kelengkapan')->default(0);
            $table->bigInteger('tunjangan_alat_kelengkapan_lainnya')->default(0);
            $table->bigInteger('tunjangan_perumahan')->default(0);
            $table->bigInteger('uang_jasa_pengabdian')->default(0);
            $table->bigInteger('tunjangan_reses')->default(0);
            $table->bigInteger('tunjangan_transportasi')->default(0);
            $table->bigInteger('jkk')->default(0);
            $table->bigInteger('jkm')->default(0);
            $table->bigInteger('tunjangan_komunikasi_insentif')->default(0);
            $table->string('status')->default('DRAFT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggarans');
    }
};
