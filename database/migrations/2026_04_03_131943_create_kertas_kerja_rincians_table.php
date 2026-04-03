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
        Schema::create('kertas_kerjas', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun_anggaran')->unique();
            $table->enum('status', ['DRAFT', 'FINAL'])->default('DRAFT');
            $table->bigInteger('total_pagu')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kertas_kerjas');
    }
};
