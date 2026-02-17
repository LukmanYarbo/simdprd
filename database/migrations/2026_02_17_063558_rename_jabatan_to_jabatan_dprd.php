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
        // 1. Rename table jabatan -> jabatan_dprd
        Schema::rename('jabatan', 'jabatan_dprd');

        // 2. Modify anggota table
        Schema::table('anggota', function (Blueprint $table) {
            // Drop old foreign key
            // Default naming: table_column_foreign -> anggota_id_jabatan_foreign
            $table->dropForeign(['id_jabatan']);
            
            // Rename column
            $table->renameColumn('id_jabatan', 'id_dprd');
        });

        // 3. Add new foreign key
        Schema::table('anggota', function (Blueprint $table) {
            $table->foreign('id_dprd')
                  ->references('id')
                  ->on('jabatan_dprd')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Remove new foreign key
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropForeign(['id_dprd']);
            $table->renameColumn('id_dprd', 'id_jabatan');
        });

        // 2. Add old foreign key
        Schema::table('anggota', function (Blueprint $table) {
            // Note: We need to rename the table back first to reference it, 
            // or reference the renamed table if we prefer.
            // Let's rename table back first.
        });

        Schema::rename('jabatan_dprd', 'jabatan');

        Schema::table('anggota', function (Blueprint $table) {
             $table->foreign('id_jabatan')
                  ->references('id')
                  ->on('jabatan')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();
        });
    }
};
