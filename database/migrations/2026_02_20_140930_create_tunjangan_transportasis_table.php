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
        Schema::create('tunjangan_transportasis', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_berlaku');
            $table->string('no_peraturan');
            $table->decimal('nilai_tunjangan_ketua', 15, 2)->default(0);
            $table->decimal('nilai_tunjangan_wakil', 15, 2)->default(0);
            $table->decimal('nilai_tunjangan_anggota', 15, 2)->default(0);
            $table->string('file_peraturan')->nullable();
            $table->enum('status', ['Y', 'T'])->default('Y');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tunjangan_transportasis');
    }
};
