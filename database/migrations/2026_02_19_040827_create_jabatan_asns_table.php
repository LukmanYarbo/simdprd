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
        Schema::create('jabatan_asns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_skpd')->nullable()->constrained('skpds')->onDelete('set null');
            $table->foreignId('id_esselon')->nullable()->constrained('esselons')->onDelete('set null');
            $table->string('nama_jabatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan_asns');
    }
};
