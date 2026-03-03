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
        Schema::table('anggota', function (Blueprint $table) {
            $table->foreignId('id_skpd')->nullable()->after('id')->constrained('skpds')->onDelete('set null');
        });

        // Populate existing rows with the "Dewan Perwakilan Rakyat Daerah" SKPD ID
        $skpd = \App\Models\Skpd::where('namaskpd', 'Dewan Perwakilan Rakyat Daerah')->first();
        if ($skpd) {
            \DB::table('anggota')->whereNull('id_skpd')->update(['id_skpd' => $skpd->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropForeign(['id_skpd']);
            $table->dropColumn('id_skpd');
        });
    }
};
