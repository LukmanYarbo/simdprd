<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('status_kawin', function (Blueprint $table) {
            $table->string('kode')->nullable()->after('id');
        });

        // Update existing records
        DB::table('status_kawin')->where('nama', 'Belum Kawin')->update(['kode' => 'T']);
        DB::table('status_kawin')->where('nama', 'Kawin')->update(['kode' => 'K']);
        DB::table('status_kawin')->where('nama', 'Cerai Mati')->update(['kode' => 'CM']);
        DB::table('status_kawin')->where('nama', 'Cerai Hidup')->update(['kode' => 'CH']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('status_kawin', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
    }
};
