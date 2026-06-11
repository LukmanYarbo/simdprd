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
        Schema::table('pegawai_asns', function (Blueprint $table) {
            $table->enum('id_ttd', ['Y', 'T'])->default('T')->after('foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai_asns', function (Blueprint $table) {
            $table->dropColumn('id_ttd');
        });
    }
};
