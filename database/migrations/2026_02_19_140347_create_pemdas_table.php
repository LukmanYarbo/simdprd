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
        Schema::create('pemdas', function (Blueprint $table) {
            $table->id();
            $table->string('namapemda');
            $table->text('alamat');
            $table->string('kota');
            $table->string('kabupaten');
            $table->string('propinsi');
            $table->string('kode_pos');
            
            // Bupati
            $table->string('nama_bupati');
            $table->string('jabatan_bupati');
            $table->string('judul_bupati');
            
            // Wakil Bupati
            $table->string('nama_wakil_bupati');
            $table->string('jabatan_wakil_bupati');
            $table->string('judul_wakil_bupati');
            
            // Sekda (Relation)
            $table->foreignId('id_sekda')->nullable()->constrained('pegawai_asns')->onDelete('set null');
            
            $table->string('logo_pemda')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemdas');
    }
};
