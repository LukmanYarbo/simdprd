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
        Schema::table('surat_keputusan', function (Blueprint $table) {
            $table->string('status', 1)->default('T')->after('file_sk')->comment('A: Aktif, T: Tidak Aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keputusan', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
