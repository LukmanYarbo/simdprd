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
        // Drop foreign key first
        Schema::table('pegawai_asns', function (Blueprint $table) {
            $table->dropForeign(['id_status_kawin']);
        });

        // Change column type
        Schema::table('pegawai_asns', function (Blueprint $table) {
            $table->string('id_status_kawin')->change();
        });

        // Map existing integer IDs to their string 'kode' equivalents
        DB::table('pegawai_asns')->where('id_status_kawin', '1')->update(['id_status_kawin' => 'T']);
        DB::table('pegawai_asns')->where('id_status_kawin', '2')->update(['id_status_kawin' => 'K']);
        DB::table('pegawai_asns')->where('id_status_kawin', '3')->update(['id_status_kawin' => 'CH']);
        DB::table('pegawai_asns')->where('id_status_kawin', '4')->update(['id_status_kawin' => 'CM']);

        Schema::table('pegawai_asns', function (Blueprint $table) {
            // Note: skip strict DB-level foreign key, as strings map to `kode` which might not be indexed.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data mapping
        DB::table('pegawai_asns')->where('id_status_kawin', 'T')->update(['id_status_kawin' => '1']);
        DB::table('pegawai_asns')->where('id_status_kawin', 'K')->update(['id_status_kawin' => '2']);
        DB::table('pegawai_asns')->where('id_status_kawin', 'CH')->update(['id_status_kawin' => '3']);
        DB::table('pegawai_asns')->where('id_status_kawin', 'CM')->update(['id_status_kawin' => '4']);

        Schema::table('pegawai_asns', function (Blueprint $table) {
            $table->unsignedBigInteger('id_status_kawin')->change();
            $table->foreign('id_status_kawin')->references('id')->on('status_kawin')->onDelete('restrict');
        });
    }
};
