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
            $table->foreignId('id_komisi')->nullable()->after('id_jabatan')->constrained('jabatan_alat_kelengkapan')->nullOnDelete();
            $table->foreignId('id_banggar')->nullable()->after('id_komisi')->constrained('jabatan_alat_kelengkapan')->nullOnDelete();
            $table->foreignId('id_banmus')->nullable()->after('id_banggar')->constrained('jabatan_alat_kelengkapan')->nullOnDelete();
            $table->foreignId('id_balegda')->nullable()->after('id_banmus')->constrained('jabatan_alat_kelengkapan')->nullOnDelete();
            $table->foreignId('id_bk')->nullable()->after('id_balegda')->constrained('jabatan_alat_kelengkapan')->nullOnDelete();
            $table->foreignId('id_pansus')->nullable()->after('id_bk')->constrained('jabatan_alat_kelengkapan')->nullOnDelete();
            $table->foreignId('id_panja')->nullable()->after('id_pansus')->constrained('jabatan_alat_kelengkapan')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropForeign(['id_komisi']);
            $table->dropForeign(['id_banggar']);
            $table->dropForeign(['id_banmus']);
            $table->dropForeign(['id_balegda']);
            $table->dropForeign(['id_bk']);
            $table->dropForeign(['id_pansus']);
            $table->dropForeign(['id_panja']);
            
            $table->dropColumn([
                'id_komisi',
                'id_banggar',
                'id_banmus',
                'id_balegda',
                'id_bk',
                'id_pansus',
                'id_panja'
            ]);
        });
    }
};
