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
        Schema::table('potongans', function (Blueprint $table) {
            $table->decimal('maks_jkkjkm', 15, 2)->default(0)->after('jkm')->comment('Maksimal JKK dan JKM (Rupiah)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('potongans', function (Blueprint $table) {
            $table->dropColumn('maks_jkkjkm');
        });
    }
};
