<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarif_pajak', function (Blueprint $table) {
            $table->id();
            $table->string('no_peraturan');
            $table->date('tgl_berlaku');
            $table->bigInteger('ptkp');                        // PTKP Dasar (TK/0)
            $table->bigInteger('tambahan_ptkp_istri');         // Tambahan PTKP untuk Istri
            $table->bigInteger('tambahan_ptkp_tanggungan');    // Tambahan per Tanggungan
            $table->decimal('persen_biaya_jabatan', 5, 2);    // % Biaya Jabatan
            $table->bigInteger('max_biaya_jabatan');           // Maks Biaya Jabatan / Bulan
            $table->char('status', 1)->default('T');           // Y = Aktif, T = Tidak Aktif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarif_pajak');
    }
};
