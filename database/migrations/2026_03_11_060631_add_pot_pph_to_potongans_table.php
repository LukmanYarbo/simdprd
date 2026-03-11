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
            $table->decimal('pot_pph', 5, 2)->default(0)->after('maks_jkkjkm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('potongans', function (Blueprint $table) {
            $table->dropColumn('pot_pph');
        });
    }
};
