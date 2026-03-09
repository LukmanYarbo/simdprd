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
        Schema::create('potongans', function (Blueprint $table) {
            $table->id();
            $table->decimal('tunjangan_bpjs', 8, 2)->default(0)->comment('Persen Tunjangan BPJS');
            $table->decimal('potongan_bpjs', 8, 2)->default(0)->comment('Persen Potongan BPJS');
            $table->decimal('maksimal_potongan_bpjs', 15, 2)->default(0)->comment('Maksimal Potongan BPJS (Rupiah)');
            $table->decimal('jkk', 8, 2)->default(0)->comment('Persen JKK');
            $table->decimal('jkm', 8, 2)->default(0)->comment('Persen JKM');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potongans');
    }
};
