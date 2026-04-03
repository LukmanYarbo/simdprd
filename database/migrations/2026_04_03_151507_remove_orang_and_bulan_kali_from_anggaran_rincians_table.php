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
        Schema::table('anggaran_rincians', function (Blueprint $table) {
            $table->dropColumn(['orang', 'bulan_kali']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggaran_rincians', function (Blueprint $table) {
            $table->integer('orang')->default(0);
            $table->integer('bulan_kali')->default(0);
        });
    }
};
