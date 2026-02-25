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
            $table->foreignId('id_skpd')->nullable()->after('id')->constrained('skpds')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai_asns', function (Blueprint $table) {
            $table->dropForeign(['id_skpd']);
            $table->dropColumn('id_skpd');
        });
    }
};
