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
        Schema::table('transaksi_gaji', function (Blueprint $table) {
            $table->string('Kategori_TER')->nullable()->after('tunjangan_jkm');
            $table->double('Nilai_TER', 8, 2)->nullable()->after('Kategori_TER');
            $table->double('PPH21_Gaji')->nullable()->after('Nilai_TER');
            $table->double('PPh21_Tunjangan')->nullable()->after('PPH21_Gaji');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_gaji', function (Blueprint $table) {
            $table->dropColumn(['Kategori_TER', 'Nilai_TER', 'PPH21_Gaji', 'PPh21_Tunjangan']);
        });
    }
};
