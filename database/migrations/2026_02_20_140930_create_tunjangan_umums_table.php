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
        Schema::create('tunjangan_umums', function (Blueprint $table) {
            $table->id();
            $table->decimal('tunjangan_beras', 15, 2)->default(0);
            $table->integer('jumlah_beras')->default(0);
            $table->integer('tunjangan_anak_persen')->default(0);
            $table->integer('tunjangan_istri_persen')->default(0);
            $table->enum('status', ['Y', 'T'])->default('Y');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tunjangan_umums');
    }
};
