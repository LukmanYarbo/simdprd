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
        Schema::create('harta_anggotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_anggota')->constrained('anggota')->onDelete('cascade');
            $table->string('jenis_harta');
            $table->string('nama_harta');
            $table->text('keterangan')->nullable();
            $table->integer('tahun_perolehan');
            $table->bigInteger('harga_perolehan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harta_anggotas');
    }
};
