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
        Schema::create('anggaran_rincians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggaran_id')->constrained('anggarans')->onDelete('cascade');
            $table->string('kode_item');
            $table->string('uraian');
            $table->bigInteger('besaran')->default(0);
            $table->integer('orang')->default(0);
            $table->integer('bulan_kali')->default(0);
            $table->bigInteger('jumlah')->default(0);
            $table->bigInteger('sisa_pagu')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggaran_rincians');
    }
};
