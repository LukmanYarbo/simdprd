<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarif_lapis_pajak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tarif_pajak')->constrained('tarif_pajak')->onDelete('cascade');
            $table->bigInteger('dari');
            $table->bigInteger('sampai')->nullable(); // null = tidak terbatas (lapis terakhir)
            $table->decimal('persen', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarif_lapis_pajak');
    }
};
