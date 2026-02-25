<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jabatan_anggota', function (Blueprint $table) {
            $table->string('nama_komisi')->nullable()->after('id_alat_kelengkapan');
        });
    }

    public function down(): void
    {
        Schema::table('jabatan_anggota', function (Blueprint $table) {
            $table->dropColumn('nama_komisi');
        });
    }
};
