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
        DB::table('status_kawin')->where('kode', 'T')->update(['kode' => 'TK']);
        DB::table('anggota')->where('id_status_kawin', 'T')->update(['id_status_kawin' => 'TK']);
        DB::table('pegawai_asns')->where('id_status_kawin', 'T')->update(['id_status_kawin' => 'TK']);
        
        // Also check Keluarga table if it exists and uses this
        if (Schema::hasTable('keluarga')) {
            DB::table('keluarga')->where('id_status_kawin', 'T')->update(['id_status_kawin' => 'TK']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('status_kawin')->where('kode', 'TK')->update(['kode' => 'T']);
        DB::table('anggota')->where('id_status_kawin', 'TK')->update(['id_status_kawin' => 'T']);
        DB::table('pegawai_asns')->where('id_status_kawin', 'TK')->update(['id_status_kawin' => 'T']);
        
        if (Schema::hasTable('keluarga')) {
            DB::table('keluarga')->where('id_status_kawin', 'TK')->update(['id_status_kawin' => 'T']);
        }
    }
};
